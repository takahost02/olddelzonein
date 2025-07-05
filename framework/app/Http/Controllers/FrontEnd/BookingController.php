<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Bookings;
use App\Model\BookingIncome;
use App\Model\CompanyServicesModel;
use App\Model\Hyvikk;
use App\Model\MessageModel;
use App\Model\TeamModel;
use App\Model\Testimonial;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\VehicleTypeModel;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Validator;
use Auth;
use App\Model\BookingAlert;
use DB;
use App\Model\BookingPaymentsModel;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller {


    public function current_location(Request $request)
    {

        $latitude=null;
        $longitude=null;
        $current=null;
        $speed=null;


        $user=User::where('id', $request->driver_id)->first();

        if($user && $user->getMeta('document_id') != null)
        {
            $projectId = 'fleetflow-b7418';
            $collectionName = 'User_Locations';
            $documentId = $user->getMeta('document_id');
            $serviceAccountKeyFile = storage_path('firebase/firebase_credentials.json'); 
    
            $documentUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/{$collectionName}/{$documentId}";
    
            // Create a new Google Client
            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccountKeyFile);
            $client->addScope('https://www.googleapis.com/auth/datastore');
    
            // Obtain an OAuth 2.0 access token
            $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
    
            // Make the HTTP request to fetch the document
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->get($documentUrl);
    
            if ($response->successful()) {
                $data = $response->json();
                
                 $latitude=$data['fields']['latitude']['doubleValue'];
                 $longitude=$data['fields']['longitude']['doubleValue'];
                 $speed=$data['fields']['speed']['integerValue'] ."kmph";
   
                 $apiKey = (Hyvikk::api('api_key') ?? '-');
               
                 // Build the URL for the API request
                 $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}";
                 
                 // Initialize cURL
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, $url);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     
             
                 // Turn off SSL certificate verification
                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                 
                 // Make the API request
                 $response = curl_exec($ch);
                 curl_close($ch);
                 
                 // Decode the JSON response
                 $data = json_decode($response, true);
                 
                 // Check if the request was successful
                 if ($data['status'] == 'OK') {
                     // Extract the formatted address from the response
                     $address = $data['results'][0]['formatted_address'];
                     $current=$address;
                 } 
   
            }
        }


         



        return response()->json(['latitude'=> $latitude,'longitude'=>$longitude,
        'current_location'=>$current,'speed'=>$speed]);
    }


    public function get_vehicle(Request $request)
    {

       
        $date=date('Y-m-d', strtotime($request->pickup_date)).' '.$request->pickup_time;

        $from_date = $date;


        if(isset($request->booking_type) && $request->booking_type == "return_way")
        {
            $to_date = date('Y-m-d', strtotime($request->return_pickup_date)).' '.$request->return_pickup_time;
        }
        else
        {
            $to_date = $date;
        }


      


            
        $vehicleInterval = Hyvikk::get('vehicle_interval').' MINUTE';
       
    
            if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
                // $q = "select id from vehicles where in_service=1 and deleted_at is null  and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
                $q = "SELECT id
                FROM vehicles
                WHERE in_service = 1
                AND deleted_at IS NULL
                AND id NOT IN (
                    SELECT DISTINCT vehicle_id
                    FROM bookings
                    WHERE deleted_at IS NULL
                    AND cancellation = 0
                    AND (
                        (dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                        OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')
                        OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')
                    )
                )";
            } else {
                // $q = "select id from vehicles where in_service=1 and deleted_at is null and group_id=" . Auth::user()->group_id . " and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
    
                $q = "SELECT id
                FROM vehicles
                WHERE in_service = 1
                AND deleted_at IS NULL
                AND group_id = " . Auth::user()->group_id . "
                AND id NOT IN (
                    SELECT DISTINCT vehicle_id
                    FROM bookings
                    WHERE deleted_at IS NULL
                    AND cancellation = 0
                    AND (
                        (dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'
                        OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')
                        OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')
                    )
                )";
            }
            $d = collect(DB::select($q));
           
           

            $new = array();
        
         foreach ($d as $ro) {
            $vhc = VehicleModel::find($ro->id);

            if(Hyvikk::get('fare_mode') == "price_wise")
            {
                if($vhc && $vhc->getMeta('price') != 0 )
                {
                    $text = $vhc->make_name . "-" . $vhc->model_name . "-" . $vhc->license_plate;
                    $luggage=$vhc->luggage??'';
                    array_push($new, array("id" => $ro->id, "model_name" => $text,"luggage"=>$luggage));
                }

            }
           else if(Hyvikk::get('fare_mode') == "type_wise")
           {
                $text = $vhc->make_name . "-" . $vhc->model_name . "-" . $vhc->license_plate;
                $luggage=$vhc->luggage??'';
                array_push($new, array("id" => $ro->id, "model_name" => $text,"luggage"=>$luggage));
            }

            
        }

    
            $r = $new;
         
         
            if(count($r) > 0)
            {
           
                return response()->json(['status' => 100, 'data' => $r]);
            }
            else {
                    return response()->json(['status' => 200]);
            }
    
    }

    public function save_booking_alert(Request $request)
    {

        $data=new BookingAlert();
        $data->booking_id=$request->bookingid;
        $data->message=$request->message;
        $data->status=$request->status;
        if($data->save())
        {
            return response()->json(['status'=>100]);        
        }
        else
        {
            return response()->json(['status'=>200]);   
        }
    }


    public function invoice($id)
    {   
        $data['id'] = $id;
		$data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::find($id);
		return view("customer_dashboard.invoice", $data);
    }

    public function invoice_print($id)
    {
        
        $data['id'] = $id;
        $data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::whereId($id)->get()->first();
     
		return view("customer_dashboard.invoice_print", $data);
    }

    public function receipt($id)
    {   
        $data['id'] = $id;
		$data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::find($id);

        $data['payment']=BookingPaymentsModel::where('booking_id',$id)->latest()->first();
        return view("customer_dashboard.receipt", $data);
    }


    public function receipt_print($id)
    {
        $data['id'] = $id;
		$data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::find($id);

        $data['payment']=BookingPaymentsModel::where('booking_id',$id)->latest()->first();
        return view("customer_dashboard.receipt_print", $data);
    }

    
    public function show_booking_info(Request $request)
    {

        

        $perPage = $request->perpage;
        $currentPage = $request->query('page', 1);
        $data = Bookings::select("bookings.*")->where('bookings.customer_id',Auth::user()->id);
    
        if (!empty($request->search_text)) {
            $searchText = preg_replace('/\s+/', ' ', trim($request->search_text)); // Normalize input whitespace
        
            $data = $data->where(function($query) use ($searchText) {
                $query->whereRaw("REPLACE(REPLACE(REPLACE(pickup_addr, '\n', ''), '\r', ''), ' ', '') LIKE ?", ['%' . str_replace(' ', '', $searchText) . '%'])
                      ->orWhereRaw("REPLACE(REPLACE(REPLACE(dest_addr, '\n', ''), '\r', ''), ' ', '') LIKE ?", ['%' . str_replace(' ', '', $searchText) . '%']);
            });
        }
        
        
        if(isset($request->status))
        {
            
            if($request->status == "Pending")
            {
                $data=$data->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')
                ->where('bookings_meta.key', 'ride_status')
                ->whereIn('bookings_meta.value', [$request->status,'Upcoming']);
            }
            else
            {
                $data=$data->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')
                ->where('bookings_meta.key', 'ride_status')
                ->where('bookings_meta.value', $request->status);
            }
        }

        if(isset($request->start_date) && isset($request->end_date)) {
            $startDate = $request->start_date . " 00:00:00";
            $endDate = $request->end_date . " 23:59:59";
            $data = $data->whereBetween('bookings.created_at', [$startDate, $endDate]);
        }
        

       
        $data = $data->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);

        return view('customer_dashboard.booking-info', compact('data'));
        
    }


    public function index()
    {
         return view('customer_dashboard.create_booking');
    }

    public function booking_fetch(Request $request)
    {
        $vehicle=VehicleModel::where('id',$request->id)->first();
        $v_type=null;
        if(isset($vehicle->type_id))
        {        
            $v_type=VehicleTypeModel::where('id',$vehicle->type_id)->first();
        }

        
        $full_date=date("d M 'y h.m A", strtotime($request->pickup_date.' '.$request->pickup_time));

        $key = Hyvikk::api('api_key');

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . str_replace(" ", "", $request->pickup_address) . "&destination=" . str_replace(" ", "", $request->dropoff_address) . "&mode=driving&units=metric&sensor=false&key=" . $key;
         
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          //Disable SSL certificate verification
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

          $data = curl_exec($ch);
          $response = json_decode($data, true);
          
            if ($response['status'] == "OK") {
                $total_kms = explode(" ", str_replace(",", "", $response['routes'][0]['legs'][0]['distance']['text']))[0];
            }
            else{
                $total_kms=0;
            }

                $v_type = VehicleTypeModel::find($vehicle->type_id);

                $type = strtolower(str_replace(" ", "", $v_type->vehicletype));

                $fare_details = array();


                if(Hyvikk::get('fare_mode') == "type_wise")
                {
                    $base_fare = Hyvikk::fare($type . '_base_fare');
                    $km_base = Hyvikk::fare($type . '_base_km');
                    $std_fare = Hyvikk::fare($type . '_std_fare');
                    $base_km = Hyvikk::fare($type . '_base_km');
    
                    if ($total_kms <= $km_base) {
                        $total_fare = $base_fare;
    
                    } else {
                        $total_fare = $base_fare + (($total_kms - $km_base) * $std_fare);
                    }
                    // calculate tax charges
                    $count = 0;
                    if (Hyvikk::get('tax_charge') != "null") {
                        $taxes = json_decode(Hyvikk::get('tax_charge'), true);
                        foreach ($taxes as $key => $val) {
                            $count = $count + $val;
                        }
                    }
                    $total_fare = round($total_fare, 2);
                    $tax_total = round((($total_fare * $count) / 100) + $total_fare, 2);
                    $total_tax_percent = $count;
                    $total_tax_charge_rs = round(($total_fare * $count) / 100, 2);
    
                    $arr=[
                        'total_fare'=>$total_fare,
                        'total_tax_percent'=>$total_tax_percent,
                        'total_tax_charge_rs'=>$total_tax_charge_rs,
                        'tax_total'=>$tax_total
                    ];
                }
                else if(Hyvikk::get('fare_mode') == "price_wise")
                {
                    if($vehicle->getMeta('price') != '0')
                    {

                        $total_fare=$vehicle->getMeta('price');

                        $count = 0;
                        if (Hyvikk::get('tax_charge') != "null") {
                            $taxes = json_decode(Hyvikk::get('tax_charge'), true);
                            foreach ($taxes as $key => $val) {
                                $count = $count + $val;
                            }
                        }
                        $total_fare = round($total_fare, 2);
                        $tax_total = round((($total_fare * $count) / 100) + $total_fare, 2);
                        $total_tax_percent = $count;
                        $total_tax_charge_rs = round(($total_fare * $count) / 100, 2);
        
                        $arr=[
                            'total_fare'=>$total_fare,
                            'total_tax_percent'=>$total_tax_percent,
                            'total_tax_charge_rs'=>$total_tax_charge_rs,
                            'tax_total'=>$tax_total
                        ];
                    }
                }

             

                $currency=Hyvikk::get('currency');
       

        return response()->json(['vehicle'=>$vehicle,'v_type'=>$v_type,'full_date'=>$full_date,'arr'=>$arr,'currency'=>$currency,'luggage'=>($vehicle->getMeta('luggage')??'-')]);
    }


    public function save_booking(Request $request)
    {
        $vehicle=VehicleModel::where('id',$request->vehicle_id)->first();

        $max_seats = VehicleTypeModel::find($vehicle->type_id)->seats;
		if($request->get("no_of_person") > $max_seats){
			return back()->withErrors(["error" => "Number of Travellers exceed seating capity of the vehicle | Seats Available : ".$max_seats.""])->withInput();
		}

        if (Auth::user() && Auth::user()->user_type == 'C') {
            $validation = Validator::make($request->all(), [
                'pickup_address' => 'required',
                'dropoff_address' => 'required|different:pickup_address',
                'pickup_date' => 'required|date_format:d-m-Y|after:today',
                'pickup_time' => 'required',
                'no_of_person' => 'required|integer',
            ]);

            if ($validation->fails()) {
                return back()->withErrors($validation)->withInput();
            } else {
                $id = Bookings::create(['customer_id' => Auth::user()->id,
                    'pickup_addr' => $request->pickup_address,
                    'dest_addr' => $request->dropoff_address,
                    'travellers' => $request->no_of_person,
                    'note' => $request->note,
                    'pickup' => date('Y-m-d', strtotime($request->pickup_date)) . " " . $request->pickup_time,
                    
                ])->id;

                $booking = Bookings::find($id);
                $booking->journey_date = $request->pickup_date;
                $booking->journey_time = $request->pickup_time;
                $booking->booking_type = 1;
                $booking->accept_status = 0; //0=yet to accept, 1= accept
                $booking->ride_status = "Pending";
                $booking->vehicle_typeid = $vehicle->type_id;
                $booking->vehicle_id = $vehicle->id;
                
                $key = (Hyvikk::api('api_key') ?? '-');
        
				$pickupAddress = urlencode($request->pickup_address);
				$dropoffAddress = urlencode($request->dropoff_address);
				
				$url1 = "https://maps.googleapis.com/maps/api/directions/json?origin={$pickupAddress}&destination={$dropoffAddress}&key={$key}";
				
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_URL, $url1);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				
				// Turn off SSL certificate verification
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
				
				$response1 = curl_exec($ch1);
				curl_close($ch1);
				
				$dataFetch1 = json_decode($response1, true);
				
				if ($dataFetch1['status'] === 'OK') {
					$totalTimeInSeconds = $dataFetch1['routes'][0]['legs'][0]['duration']['value'];
					$hours = floor($totalTimeInSeconds / 3600);
					$minutes = floor(($totalTimeInSeconds % 3600) / 60);
					$seconds = $totalTimeInSeconds % 60;
					$totalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

					$booking->total_time=$totalTime;
                    
                    $total_kms = explode(" ", str_replace(",", "", $dataFetch1['routes'][0]['legs'][0]['distance']['text']))[0];

				} else {
					$totalTime = "00:00:00";
					$booking->total_time=$totalTime;

                    $total_kms=0;
				}



                $v_type = VehicleTypeModel::find($vehicle->type_id);

                $type = strtolower(str_replace(" ", "", $v_type->vehicletype));
                $fare_details = array();

                if(Hyvikk::get('fare_mode') == "type_wise")
                {
                    $base_fare = Hyvikk::fare($type . '_base_fare');
                    $km_base = Hyvikk::fare($type . '_base_km');
                    $std_fare = Hyvikk::fare($type . '_std_fare');
                    $base_km = Hyvikk::fare($type . '_base_km');
    
                    if ($total_kms <= $km_base) {
                        $total_fare = $base_fare;
    
                    } else {
                        $total_fare = $base_fare + (($total_kms - $km_base) * $std_fare);
                    }
                    // calculate tax charges
                    $count = 0;
                    if (Hyvikk::get('tax_charge') != "null") {
                        $taxes = json_decode(Hyvikk::get('tax_charge'), true);
                        foreach ($taxes as $key => $val) {
                            $count = $count + $val;
                        }
                    }
                    $total_fare = round($total_fare, 2);
                    $tax_total = round((($total_fare * $count) / 100) + $total_fare, 2);
                    $total_tax_percent = $count;
                    $total_tax_charge_rs = round(($total_fare * $count) / 100, 2);
    
                    $arr=[
                        'total_fare'=>$total_fare,
                        'total_tax_percent'=>$total_tax_percent,
                        'total_tax_charge_rs'=>$total_tax_charge_rs,
                        'tax_total'=>$tax_total
                    ];
                }
                else if(Hyvikk::get('fare_mode') == "price_wise")
                {
                    if($vehicle->getMeta('price') != '0')
                    {
    
                        $total_fare=$vehicle->getMeta('price');
    
                        $count = 0;
                        if (Hyvikk::get('tax_charge') != "null") {
                            $taxes = json_decode(Hyvikk::get('tax_charge'), true);
                            foreach ($taxes as $key => $val) {
                                $count = $count + $val;
                            }
                        }
                        $total_fare = round($total_fare, 2);
                        $tax_total = round((($total_fare * $count) / 100) + $total_fare, 2);
                        $total_tax_percent = $count;
                        $total_tax_charge_rs = round(($total_fare * $count) / 100, 2);
        
                        $arr=[
                            'total_fare'=>$total_fare,
                            'total_tax_percent'=>$total_tax_percent,
                            'total_tax_charge_rs'=>$total_tax_charge_rs,
                            'tax_total'=>$tax_total
                        ];
                    }
                }



                

                $booking->total_kms=$total_kms;
                $booking->currency=Hyvikk::get('currency');


                $booking->save();


                if(isset($request->booking_type) && $request->booking_type  == "return_way")
                {
                    $ids = Bookings::create(['customer_id' => Auth::user()->id,
                    'pickup_addr' => $request->dropoff_address,
                    'dest_addr' => $request->pickup_address,
                    'travellers' => $request->no_of_person,
                    'note' => $request->note,
                    'pickup' => date('Y-m-d', strtotime($request->return_pickup_date)) . " " . $request->return_pickup_time,

                    'vehicle_id'=>$vehicle->id
                    ])->id;

        
                    $bookings = Bookings::find($ids);
                    $bookings->journey_date = date('Y-m-d', strtotime($request->return_pickup_date));
                    $bookings->journey_time = $request->return_pickup_time;
                    $bookings->booking_type = 1;
                    $bookings->accept_status = 0; //0=yet to accept, 1= accept
                    $bookings->ride_status = "Pending";
                    $bookings->vehicle_typeid = $request->vehicle_type;
                

                    $bookings->booking_type=$request->booking_type;

                    $bookings->parent_booking_id=$booking->id;

                 
                    

                    $key2 = (Hyvikk::api('api_key') ?? '-');
        
                    $pickupAddress2 = urlencode($request->pickup_address);
                    $dropoffAddress2 = urlencode($request->dropoff_address);
                    
                    $url2 = "https://maps.googleapis.com/maps/api/directions/json?origin={$pickupAddress2}&destination={$dropoffAddress2}&key={$key2}";
                    
                    $ch2 = curl_init();
                    curl_setopt($ch2, CURLOPT_URL, $url2);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    
                    // Turn off SSL certificate verification
                    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
                    
                    $response2 = curl_exec($ch2);
                    curl_close($ch2);
                    
                    $dataFetch2 = json_decode($response2, true);
                    
                    if ($dataFetch2['status'] === 'OK') {
                        $totalTimeInSeconds2 = $dataFetch2['routes'][0]['legs'][0]['duration']['value'];
                        $hours2 = floor($totalTimeInSeconds2 / 3600);
                        $minutes2 = floor(($totalTimeInSeconds2 % 3600) / 60);
                        $seconds2 = $totalTimeInSeconds2 % 60;
                        $totalTime2 = sprintf('%02d:%02d:%02d', $hours2, $minutes2, $seconds2);
    
                        $bookings->total_kms = explode(" ", str_replace(",", "", $dataFetch2['routes'][0]['legs'][0]['distance']['text']))[0];

                        $bookings->total_time=$totalTime2;


    
                    } else {
                        $totalTime2 = "00:00:00";
                        $bookings->total_time=$totalTime2;
                        $bookings->total_kms=0;
                    }

                    $bookings->save();
                }

                
                Address::updateOrCreate(['customer_id' => Auth::user()->id, 'address' => $request->pickup_address]);
                Address::updateOrCreate(['customer_id' => Auth::user()->id, 'address' => $request->dropoff_address]);
                $this->book_later_notification($booking->id, $booking->vehicle_typeid);
                if (Hyvikk::email_msg('email') == 1) {
                    Mail::to($booking->customer->email)->send(new VehicleBooked($booking));
                }
            }
            return redirect()->back()->with('success', 'Your Booking Save Successfully.');     
        }
        else {
            return redirect("/#login")->withErrors(["error" => "Please Login Fleet Manager"], 'login');
        }
    }

    public function book_later_notification($id, $type_id)
    {
        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "book_later_notification";
        $data['message'] = 'Data Received.';
        $data['title'] = "New Ride Request (Book Later)";
        $data['description'] = "Do you want to Accept it ?";
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array('riderequest_info' => array('user_id' => $booking->customer_id,
            'booking_id' => $booking->id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'book_date' => date('Y-m-d'),
            'book_time' => date('H:i:s'),
            'journey_date' => $booking->journey_date,
            'journey_time' => $booking->journey_time,
            'accept_status' => $booking->accept_status));
        if ($type_id == null) {
            $vehicles = VehicleModel::get()->pluck('id')->toArray();
        } else {
            $vehicles = VehicleModel::where('type_id', $type_id)->get()->pluck('id')->toArray();
        }
        $drivers = User::where('user_type', 'D')->get();
        foreach ($drivers as $d) {
            if (in_array($d->vehicle_id, $vehicles)) {
                // echo $d->vehicle_id . " " . $d->id . "<br>";
                if ($d->fcm_id != null) {
                    // PushNotification::app('appNameAndroid')
                    //     ->to($d->fcm_id)
                    //     ->send($data);

                    $push = new PushNotification('fcm');
                    $push->setMessage($data)
                        ->setApiKey(env('server_key'))
                        ->setDevicesToken([$d->fcm_id])
                        ->send();
                }
            }
        }

    }

}