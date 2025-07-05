<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequest;
use App\Model\Bookings;
use App\Model\Expense;
use App\Model\FuelModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FuelController extends Controller {
	public function __construct() {
		$this->middleware('permission:Fuel add', ['only' => ['create']]);
		$this->middleware('permission:Fuel edit', ['only' => ['edit']]);
		$this->middleware('permission:Fuel delete', ['only' => ['bulk_delete', 'destroy']]);
		$this->middleware('permission:Fuel list');
	}
public function index() {
    $user = Auth::user();

    // Get accessible vehicle IDs using the helper method
    $vehicle_ids = $this->getAccessibleVehicleIds($user);

    if ($user->user_type == "D") {
        // If user is driver, show only their own fuel entries
        $data['data'] = FuelModel::orderBy('id', 'desc')
            ->where('user_id', $user->id)
            ->get();
    } else {
        // For other users, show fuel entries for accessible vehicles
        $data['data'] = FuelModel::orderBy('id', 'desc')
            ->whereIn('vehicle_id', $vehicle_ids)
            ->get();
    }

    return view('fuel.index', $data);
}

private function getAccessibleVehicleIds($user)
{
    $vehicleQuery = DB::table('vehicles')->select('vehicles.id');

    // Only filter if user is not super admin
    if ($user->id != 1) {
        $vehicleQuery->join('vehicles_meta as vm', function ($join) {
            $join->on('vehicles.id', '=', 'vm.vehicle_id')
                ->where('vm.key', '=', 'assign_vowner_id');
        })
        ->where('vm.value', $user->id);
    }

    return $vehicleQuery->pluck('id')->toArray();
}

	public function create() {
		if (Auth::user()->user_type == "S" || Auth::user()->user_type != "V") {
			if (Auth::user()->group_id == null) {
				$data['vehicles'] = VehicleModel::whereIn_service("1")->get();
			} else {
				$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
			}
		}
		if (Auth::user()->user_type == "V") {
			$assign_vehicles = VehicleModel::whereIn_service("1")->whereMeta('assign_vowner_id', Auth::user()->id)->pluck('id')->toArray();
			$booking_associated_vehicle_1 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Upcoming')
				->pluck('vehicle_id')->toArray();
			$booking_associated_vehicle_2 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Ongoing')
				->pluck('vehicle_id')->toArray();
			$mergedArray = array_unique(array_merge($booking_associated_vehicle_1, $booking_associated_vehicle_2, $assign_vehicles));
			$data['vehicles'] = VehicleModel::whereIn('id', $mergedArray)->get();
			// dd($data['vehicles']);
		}
		$data['vendors'] = Vendor::where('type', 'fuel')->get();
		return view('fuel.create', $data);
	}
	public function store(FuelRequest $request) {
		// dd($request->all());
		$fuel = new FuelModel();
		$fuel->vehicle_id = $request->get('vehicle_id');
		$fuel->user_id = $request->get('user_id');
		$condition = FuelModel::orderBy('id', 'desc')->where('vehicle_id', $request->get('vehicle_id'))->first();
		// dd($condition->qty);
		if ($condition != null) {
			$fuel->start_meter = $request->get('start_meter');
			$fuel->end_meter = "0";
			$fuel->consumption = "0";
			$condition->end_meter = $end = $request->get('start_meter');
			if ($request->get('qty') == 0) {
				$condition->consumption = $con = 0;
			} else {
				$condition->consumption = $con = ($end - $condition->start_meter) / $condition->qty;
			}
			// dd($con);
			$condition->save();
		} else {
			$fuel->start_meter = $request->get('start_meter');
			$fuel->end_meter = "0";
			$fuel->consumption = "0";
		}
		$fuel->reference = $request->get('reference');
		$fuel->province = $request->get('province');
		$fuel->note = $request->get('note');
		$fuel->qty = $request->get('qty');
		$fuel->fuel_from = $request->get('fuel_from');
		$fuel->vendor_name = $request->get('vendor_name');
		$fuel->cost_per_unit = $request->get('cost_per_unit');
		$fuel->date = $request->get('date');
		$fuel->complete = $request->get("complete");
		$file = $request->file('image');
		if ($file && $file->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$fuel->image = $fileName1;
		}
		$fuel->save();
		$expense = new Expense();
		$expense->vehicle_id = $request->get('vehicle_id');
		$expense->user_id = $request->get('user_id');
		$expense->expense_type = '8';
		$expense->comment = $request->get('note');
		$expense->date = $request->get('date');
		$amount = $request->get('qty') * $request->get('cost_per_unit');
		$expense->amount = $amount;
		$expense->exp_id = $fuel->id;
		$expense->save();
		VehicleModel::where('id', $request->vehicle_id)->update(['mileage' => $request->start_meter]);
		return redirect('admin/fuel');
	}
	public function edit($id) {
		$data['data'] = $data = FuelModel::whereId($id)->get()->first();
		$data['vehicle_id'] = $data->vehicle_id;
		$data['vendors'] = Vendor::where('type', 'fuel')->get();
		return view('fuel.edit', $data);
	}
	public function update(FuelRequest $request) {
		// dd($request->all());
		$fuel = FuelModel::find($request->get("id"));
		// $form_data = $request->all();
		$old = FuelModel::where('vehicle_id', $fuel->vehicle_id)->where('end_meter', $fuel->start_meter)->first();
		if ($old != null) {
			$old->end_meter = $request->get('start_meter');
			$old->consumption = ($old->end_meter - $old->start_meter) / $old->qty;
			$old->save();
		}
		$fuel->start_meter = $request->get('start_meter');
		$fuel->reference = $request->get('reference');
		$fuel->province = $request->get('province');
		$fuel->note = $request->get('note');
		$fuel->qty = $request->get('qty');
		$fuel->fuel_from = $request->get('fuel_from');
		$fuel->vendor_name = $request->get('vendor_name');
		$fuel->cost_per_unit = $request->get('cost_per_unit');
		$fuel->date = $request->get('date');
		$fuel->complete = $request->get("complete");
		if ($fuel->end_meter != 0) {
			$fuel->consumption = ($fuel->end_meter - $request->get('start_meter')) / $request->get('qty');
		}
		$file = $request->file('image');
		if ($file && $file->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$fuel->image = $fileName1;
		}
		$fuel->save();
		$exp = Expense::where('exp_id', $request->get('id'))->where('expense_type', 8)->first();
		if ($exp != null) {
			$exp->amount = $request->get('qty') * $request->get('cost_per_unit');
			$exp->save();
		}
		VehicleModel::where('id', $request->vehicle_id)->update(['mileage' => $request->start_meter]);
		return redirect()->route('fuel.index');
	}
	public function destroy(Request $request) {
		$fuel = FuelModel::find($request->get('id'));
		if (!is_null($fuel->image) && file_exists('uploads/' . $fuel->image)) {
			unlink('uploads/' . $fuel->image);
		}
		$fuel->delete();
		Expense::where('exp_id', $request->get('id'))->where('expense_type', 8)->delete();
		return redirect()->route('fuel.index');
	}
	public function bulk_delete(Request $request) {
		// dd($request->all());
		$fuels = FuelModel::whereIn('id', $request->ids)->get();
		foreach ($fuels as $fuel) {
			if (!is_null($fuel->image) && file_exists('uploads/' . $fuel->image)) {
				unlink('uploads/' . $fuel->image);
			}
			$fuel->delete();
		}
		Expense::whereIn('exp_id', $request->ids)->where('expense_type', 8)->delete();
		return redirect()->back();
	}
}
