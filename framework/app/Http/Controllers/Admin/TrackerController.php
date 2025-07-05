<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\Bookings;
use App\Model\Hyvikk;
use App\Model\Settings;
use App\Model\VehicleModel;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TrackerController extends Controller
{



	private $username;
	private $password;
	private $serverLink;
	private $mapKey;

	public function __construct()
	{
		$this->username = Hyvikk::get('traccar_username');
		$this->password = Hyvikk::get('traccar_password');
		$this->serverLink = Hyvikk::get('traccar_server_link');
		$this->mapKey = Hyvikk::get('traccar_map_key');
	}

	public function traccarLocation(Request $request, $id = null)
	{
		//get only in service vehicles
	    $user = Auth::user();
        $vehiclesQuery = VehicleModel::where('in_service', 1);
        if ($user->id != 1) {
            $vehiclesQuery = $vehiclesQuery->whereHas('metas', function ($q) use ($user) {
                $q->where('key', 'assign_vowner_id')->where('value', $user->id);
            });
        }
        $vehicles = $vehiclesQuery->with('metas')->get();
		$selected_vehicle = VehicleModel::find($id);
		$selected_vehicle_id = $selected_vehicle->id ?? '';
		$error = '';
		$active_device_found = false;
		$positionIds = [];
		$positionsData = []; // Initialize positionsData as an empty array
		try {
			if (!$this->username || !$this->password || !$this->serverLink || !$this->mapKey) {
				throw new \Exception('Please enter Traccar UserName, Password, Traccar Server URL, and Google Map key in Traccar settings to see your vehicles on the map!');
			}
			$duplicateDeviceIds = $vehicles->flatMap(function ($vehicle) {
				return [$vehicle->metas->where('key', 'traccar_device_id')->first()?->value];
			})->unique()->values()->count();
			
			if ($duplicateDeviceIds !== $vehicles->count()) {
				throw new Exception('Error: Duplicate Traccar device IDs found in active vehicles.');
			}
			
			$currentTime = now()->tz('Asia/Kolkata')->format("Y-m-d H:i:s");
			$credentials = base64_encode($this->username . ':' . $this->password);
			$active_vehicle_base_uri = $this->serverLink.'api/devices?';
			if($selected_vehicle){
				$traccarDeviceId = $selected_vehicle->traccar_device_id;
				if (!$traccarDeviceId) {
					throw new \Exception('Traccar Device ID is not set for the selected vehicle: ' . $selected_vehicle->name);
				}
				// Fetch positions for a single vehicle
				$active_vehicle_base_uri .= http_build_query(['uniqueId' => $traccarDeviceId]);
			}
			else{
				
				$traccarDeviceIds = $vehicles->pluck('traccar_device_id')->filter();

				// Check if there are any IDs present
				if ($traccarDeviceIds->isEmpty()) {
					throw new \Exception('Traccar Device IDs are not set for any vehicle.');
				}

				// Get unique device IDs
				$uniqueDeviceIds = $traccarDeviceIds->unique()->toArray();

				// Initialize an empty array to hold the query string parameters
				$params = [];

				// Loop through each unique device ID and add it to the parameters array
				foreach ($uniqueDeviceIds as $deviceId) {
					$params[] = 'uniqueId=' . urlencode($deviceId);
				}

				// Combine the parameters array into a single string
				$queryString = implode('&', $params);

				// Append the query string to the base URI
				$active_vehicle_base_uri .= $queryString;
			}
			// dd($active_vehicle_base_uri);
			// dd($active_vehicle_base_uri);
			$client = new Client([
				'base_uri' => $active_vehicle_base_uri,
				'verify' => false,
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic ' . $credentials,
				],
			]);
			// dd($active_vehicle_base_uri);
			$response = $client->get('');
			if ($response->getStatusCode() == 200) {
				$devices = json_decode($response->getBody()->getContents(), true);
				// dd($devices);
				foreach($devices as $device){
					if ($device['status'] == 'online') {
						$active_device_found = true; // Set the flag if an active device is found
						array_push($positionIds,$device['positionId']);
					}
				}
				if (!$active_device_found) {
					if ($selected_vehicle) {
						throw new \Exception('The selected vehicle has no active device.');
					}
					throw new \Exception('No Active Devices Found.');
				}		
			}
			else{
				throw new \Exception('Error fetching devices: ' . $response->getReasonPhrase());
			}
			$position_base_uri = $this->serverLink . '/api/positions?';
			$params = [];
			foreach ($positionIds as $Id) {
				$params[] = 'id=' . urlencode($Id);
			}
			// Combine the parameters array into a single string
			$queryString = implode('&', $params);
			// Append the query string to the base URI
			$position_base_uri .= $queryString;
			// dd($position_base_uri,$positionIds);
			$client = new Client([
				'base_uri' => $position_base_uri,
				'verify' => false,
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic ' . $credentials,
				],
			]);
			$response = $client->get('');
			if ($response->getStatusCode() == 200) {
				$positions = json_decode($response->getBody()->getContents(), true);
				// dd($devices,$positions);
				foreach ($positions as $position) {
					$deviceId = $position['id'];
					$device = null;
					foreach ($devices as $dev) {
						if ($dev['positionId'] == $deviceId) {
							$device = $dev;
							break;
						}
					}
					$vehicle = $vehicles->firstWhere('traccar_device_id', $device['uniqueId']);
					if (!$vehicle) {
						continue;
					}
					$booking = Bookings::where('vehicle_id', $vehicle->id)
						->where('pickup', '<=', $currentTime)
						->where('dropoff', '>=', $currentTime)
						->with('driver')
						->with('vehicle')
						->latest()
						->first();

					$positionsData[] = [
						'position' => $position,
						'vehicle' => $vehicle,
						'booking' => $booking
					];
					
				}
				if(count($positionsData)==0){
					if($selected_vehicle){
							throw new \Exception('No Position Received For Vehicle:'.$selected_vehicle->make_name.' '.$selected_vehicle->model_name .' '.$selected_vehicle->license_plate . 'Please Check Traccar Device Id');
					}
					throw new \Exception('No Position Received For Vehicles Please Check Traccar Device Id In Vehicles:');
				}
				
			} else {
				throw new \Exception('Error fetching positions: ' . $response->getReasonPhrase());
			}
		} catch (\Throwable $e) {
			$error = $e->getMessage();
			if ($request->expectsJson()) {
				return response()->json(['error' => $error], 200);
			}
			return view('tracker.map', compact('error', 'vehicles', 'selected_vehicle_id'));
		}
		if ($request->ajax()) {
			return response()->json(compact('positionsData', 'vehicles', 'error', 'selected_vehicle_id'));
		}
		return view('tracker.map', compact('positionsData', 'vehicles', 'error', 'selected_vehicle_id'));
	}

	public function traccar_settings(Request $request)
	{
		return view('utilities.traccar_settings');
	}
	public function traccar_settings_store(Request $request)
	{
		$traccar_enable = 0;
		if ($request->traccar_enable == 1) {
			$traccar_enable = 1;
		}
		Settings::where('name', 'traccar_server_link')->update(['value' => $request->traccar_server_link]);
		Settings::where('name', 'traccar_enable')->update(['value' => $traccar_enable]);
		Settings::where('name', 'traccar_username')->update(['value' => $request->traccar_username]);
		Settings::where('name', 'traccar_password')->update(['value' => $request->traccar_password]);
		Settings::where('name', 'traccar_map_key')->update(['value' => $request->traccar_map_key]);
		return redirect()->route('traccar.settings')->with('message', 'Traccar Settings Updated!');
	}

}