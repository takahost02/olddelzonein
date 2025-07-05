<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Mail\BookingCancelled;
use App\Mail\CustomerInvoice;
use App\Mail\DriverBooked;
use App\Mail\VehicleBooked;
use App\Model\Address;
use App\Model\BookingIncome;
use App\Model\BookingPaymentsModel;
use App\Model\Bookings;
use App\Model\Hyvikk;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\ReasonsModel;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\VehicleTypeModel;
use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Illuminate\Support\Facades\Http;


class BookingsController extends Controller {
	public function __construct() {
		// $this->middleware(['role:Admin']);
// 		$this->middleware('permission:Bookings add', ['only' => ['create']]);
// 		$this->middleware('permission:Bookings edit', ['only' => ['edit']]);
// 		$this->middleware('permission:Bookings delete', ['only' => ['bulk_delete', 'destroy']]);
// 		$this->middleware('permission:Bookings list');
	}
	public function transactions() {
		$data['data'] = BookingPaymentsModel::orderBy('id', 'desc')->get();
		return view('bookings.transactions', $data);
	}
	public function transactions_fetch_data(Request $request) {
		if ($request->ajax()) {
			$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
			$payments = BookingPaymentsModel::select('booking_payments.*')->with('booking.customer')->orderBy('id', 'desc');
			return DataTables::eloquent($payments)
				->addColumn('customer', function ($row) {
					return ($row->booking->customer->name) ?? "";
				})
				->editColumn('amount', function ($row) {
					return ($row->amount) ? Hyvikk::get('currency') . " " . $row->amount : "";
				})
				->editColumn('created_at', function ($row) use ($date_format_setting) {
					$created_at = '';
					$created_at = [
						'display' => '',
						'timestamp' => '',
					];
					if (!is_null($row->created_at)) {
						$created_at = date($date_format_setting . ' h:i A', strtotime($row->created_at));
						return [
							'display' => date($date_format_setting . ' h:i A', strtotime($row->created_at)),
							'timestamp' => Carbon::parse($row->created_at),
						];
					}
					return $created_at;
				})
				->filterColumn('created_at', function ($query, $keyword) {
					$query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
				})
				->make(true);
		}
	}
	public function index() {
		$data['types'] = IncCats::get();
		$data['reasons'] = ReasonsModel::get();
		return view("bookings.index", $data);
	}
	
    private function getAccessibleVehicleIds($user)
    {
        $vehicleQuery = DB::table('vehicles')
            ->select('vehicles.id');
            $vehicleQuery->join('vehicles_meta as vm', function ($join) {
                $join->on('vehicles.id', '=', 'vm.vehicle_id')
                    ->where('vm.key', '=', 'assign_vowner_id');
            })
            ->where('vm.value', $user->id);
        return $vehicleQuery->pluck('id')->toArray(); 
    }
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $date_format_setting = Hyvikk::get('date_format') ?? 'd-m-Y';
            $user = Auth::user();

            if ($user->id == 1) {
                $bookings = Bookings::where('ride_type', 'oneway');
            } elseif ($user->user_type == "V") {
                
                $vehicleIds = $this->getAccessibleVehicleIds($user);
                $bookings = Bookings::whereIn('vehicle_id', $vehicleIds)
                                ->where('ride_type', 'oneway');
            }
            
            $statusMap = [
                1 => 'Trip Given to Driver',
                2 => 'Driver on the Way',
                3 => 'Driver Reached Warehouse',
                4 => 'Loading Started',
                5 => 'Loading Done',
                6 => 'Unloading Started',
                7 => 'Trip Finished',
                8 => 'Driver Coming Back',
            ];

            return DataTables::eloquent($bookings)
                ->addColumn('check', fn($user) => '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick="checkcheckbox();">')
                ->addColumn('customer', fn($row) => $row->customer->name ?? "")
                ->addColumn('travellers', fn($row) => $row->travellers ?? "")
                ->addColumn('ride_status', fn($row) => $statusMap[$row->status] ?? 'Unknown')
               ->addColumn('return_booking', function ($row) {
                    // Check if this booking is a parent of a return booking
                    $isReturn = $row->ride_type === 'oneway';
                    if ($isReturn) {
                        // Check if this booking is referenced as a parent_booking_id by any other booking
                        $hasReturn = \App\Model\Bookings::where('parent_booking_id', $row->id)->exists();
                
                        return $hasReturn
                            ? url('/assets/customer_dashboard/assets/img/return_way.svg')
                            : url('/assets/customer_dashboard/assets/img/one_way.svg');
                    }
                
                    return url('/assets/customer_dashboard/assets/img/one_way.svg');
                })
                ->editColumn('pickup_addr', fn($row) => str_replace(",", "<br/>", $row->pickup_addr))
                ->editColumn('dest_addr', fn($row) => str_replace(",", "<br/>", $row->dest_addr))
                ->editColumn('pickup', function ($row) use ($date_format_setting) {
                    return !is_null($row->pickup)
                        ? ['display' => date($date_format_setting . ' h:i A', strtotime($row->pickup)), 'timestamp' => \Carbon\Carbon::parse($row->pickup)]
                        : ['display' => '', 'timestamp' => ''];
                })
                ->editColumn('dropoff', function ($row) use ($date_format_setting) {
                    return !is_null($row->dropoff)
                        ? ['display' => date($date_format_setting . ' h:i A', strtotime($row->dropoff)), 'timestamp' => \Carbon\Carbon::parse($row->dropoff)]
                        : ['display' => '', 'timestamp' => ''];
                })
                ->editColumn('payment', function ($row) {
                    return $row->payment == 1
                        ? '<span class="text-success">' . __('fleet.paid1') . '</span>'
                        : '<span class="text-warning">' . __('fleet.pending') . '</span>';
                })
                ->editColumn('tax_total', fn($row) => $row->tax_total ? Hyvikk::get('currency') . " " . $row->tax_total : "")
                ->addColumn('vehicle', function ($row) {
                    $vehicle_type = VehicleTypeModel::find($row->getMeta('vehicle_typeid'));
                    return !empty($row->vehicle_id)
                        ? optional($row->vehicle)->make_name . '-' . optional($row->vehicle)->model_name . '-' . optional($row->vehicle)->license_plate
                        : $vehicle_type->displayname ?? "";
                })
                ->filterColumn('vehicle', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(vehicles.make_name , '-' , vehicles.model_name , '-' , vehicles.license_plate) like ?", ["%$keyword%"])
                        ->orWhereRaw("(vehicle_types.displayname like ? and bookings.vehicle_id IS NULL)", ["%$keyword%"]);
                })
                ->filterColumn('ride_status', function ($query, $keyword) {
                    $query->whereHas("metas", function ($q) use ($keyword) {
                        $q->where('key', 'ride_status')->whereRaw("value like ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('tax_total', function ($query, $keyword) {
                    $query->whereHas("metas", function ($q) use ($keyword) {
                        $q->where('key', 'tax_total')->whereRaw("value like ?", ["%{$keyword}%"]);
                    });
                })
                ->addColumn('action', fn($user) => view('bookings.list-actions', ['row' => $user]))
                ->filterColumn('payment', function ($query, $keyword) {
                    $query->whereRaw("IF(payment = 1 , '" . __('fleet.paid1') . "', '" . __('fleet.pending') . "') like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('pickup', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(pickup,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
                })
                ->filterColumn('dropoff', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(dropoff,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
                })
                ->filterColumn('travellers', function ($query, $keyword) {
                    $query->where("travellers", 'LIKE', '%' . $keyword . '%');
                })
                ->rawColumns(['payment', 'action', 'check', 'pickup_addr', 'dest_addr'])
                ->make(true);
        }
    }


    
    	
	
	public function receipt($id) {
		$data['id'] = $id;
		$data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::find($id);
		return view("bookings.receipt", $data);
	}
	function print($id) {
		$data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
		// $data['info'] = IncomeModel::whereId($book['income_id'])->first();
		$data['booking'] = Bookings::whereId($id)->get()->first();
		return view("bookings.print", $data);
	}
	public function payment($id) {
		$booking = Bookings::find($id);
		$booking->payment = 1;
		$booking->payment_method = "cash";
		$booking->save();
		BookingPaymentsModel::create(['method' => 'cash', 'booking_id' => $id, 'amount' => $booking->tax_total, 'payment_details' => null, 'transaction_id' => null, 'payment_status' => __('fleet.succeeded')]);
		return redirect()->route('bookings.index');
	}
	public function complete_post(Request $request) {
		// dd($request->all());
		if ($request->get('total') < 1) {
			return redirect()->back()->withErrors(["error" => "Invoice amount cannot be Zero or less than 0"]);
		}
		$booking = Bookings::find($request->get("booking_id"));
		$booking->setMeta([
			'customerId' => $request->get('customerId'),
			'vehicleId' => $request->get('vehicleId'),
			'day' => $request->get('day'),
			'mileage' => $request->get('mileage'),
			'waiting_time' => $request->get('waiting_time'),
			'date' => $request->get('date'),
			'total' => round($request->get('total'), 2),
			'total_kms' => $request->get('mileage'),
			'ride_status' => 'Ongoing',
			'tax_total' => round($request->get('tax_total'), 2),
			'total_tax_percent' => round($request->get('total_tax_charge'), 2),
			'total_tax_charge_rs' => round($request->total_tax_charge_rs, 2),
		]);
		if ($booking->driver && $booking->driver->driver_commision != null) {
			$commision = $booking->driver->driver_commision;
			$amnt = $commision;
			if ($booking->driver->driver_commision_type == 'percent') {
				$amnt = ($booking->total * $commision) / 100;
			}
			// $driver_amount = round($booking->total - $amnt, 2);
			$booking->driver_amount = $amnt;
			$booking->driver_commision = $booking->driver->driver_commision;
			$booking->driver_commision_type = $booking->driver->driver_commision_type;
			$booking->save();
		}
		$booking->save();
		$id = IncomeModel::create([
			"vehicle_id" => $request->get("vehicleId"),
			// "amount" => $request->get('total'),
			"amount" => $request->get('tax_total'),
			"driver_amount" => $booking->driver_amount ?? $request->get('tax_total'),
			"user_id" => $request->get("customerId"),
			"date" => $request->get('date'),
			"mileage" => $request->get("mileage"),
			"income_cat" => $request->get("income_type"),
			"income_id" => $booking->id,
			"tax_percent" => $request->get('total_tax_charge'),
			"tax_charge_rs" => $request->total_tax_charge_rs,
		])->id;
		BookingIncome::create(['booking_id' => $request->get("booking_id"), "income_id" => $id]);
		$xx = Bookings::whereId($request->get("booking_id"))->first();
		// $xx->status = 1;
		$xx->receipt = 1;
		$xx->save();
		if (Hyvikk::email_msg('email') == 1) {
			Mail::to($booking->customer->email)->send(new CustomerInvoice($booking));
		}
		return redirect()->route("bookings.index");
	}
	public function complete($id) {
		$xx = Bookings::find($id);
		$xx->status = 1;
		$xx->completed_at = date('Y-m-d H:i:s');
		$xx->ride_status = "Completed";
		$xx->save();
		return redirect()->route("bookings.index");
	}
	public function get_driver(Request $request) {

        //  dd($request->all());

        $from_date = $request->get("from_date");

        $to_date = $request->get("to_date");

		$driverInterval = Hyvikk::get('driver_interval').' MINUTE';

        $req_type = $request->get("req");

        if ($req_type == "new" || $request->req == 'true') {

			

			// This query is old version 

			// $q="SELECT id, name AS text

			// FROM users

			// WHERE user_type = 'D'

			// AND deleted_at IS NULL

			// AND id NOT IN (

			// 	SELECT DISTINCT driver_id

			// 	FROM bookings

			// 	WHERE deleted_at IS NULL

			// 	AND (

			// 		(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL ".$driverInterval.") AND DATE_SUB('" . $to_date . "', INTERVAL ".$driverInterval."))

			// 		OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL ".$driverInterval.") AND DATE_SUB('" . $to_date . "', INTERVAL ".$driverInterval."))

			// 		OR (pickup < DATE_ADD('" . $from_date . "', INTERVAL ".$driverInterval.") AND dropoff > DATE_SUB('" . $to_date . "', INTERVAL ".$driverInterval."))

			// 	)

			// )";







			// Un comment this if the below query does not work



			// $q = "SELECT id, name AS text

			// FROM users

			// WHERE user_type = 'D'

			// AND deleted_at IS NULL

			// AND id NOT IN (

			// 	SELECT DISTINCT driver_id

			// 	FROM bookings

			// 	WHERE deleted_at IS NULL

			// 	AND (

			// 		(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

			// 		OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

			// 		OR (pickup < DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND dropoff > DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

			// 	)

			// )

			// AND id NOT IN (

			// 	SELECT DISTINCT driver_id

			// 	FROM bookings

			// 	WHERE deleted_at IS NULL

			// 	AND (

			// 		(pickup BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

			// 		OR (dropoff BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

			// 		OR (dropoff > '" . $to_date . "' AND pickup < DATE_ADD('" . $to_date . "', INTERVAL " . $driverInterval . "))

			// 	)

			// )";





			

			$q = "SELECT id, name AS text

			FROM users

			WHERE user_type = 'D'

			AND deleted_at IS NULL

			AND id NOT IN (

				SELECT DISTINCT driver_id

				FROM bookings

				WHERE deleted_at IS NULL

				AND cancellation = 0

				AND (

					(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

					OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

					OR (pickup < DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND dropoff > DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

				)

			)

			AND id NOT IN (

				SELECT DISTINCT driver_id

				FROM bookings

				WHERE deleted_at IS NULL

				AND cancellation = 0

				AND (

					(pickup BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

					OR (dropoff BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

					OR (dropoff > '" . $to_date . "' AND pickup < DATE_ADD('" . $to_date . "', INTERVAL " . $driverInterval . "))

				)

			)";





            $new = [];

            $d = collect(DB::select($q));

            foreach ($d as $ro) {

                array_push($new, array("id" => $ro->id, "text" => $ro->text));

            }

            $r['data'] = $new;

        } else {

            // dd('test');

            $id = $request->get("id");

            $current = Bookings::find($id);

			$b = Bookings::where('parent_booking_id', $current->id)->first();

			if(isset($b))
			{

				$q = "SELECT id, name AS text

			FROM users

			WHERE user_type = 'D'

			AND deleted_at IS NULL

			AND id NOT IN (

				SELECT DISTINCT driver_id

				FROM bookings

				WHERE deleted_at IS NULL

				AND cancellation = 0

				AND (

					(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

					OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

					OR (pickup < DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND dropoff > DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))

				)

			)

			AND id NOT IN (

				SELECT DISTINCT driver_id

				FROM bookings

				WHERE deleted_at IS NULL

				AND cancellation = 0

				AND (

					(pickup BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

					OR (dropoff BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')

					OR (dropoff > '" . $to_date . "' AND pickup < DATE_ADD('" . $to_date . "', INTERVAL " . $driverInterval . "))

				)

				  AND driver_id <> '" . $current->driver_id . "' 
              		AND driver_id <> '" . $b->driver_id . "'

			)";


			}
			else
			{

				$q = "SELECT id, name AS text

				FROM users
	
				WHERE user_type = 'D'
	
				AND deleted_at IS NULL
	
				AND id NOT IN (
	
					SELECT DISTINCT driver_id
	
					FROM bookings
	
					WHERE deleted_at IS NULL
	
					AND cancellation = 0
	
					AND (
	
						(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))
	
						OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))
	
						OR (pickup < DATE_ADD('" . $from_date . "', INTERVAL " . $driverInterval . ") AND dropoff > DATE_SUB('" . $to_date . "', INTERVAL " . $driverInterval . "))
	
					)
	
				)
	
				AND id NOT IN (
	
					SELECT DISTINCT driver_id
	
					FROM bookings
	
					WHERE deleted_at IS NULL
	
					AND cancellation = 0
	
					AND (
	
						(pickup BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')
	
						OR (dropoff BETWEEN DATE_SUB('" . $from_date . "', INTERVAL " . $driverInterval . ") AND '" . $to_date . "')
	
						OR (dropoff > '" . $to_date . "' AND pickup < DATE_ADD('" . $to_date . "', INTERVAL " . $driverInterval . "))
	
					)
	
					AND driver_id <> '" . $current->driver_id . "'
	
				)";
	


			}


        	




            $d = collect(DB::select($q));

            $chk = $d->where('id', $current->driver_id);

            $r['show_error'] = "yes";

            if (count($chk) > 0) {

                $r['show_error'] = "no";

            }

            $new = array();

            foreach ($d as $ro) {

                if ($ro->id === $current->driver_id) {

                    array_push($new, array("id" => $ro->id, "text" => $ro->text, 'selected' => true));

                } else {

                    array_push($new, array("id" => $ro->id, "text" => $ro->text));

                }

            }

            $r['data'] = $new;

        }

        // dd($r);

        $new1 = [];

        foreach ($r['data'] as $r1) {

            $user = User::where('id', $r1['id'])->first();

            if ($user->getMeta('is_active') == 1) {

                // dd($r1);

                $new1[] = $r1;

            }

        }

        $r['data'] = $new1;

        return $r;

    }



// (this one)public function get_vehicle(Request $request) {



//     $from_date = $request->get("from_date");

//     $to_date = $request->get("to_date");



//     $req_type = $request->get("req");

//     $vehicleInterval = Hyvikk::get('vehicle_interval').' MINUTE';

//     if ($req_type == "new") {

//         $xy = array();

//         if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {


//             $q = "SELECT id

//             FROM vehicles

//             WHERE in_service = 1

//             AND deleted_at IS NULL

//             AND id NOT IN (

//                 SELECT DISTINCT vehicle_id

//                 FROM bookings

//                 WHERE deleted_at IS NULL

//                 AND cancellation = 0

//                 AND (

//                     (dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'

//                     OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')

//                     OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

//                 )

//             )";






//         } else {




//             $q = "SELECT id

//             FROM vehicles

//             WHERE in_service = 1

//             AND deleted_at IS NULL

//             AND group_id = " . Auth::user()->group_id . "

//             AND id NOT IN (

//                 SELECT DISTINCT vehicle_id

//                 FROM bookings

//                 WHERE deleted_at IS NULL

//                 AND cancellation = 0

//                 AND (

//                     (dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'

//                     OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')

//                     OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

//                 )

//             )";

//         }

//         $d = collect(DB::select($q));



//         $new = array();

//         foreach ($d as $ro) {
				

//             $vhc = VehicleModel::find($ro->id);
//             // $text = $vhc->make_name . "-" . $vhc->model_name . "-" . $vhc->license_plate;
//             // array_push($new, array("id" => $ro->id, "text" => $text));

            
//             if(Hyvikk::get('fare_mode') == "price_wise")
//             {
            
//                 if($vhc && $vhc->getMeta('price') != 0 )
//                 {
//                     $text = ($vhc->make_name??'-') . "-" . ($vhc->model_name??'-') . "-" . ($vhc->license_plate??'-');

//                     array_push($new, array("id" => $ro->id, "text" => $text));
//                 }

//             }
//           else if(Hyvikk::get('fare_mode') == "type_wise")
//           {
        

//                 $text = ($vhc->make_name??'-') . "-" . ($vhc->model_name??'-') . "-" . ($vhc->license_plate??'-');

//                 array_push($new, array("id" => $ro->id, "text" => $text));
//             }

//         }

//         $r['data'] = $new;

//         return $r;



//     } else {

//         $id = $request->get("id");

//         $current = Bookings::find($id);

//         if ($current->vehicle_typeid != null) {

//             $condition = " and type_id = '" . $current->vehicle_typeid . "'";



//         } else {

//             $condition = "";

//         }



//         if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {


// $b = Bookings::where('parent_booking_id', $id)->first();



//             if(isset($b))
//             {
//                 $from=$request->get("from_date");

//                 $to=$request->get("to_date");


//                 $q = "SELECT id

//                 FROM vehicles

//                 WHERE in_service = 1" . $condition . "

// 				AND deleted_at IS NULL

//                 AND id NOT IN (

//                     SELECT DISTINCT vehicle_id

//                     FROM bookings

//                     WHERE id != $id and  id != $b->id

//                     AND deleted_at IS NULL

//                     AND cancellation = 0

//                     AND (

//                         (dropoff BETWEEN DATE_ADD('" . $from . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to . "', INTERVAL " . $vehicleInterval . "))

//                         OR (pickup BETWEEN DATE_ADD('" . $from . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to . "', INTERVAL " . $vehicleInterval . "))

//                         OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to . "')

//                     )

//                 )";



//             }
//             else
//             {
//                 $q = "SELECT id

//                 FROM vehicles

//                 WHERE in_service = 1" . $condition . "

// 				AND deleted_at IS NULL

//                 AND id NOT IN (

//                     SELECT DISTINCT vehicle_id

//                     FROM bookings

//                     WHERE id != $id

//                     AND deleted_at IS NULL

//                     AND cancellation = 0

//                     AND (

//                         (dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

//                         OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

//                         OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

//                     )

//                 )";
//             }

        




//         } else {


// $b = Bookings::where('parent_booking_id', $id)->first();


//             if(isset($b))
//             {

//                 $from1=$request->get("from_date");

//                 $to1=$request->get("to_date");

//                 $q = "SELECT id

//                 FROM vehicles

//                 WHERE in_service = 1" . $condition . "

//                 AND group_id = " . Auth::user()->group_id . "

//                 AND id NOT IN (

//                     SELECT DISTINCT vehicle_id

//                     FROM bookings

//                     WHERE id != $id and  id != $b->id

//                     AND deleted_at IS NULL

//                     AND cancellation = 0

//                     AND (

//                         (dropoff BETWEEN DATE_ADD('" . $from1 . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to1 . "', INTERVAL " . $vehicleInterval . "))

//                         OR (pickup BETWEEN DATE_ADD('" . $from1 . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to1 . "', INTERVAL " . $vehicleInterval . "))

//                         OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from1 . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to1 . "')

//                     )

//                 )";
//             }
//             else
//             {
//                 $q = "SELECT id

//                 FROM vehicles

//                 WHERE in_service = 1" . $condition . "

//                 AND group_id = " . Auth::user()->group_id . "

//                 AND id NOT IN (

//                     SELECT DISTINCT vehicle_id

//                     FROM bookings

//                     WHERE id != $id

//                     AND deleted_at IS NULL

//                     AND cancellation = 0

//                     AND (

//                         (dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

//                         OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

//                         OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

//                     )

//                 )";
//             }

//         }



//         $d = collect(DB::select($q));



//         $chk = $d->where('id', $current->vehicle_id);

//         $r['show_error'] = "yes";

//         if (count($chk) > 0) {

//             $r['show_error'] = "no";

//         }



//         $new = array();

//         foreach ($d as $ro) {
//             $vhc = VehicleModel::find($ro->id);
//             if(Hyvikk::get('fare_mode') == "price_wise")
//             {
//                 if($vhc && $vhc->getMeta('price') != 0 )
//                 {
//                     $text = ($vhc->make_name??'-') . "-" . ($vhc->model_name??'-') . "-" . ($vhc->license_plate??'-');

//                     if ($ro->id == $current->vehicle_id)
//                     {
//                         array_push($new, array("id" => $ro->id, "text" => $text, "selected" => true));
//                     }
//                     else
//                     {
//                         array_push($new, array("id" => $ro->id, "text" => $text));
//                     }
                    
//                 }

//             }
//           else if(Hyvikk::get('fare_mode') == "type_wise")
//           {
//                 $text = ($vhc->make_name??'-') . "-" . ($vhc->model_name??'-') . "-" . ($vhc->license_plate??'-');
//                 if ($ro->id == $current->vehicle_id)
//                 {
//                     array_push($new, array("id" => $ro->id, "text" => $text, "selected" => true));
//                 }
//                 else
//                 {
//                     array_push($new, array("id" => $ro->id, "text" => $text));
//                 }
//             }

//         }


//         $r['data'] = $new;

//         return $r;

//     }



// }


	// public function get_vehicle(Request $request) {



	// 	$from_date = $request->get("from_date");

	// 	$to_date = $request->get("to_date");

	// 	$req_type = $request->get("req");

	// 	$vehicleInterval = Hyvikk::get('vehicle_interval').' MINUTE';

	// 	if ($req_type == "new") {

	// 		$xy = array();

	// 		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {

	// 			// $q = "select id from vehicles where in_service=1 and deleted_at is null  and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";

	// 			$q = "SELECT id

	// 			FROM vehicles

	// 			WHERE in_service = 1

	// 			AND deleted_at IS NULL

	// 			AND id NOT IN (

	// 				SELECT DISTINCT vehicle_id

	// 				FROM bookings

	// 				WHERE deleted_at IS NULL

	// 				AND cancellation = 0

	// 				AND (

	// 					(dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'

	// 					OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')

	// 					OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

	// 				)

	// 			)";

	// 		} else {

	// 			// $q = "select id from vehicles where in_service=1 and deleted_at is null and group_id=" . Auth::user()->group_id . " and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";



	// 			$q = "SELECT id

	// 			FROM vehicles

	// 			WHERE in_service = 1

	// 			AND deleted_at IS NULL

	// 			AND group_id = " . Auth::user()->group_id . "

	// 			AND id NOT IN (

	// 				SELECT DISTINCT vehicle_id

	// 				FROM bookings

	// 				WHERE deleted_at IS NULL

	// 				AND cancellation = 0

	// 				AND (

	// 					(dropoff BETWEEN '" . $from_date . "' AND '" . $to_date . "'

	// 					OR pickup BETWEEN '" . $from_date . "' AND '" . $to_date . "')

	// 					OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

	// 				)

	// 			)";

	// 		}

	// 		$d = collect(DB::select($q));



	// 		$new = array();

	// 		foreach ($d as $ro) {

	// 			$vhc = VehicleModel::find($ro->id);

	// 			$text = $vhc->make_name . "-" . $vhc->model_name . "-" . $vhc->license_plate;

	// 			array_push($new, array("id" => $ro->id, "text" => $text));



	// 		}

	// 		//dd($new);

	// 		$r['data'] = $new;

	// 		return $r;



	// 	} else {

	// 		$id = $request->get("id");

	// 		$current = Bookings::find($id);

	// 		if ($current->vehicle_typeid != null) {

	// 			$condition = " and type_id = '" . $current->vehicle_typeid . "'";



	// 		} else {

	// 			$condition = "";

	// 		}



	// 		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {

	// 			// $q = "select id from vehicles where in_service=1 " . $condition . " and id not in (select vehicle_id from bookings where id!=$id and  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";

	// 			$q = "SELECT id

	// 			FROM vehicles

	// 			WHERE in_service = 1" . $condition . "

	// 			AND id NOT IN (

	// 				SELECT DISTINCT vehicle_id

	// 				FROM bookings

	// 				WHERE id != $id

	// 				AND deleted_at IS NULL

	// 				AND cancellation = 0

	// 				AND (

	// 					(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

	// 					OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

	// 					OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

	// 				)

	// 			)";

	// 		} else {

	// 			// $q = "select id from vehicles where in_service=1 " . $condition . " and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where id!=$id and  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";

	// 			$q = "SELECT id

	// 			FROM vehicles

	// 			WHERE in_service = 1" . $condition . "

	// 			AND group_id = " . Auth::user()->group_id . "

	// 			AND id NOT IN (

	// 				SELECT DISTINCT vehicle_id

	// 				FROM bookings

	// 				WHERE id != $id

	// 				AND deleted_at IS NULL

	// 				AND cancellation = 0

	// 				AND (

	// 					(dropoff BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

	// 					OR (pickup BETWEEN DATE_ADD('" . $from_date . "', INTERVAL " . $vehicleInterval . ") AND DATE_SUB('" . $to_date . "', INTERVAL " . $vehicleInterval . "))

	// 					OR (DATE_ADD(dropoff, INTERVAL " . $vehicleInterval . ") >= '" . $from_date . "' AND DATE_SUB(pickup, INTERVAL " . $vehicleInterval . ") <= '" . $to_date . "')

	// 				)

	// 			)";

	// 		}



	// 		$d = collect(DB::select($q));



	// 		$chk = $d->where('id', $current->vehicle_id);

	// 		$r['show_error'] = "yes";

	// 		if (count($chk) > 0) {

	// 			$r['show_error'] = "no";

	// 		}



	// 		$new = array();

	// 		foreach ($d as $ro) {

	// 			$vhc = VehicleModel::find($ro->id);

	// 			$text = $vhc->make_name . "-" . $vhc->model_name . "-" . $vhc->license_plate;

	// 			if ($ro->id == $current->vehicle_id) {

	// 				array_push($new, array("id" => $ro->id, "text" => $text, "selected" => true));

	// 			} else {

	// 				array_push($new, array("id" => $ro->id, "text" => $text));

	// 			}

	// 		}

	// 		$r['data'] = $new;

	// 		return $r;

	// 	}



	// }

	public function calendar_event($id) {
		$data['booking'] = Bookings::find($id);
		return view("bookings.event", $data);
	}
	public function calendar_view() {
		$booking = Bookings::where('user_id', Auth::user()->id)->exists();
		return view("bookings.calendar", compact('booking'));
	}
	public function service_view($id) {
		$data['service'] = ServiceReminderModel::find($id);
		return view("bookings.service_event", $data);
	}
	public function calendar(Request $request) {
		$data = array();
		$start = $request->get("start");
		$end = $request->get("end");
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$b = Bookings::get();
		} else {
			$vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
			$b = Bookings::whereIn('vehicle_id', $vehicle_ids)->get();
		}
		foreach ($b as $booking) {
			$x['start'] = $booking->pickup;
			$x['end'] = $booking->dropoff;
			if ($booking->status == 1) {
				$color = "grey";
			} else {
				$color = "red";
			}
			$x['backgroundColor'] = $color;
			$x['title'] = $booking->customer->name."\n"."Ride Status:".($booking->ride_status??'-');
			$x['id'] = $booking->id;
			$x['type'] = 'calendar';
			array_push($data, $x);
		}
		$reminders = ServiceReminderModel::get();
		foreach ($reminders as $r) {
			$interval = substr($r->services->overdue_unit, 0, -3);
			$int = $r->services->overdue_time . $interval;
			$date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))));
			if ($r->last_date != 'N/D') {
				$date = date('Y-m-d', strtotime($int, strtotime($r->last_date)));
			}
			$x['start'] = $date;
			$x['end'] = $date;
			$color = "green";
			$x['backgroundColor'] = $color;
			$x['title'] = $r->services->description."\n"."Ride Status:".($booking->ride_status??'-');
			$x['id'] = $r->id;
			$x['type'] = 'service';
			array_push($data, $x);
		}
		return $data;
	}
	public function create() {
		$user = Auth::user()->group_id;
		$data['customers'] = User::where('user_type', 'C')->get();
		$drivers = User::whereUser_type("D")->get();
		$data['drivers'] = [];
		foreach ($drivers as $d) {
			if ($d->getMeta('is_active') == 1) {
				$data['drivers'][] = $d;
			}
			$data['drivers'][] = $d;
		}
		$data['addresses'] = Address::where('customer_id', Auth::user()->id)->get();
		if ($user == null) {
			$data['vehicles'] = VehicleModel::whereIn_service("1")->get();
		} else {
			$data['vehicles'] = VehicleModel::where([['group_id', $user], ['in_service', '1']])->get();}
		return view("bookings.create", $data);
		//dd($data['vehicles']);
	}
	public function edit($id) {
		$booking = Bookings::whereId($id)->get()->first();


		if ($booking && $booking->vehicle_typeid != null) {
			$condition = " and type_id = '" . $booking->vehicle_typeid . "'";
		} else {
			$condition = "";
		}

		$ba = Bookings::where('parent_booking_id', $booking->id)->first();



		if(isset($ba))
		{

			$pickup=$booking->pickup;

			$dropoff=isset($b->dropoff) ? $b->dropoff : $pickup;

			$q = "select id,name,deleted_at from users where user_type='D' and deleted_at is null and id not in (select user_id from bookings where status=0 and  id!=" . $id . " and "."id!=" . $ba->id." and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
		}
		else
		{
			$q = "select id,name,deleted_at from users where user_type='D' and deleted_at is null and id not in (select user_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
		}

		
		

		// $drivers = collect(DB::select($q));
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {

			$b = Bookings::where('parent_booking_id', $booking->id)->first();



			if(isset($b))
			{

				$pickup=$booking->pickup;

				$dropoff=isset($b->dropoff) ? $b->dropoff : $pickup;
				
				$q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and "."id!=" . $b->id." and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $pickup . "' and '" . $dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $pickup . "' and '" . $dropoff . "'  or dropoff between '" . $pickup . "' and '" . $dropoff . "'))";
			}
			else
			{
				$q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "'  or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
			}


		

		} else {

		$b = Bookings::where('parent_booking_id', $booking->id)->first();



			if(isset($b))
			{

				$pickup=$booking->pickup;

				$dropoff=isset($b->dropoff) ? $b->dropoff : $pickup;

				$q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and  "."id!=" . $b->id." and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $pickup . "' and '" . $dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $pickup . "' and '" . $dropoff . "'  or dropoff between '" . $pickup . "' and '" . $dropoff . "'))";

			}
			else
			{
				$q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "'  or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
			}
		
		}

		
		


		$v_ids = array();
		$vehicles_data = collect(DB::select($q1));
		foreach ($vehicles_data as $v) {

			$vhc = VehicleModel::find($v->id);

			if(Hyvikk::get('fare_mode') == "price_wise")
			{
				if($vhc && $vhc->getMeta('price') != 0 )
				{
					$v_ids[] = $vhc->id;
				}
			}
			else if(Hyvikk::get('fare_mode') == "type_wise")
			{
				$v_ids[] = $vhc->id;
			}

			//$v_ids[] = $v->id;
		}

		$vehicles = VehicleModel::all();

		$index['drivers'] = [];
		$drivers = User::whereUser_type("D")->get();
		// $drivers = $this->get_driver($from_date,$to_date);
		foreach ($drivers as $d) {
			if ($d->getMeta('is_active') == 1) {
				$index['drivers'][] = $d;
			}
		}

		$index['vehicles'] = $vehicles;
		$index['data'] = $booking;
		$index['udfs'] = unserialize($booking->getMeta('udf'));
		$return_booking = Bookings::where('parent_booking_id', $id)->first();

        if ($return_booking) {
            $index['return_booking'] = $return_booking;
        }

		return view("bookings.edit", $index);
	}

	// public function destroy(Request $request) {
		
	// 	$b=Bookings::find($request->get('id'))->delete();	
	// 	IncomeModel::where('income_id', $request->get('id'))->where('income_cat', 1)->delete();
		
	// 	if(isset($request->check) && $request->check == 1)
	// 	{

	// 		if(isset($b->parent_booking_id))
	// 		{
	// 			Bookings::find($b->parent_booking_id)->delete();
	// 			IncomeModel::where('income_id', $b->parent_booking_id)->where('income_cat', 1)->delete();

	// 		}
	// 		else
	// 		{

// $c = Bookings::where('parent_booking_id', $b->id)->first();


	// 			Bookings::find($c->id)->delete();
	// 			IncomeModel::where('income_id', $c->id)->where('income_cat', 1)->delete();



	// 		}

	// 	}

	// 	return redirect()->route('bookings.index');
	// }


	public function destroy(Request $request)
{
    $booking = Bookings::find($request->get('id'));

    if (!$booking) {
        return redirect()->route('bookings.index')->with('error', 'Booking not found');
    }

    // Delete related income record
    IncomeModel::where('income_id', $booking->id)->where('income_cat', 1)->delete();

    // Check if we also need to delete parent or child booking
    if ($request->has('check') && $request->check == 1) {

        // If the booking has a parent, delete the parent
        if ($booking->parent_booking_id) {
            $parent = Bookings::find($booking->parent_booking_id);
            if ($parent) {
                IncomeModel::where('income_id', $parent->id)->where('income_cat', 1)->delete();
                $parent->delete();
            }

        } else {
            // Else find the child booking using meta table
            $child = Bookings::where('parent_booking_id', $booking->id)->first();


            if ($child) {
                IncomeModel::where('income_id', $child->id)->where('income_cat', 1)->delete();
                $child->delete();
            }
        }
    }

    // Finally delete the main booking
    $booking->delete();

    return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
}

	protected function check_booking($pickup, $dropoff, $vehicle) {
		$chk = DB::table("bookings")
			->where("status", 0)
			->where("vehicle_id", $vehicle)
			->whereNull("deleted_at")
			->where("pickup", ">=", $pickup)
			->where("dropoff", "<=", $dropoff)
			->get();
		if (count($chk) > 0) {
			return false;
		} else {
			return true;
		}
	}
	public function store(BookingRequest $request) {

		$max_seats = VehicleModel::find($request->get('vehicle_id'))->types->seats;
		$xx = $this->check_booking($request->get("pickup"), $request->get("dropoff"), $request->get("vehicle_id"));
		if ($xx) {
			if ($request->get("travellers") > $max_seats) {
				return redirect()->route("bookings.create")->withErrors(["error" => "Number of Travellers exceed seating capity of the vehicle | Seats Available : " . $max_seats . ""])->withInput();
			} else {
				$id = Bookings::create($request->all())->id;
				Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('pickup_addr')]);
				Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('dest_addr')]);
				$booking = Bookings::find($id);
				$booking->user_id = $request->get("user_id");
				$booking->driver_id = $request->get('driver_id');
				$dropoff = Carbon::parse($booking->dropoff);
				$pickup = Carbon::parse($booking->pickup);
				$diff = $pickup->diffInMinutes($dropoff);
				$booking->note = $request->get('note');
				$booking->duration = $diff;
				$booking->udf = serialize($request->get('udf'));
				$booking->accept_status = 1; //0=yet to accept, 1= accept
				$booking->ride_status = "Pending";
				$booking->ride_type = "oneway";
				$booking->journey_date = date('d-m-Y', strtotime($booking->pickup));
				$booking->journey_time = date('H:i:s', strtotime($booking->pickup));


				$key = (Hyvikk::api('api_key') ?? '-');
        
				$pickupAddress = urlencode($request->get('pickup_addr'));
				$dropoffAddress = urlencode($request->get('dest_addr'));
				
				$url = "https://maps.googleapis.com/maps/api/directions/json?origin={$pickupAddress}&destination={$dropoffAddress}&key={$key}";
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				// Turn off SSL certificate verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				
				$response = curl_exec($ch);
				curl_close($ch);
				
				$dataFetch = json_decode($response, true);
				
				if ($dataFetch['status'] === 'OK') {
					$totalTimeInSeconds = $dataFetch['routes'][0]['legs'][0]['duration']['value'];
					$hours = floor($totalTimeInSeconds / 3600);
					$minutes = floor(($totalTimeInSeconds % 3600) / 60);
					$seconds = $totalTimeInSeconds % 60;
					$totalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

					$booking->total_time=$totalTime;

					$total_kms=explode(" ", str_replace(",", "", $dataFetch['routes'][0]['legs'][0]['distance']['text']))[0];

					$booking->total_kms = (string)$total_kms;

				} else {
					$totalTime = "00:00:00";
					$booking->total_time=$totalTime;

					$total_kms="0";
				}
				
				$booking->save();

				if(isset($request->ride_type) && $request->ride_type  == "return_way")
				{
					
					$ids = Bookings::create(['customer_id' => $request->customer_id,
					'pickup_addr' => $request->dest_addr,
					'dest_addr' => $request->pickup_addr,
					'note' => $request->get('note'),
					'pickup' => $request->return_pickup_date_time,
					'dropoff'=>$request->return_dropoff_date_time,
					'vehicle_id'=>$request->vehicle_id,
					'user_id' => Auth::user()->id
					])->id;

					$return_date_time = Carbon::parse($request->return_pickup_date_time);

					$bookings = Bookings::find($ids);
					$bookings->driver_id = $request->get('driver_id');
					$bookings->journey_date = date('d-m-Y', strtotime($return_date_time));
					$bookings->journey_time =date('H:i:s', strtotime($return_date_time));
					$bookings->ride_type = "oneway";
					$bookings->accept_status = 0; //0=yet to accept, 1= accept
					$bookings->ride_status = "Pending";
	
					$bookings->ride_type=$request->ride_type;

					$bookings->parent_booking_id=$booking->id;

					$key2 = (Hyvikk::api('api_key') ?? '-');
		
					$pickupAddress2 = urlencode($request->dest_addr);
					$dropoffAddress2 = urlencode($request->pickup_addr);
					
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
	
						$bookings->total_kms = explode(" ", str_replace(",", "", $dataFetch['routes'][0]['legs'][0]['distance']['text']))[0];

						$bookings->total_time=$totalTime2;


	
					} else {
						$totalTime2 = "00:00:00";
						$bookings->total_time=$totalTime2;
						$bookings->total_kms=0;
					}

					$bookings->save();
				}


				$mail = Bookings::find($id);
				$this->booking_notification($booking->id);
				// send sms to customer while adding new booking
				$this->sms_notification($booking->id);
				// browser notification
				$this->push_notification($booking->id);
				if (Hyvikk::email_msg('email') == 1) {
					Mail::to($mail->customer->email)->send(new VehicleBooked($booking));
					Mail::to($mail->driver->email)->send(new DriverBooked($booking));
				}
				return redirect()->route("bookings.index");
			}
		} else {
			return redirect()->route("bookings.create")->withErrors(["error" => "Selected Vehicle is not Available in Given Timeframe"])->withInput();
		}
	}

	public function sms_notification($booking_id) {
	$booking = Bookings::find($booking_id);

	if (!$booking) {
		return; // Optionally: return response or log error
	}

	$customer = $booking->customer;
	$driver = $booking->driver;

	if (!$customer || !$driver) {
		return; // One of the relations is missing
	}

	$id = Hyvikk::twilio('sid');
	$token = Hyvikk::twilio('token');
	$from = Hyvikk::twilio('from');

	$to = $customer->mobno; // twilio trial verified number
	$driver_no = $driver->phone_code . $driver->phone;

	$customer_name = $customer->name;
	$customer_contact = $customer->mobno;

	$driver_name = $driver->name;
	$driver_contact = $driver->phone;

	$pickup_address = $booking->pickup_addr;
	$destination_address = $booking->dest_addr;

	$pickup_datetime = date(Hyvikk::get('date_format') . " H:i", strtotime($booking->pickup));
	$dropoff_datetime = date(Hyvikk::get('date_format') . " H:i", strtotime($booking->dropoff));

	$passengers = $booking->travellers;

	$search = ['$customer_name', '$customer_contact', '$pickup_address', '$pickup_datetime', '$passengers', '$destination_address', '$dropoff_datetime', '$driver_name', '$driver_contact'];
	$replace = [$customer_name, $customer_contact, $pickup_address, $pickup_datetime, $passengers, $destination_address, $dropoff_datetime, $driver_name, $driver_contact];

	$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";

	// Customer SMS
	$body = str_replace($search, $replace, Hyvikk::twilio("customer_message"));
	$new_body = explode("\n", wordwrap($body, 120));
	foreach ($new_body as $row) {
		$data = ['From' => $from, 'To' => $to, 'Body' => $row];
		$post = http_build_query($data);
		$x = curl_init($url);
		curl_setopt_array($x, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_USERPWD => "$id:$token",
			CURLOPT_POSTFIELDS => $post
		]);
		$y = curl_exec($x);
		curl_close($x);
	}

	// Driver SMS
	$driver_body = str_replace($search, $replace, Hyvikk::twilio("driver_message"));
	$msg_body = explode("\n", wordwrap($driver_body, 120));
	foreach ($msg_body as $row) {
		$data = ['From' => $from, 'To' => $driver_no, 'Body' => $row];
		$post = http_build_query($data);
		$x = curl_init($url);
		curl_setopt_array($x, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_USERPWD => "$id:$token",
			CURLOPT_POSTFIELDS => $post
		]);
		$y = curl_exec($x);
		curl_close($x);
	}
}

	public function push_notification($id) {
		$booking = Bookings::find($id);
		$auth = array(
			'VAPID' => array(
				'subject' => 'Alert about new post',
				'publicKey' => 'BKt+swntut+5W32Psaggm4PVQanqOxsD5PRRt93p+/0c+7AzbWl87hFF184AXo/KlZMazD5eNb1oQVNbK1ti46Y=',
				'privateKey' => 'NaMmQJIvddPfwT1rkIMTlgydF+smNzNXIouzRMzc29c=', // in the real world, this would be in a secret file
			),
		);
		$select1 = DB::table('push_notification')->select('*')->whereIn('user_id', [$booking->user_id])->get()->toArray();
		$webPush = new WebPush($auth);
		foreach ($select1 as $fetch) {
			$sub = Subscription::create([
				'endpoint' => $fetch->endpoint, // Firefox 43+,
				'publicKey' => $fetch->publickey, // base 64 encoded, should be 88 chars
				'authToken' => $fetch->authtoken, // base 64 encoded, should be 24 chars
				'contentEncoding' => $fetch->contentencoding,
			]);
			$user = User::find($fetch->user_id);
			$title = __('fleet.new_booking');
			$body = __('fleet.customer') . ": " . $booking->customer->name . ", " . __('fleet.pickup') . ": " . date(Hyvikk::get('date_format') . ' g:i A', strtotime($booking->pickup)) . ", " . __('fleet.pickup_addr') . ": " . $booking->pickup_addr . ", " . __('fleet.dropoff_addr') . ": " . $booking->dest_addr;
			$url = url('admin/bookings');
			$array = array(
				'title' => $title ?? "",
				'body' => $body ?? "",
				'img' => url('assets/images/' . Hyvikk::get('icon_img')),
				'url' => $url ?? url('admin/'),
			);
			$object = json_encode($array);
			if ($fetch->user_id == $user->id) {
				$test = $webPush->sendOneNotification($sub, $object);
			}
			foreach ($webPush->flush() as $report) {
				$endpoint = $report->getRequest()->getUri()->__toString();
			}
		}
	}
	
    public function update(BookingRequest $request) {
        $booking = Bookings::whereId($request->get("id"))->first();
    
        if (!$booking) {
            return redirect()->back()->withErrors(["error" => "Booking not found."]);
        }
    
        $booking->vehicle_id = $request->get("vehicle_id");
        $booking->user_id = $request->get("user_id");
        $booking->driver_id = $request->get('driver_id');
        $booking->travellers = $request->get("travellers");
        $booking->tax_total = $request->get("tax_total");
        $booking->pickup = $request->get("pickup");
        $booking->dropoff = $request->get("dropoff");
        $booking->pickup_addr = $request->get("pickup_addr");
        $booking->dest_addr = $request->get("dest_addr");
        $booking->note = $request->get('note');
    
        if ($booking->ride_status == null || $booking->ride_status == "Pending") {
            $booking->ride_status = "Pending";
        }
    
        $pickup = Carbon::parse($request->get("pickup"));
        $dropoff = Carbon::parse($request->get("dropoff"));
        $booking->duration = $pickup->diffInMinutes($dropoff);
        $booking->journey_date = $pickup->format('d-m-Y');
        $booking->journey_time = $pickup->format('H:i:s');
        $booking->udf = serialize($request->get('udf'));
    
        $key = Hyvikk::api('api_key') ?? '-';
        $pickupAddress = urlencode($request->get('pickup_addr'));
        $dropoffAddress = urlencode($request->get('dest_addr'));
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$pickupAddress}&destination={$dropoffAddress}&key={$key}";
    
        $response = Http::get($url);
        $dataFetch = $response->json();

    
        if ($dataFetch['status'] === 'OK') {
            $duration = $dataFetch['routes'][0]['legs'][0]['duration']['value'];
            $booking->total_time = gmdate('H:i:s', $duration);
            $distance = $dataFetch['routes'][0]['legs'][0]['distance']['text'];
            $booking->total_kms = (string) explode(" ", str_replace(",", "", $distance))[0];
        } else {
            $booking->total_time = "00:00:00";
            $booking->total_kms = "0";
        }
    
        $booking->save();
    
        // Handle return booking update
        if ($request->ride_type === "return_way") {
            $returnBooking = Bookings::find($request->get("return_booking_id"));
    
            if (!$returnBooking) {
                return redirect()->back()->withErrors(["error" => "Return booking not found."]);
            }
    
            $maxSeats = VehicleModel::find($request->get('return_vehicle_id'))->types->seats ?? 0;
    
            if ($request->get("return_travellers") > $maxSeats) {
                return redirect()->route("bookings.edit", $request->get('id'))
                    ->withErrors(["error" => "Travellers exceed vehicle seat capacity. Seats Available: {$maxSeats}"])
                    ->withInput();
            }
    
            $returnBooking->vehicle_id = $request->get('return_vehicle_id');
            $returnBooking->driver_id = $request->get("return_driver_id");
            $returnBooking->travellers = $request->get("return_travellers");
            $returnBooking->pickup = $request->get("return_pickup_date_time");
            $returnBooking->dropoff = $request->get("return_dropoff_date_time");
            $returnBooking->pickup_addr = $request->get("return_pickup_addr");
            $returnBooking->dest_addr = $request->get("return_dest_addr");
            $returnBooking->note = $request->get('return_note');
    
            if ($returnBooking->ride_status == null || $returnBooking->ride_status == "Pending") {
                $returnBooking->ride_status = "Pending";
            }
    
            $pickup1 = Carbon::parse($request->get("return_pickup_date_time"));
            $dropoff1 = Carbon::parse($request->get("return_dropoff_date_time"));
            $returnBooking->duration = $pickup1->diffInMinutes($dropoff1);
            $returnBooking->journey_date = $pickup1->format('d-m-Y');
            $returnBooking->journey_time = $pickup1->format('H:i:s');
    
            $pickupAddress1 = urlencode($request->get('return_pickup_addr'));
            $dropoffAddress1 = urlencode($request->get('return_dest_addr'));
            $url1 = "https://maps.googleapis.com/maps/api/directions/json?origin={$pickupAddress1}&destination={$dropoffAddress1}&key={$key}";
    
            $response1 = file_get_contents($url1);
            $dataFetch1 = json_decode($response1, true);
    
            if ($dataFetch1['status'] === 'OK') {
                $duration1 = $dataFetch1['routes'][0]['legs'][0]['duration']['value'];
                $returnBooking->total_time = gmdate('H:i:s', $duration1);
                $distance1 = $dataFetch1['routes'][0]['legs'][0]['distance']['text'];
                $returnBooking->total_kms = (string) explode(" ", str_replace(",", "", $distance1))[0];
            } else {
                $returnBooking->total_time = "00:00:00";
                $returnBooking->total_kms = "0";
            }
    
            $returnBooking->save();
        }
    
        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

	public function prev_address(Request $request) {
		$booking = Bookings::where('customer_id', $request->get('id'))->orderBy('id', 'desc')->first();
		if ($booking != null) {
			$r = array('pickup_addr' => $booking->pickup_addr, 'dest_addr' => $booking->dest_addr);
		} else {
			$r = array('pickup_addr' => "", 'dest_addr' => "");
		}
		return $r;
	}
	public function print_bookings() {
		if (Auth::user()->user_type == "C") {
			$data['data'] = Bookings::where('customer_id', Auth::user()->id)->orderBy('id', 'desc')->get();
		} else {
			$data['data'] = Bookings::orderBy('id', 'desc')->get();
		}
		return view('bookings.print_bookings', $data);
	}
    public function booking_notification($id)
    {
        $booking = Bookings::find($id);
    
        if (!$booking) {
            return; // Or handle the error appropriately
        }
    
        $customer = $booking->customer;
        $driver = User::find($booking->driver_id);
    
        $data['success'] = 1;
        $data['key'] = "upcoming_ride_notification";
        $data['message'] = 'New Ride has been Assigned to you.';
        $data['title'] = "New Upcoming Ride for you !";
        $data['description'] = $booking->pickup_addr . " - " . $booking->dest_addr . " on " . date('d-m-Y', strtotime($booking->pickup));
        $data['timestamp'] = date('Y-m-d H:i:s');
    
        $user_details = [
            'user_id' => $booking->customer_id,
            'user_name' => $customer ? $customer->name : '',
            'mobno' => $customer ? $customer->getMeta('mobno') : '',
            'profile_pic' => $customer ? $customer->getMeta('profile_pic') : '',
        ];
    
        $data['data'] = [
            'rideinfo' => [
                'booking_id' => $booking->id,
                'source_address' => $booking->pickup_addr,
                'dest_address' => $booking->dest_addr,
                'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                'ridestart_timestamp' => null,
                'journey_date' => date('d-m-Y', strtotime($booking->pickup)),
                'journey_time' => date('H:i:s', strtotime($booking->pickup)),
                'ride_status' => "Pending"
            ],
            'user_details' => $user_details,
        ];
    
        if ($driver && $driver->getMeta('fcm_id') && $driver->getMeta('is_available') == 1) {
            $push = new PushNotification('fcm');
            $push->setMessage($data)
                ->setApiKey(env('server_key'))
                ->setDevicesToken([$driver->getMeta('fcm_id')])
                ->send();
        }
    }

	public function bulk_delete(Request $request) {
		Bookings::whereIn('id', $request->ids)->delete();
		IncomeModel::whereIn('income_id', $request->ids)->where('income_cat', 1)->delete();
		return back();
	}
	public function cancel_booking(Request $request) {
		// dd($request->all());
		$booking = Bookings::find($request->cancel_id);
		$booking->cancellation = 1;
		$booking->ride_status = "Cancelled";
		$booking->reason = $request->reason;
		$booking->save();
		// if booking->status != 1 then delete income record
		IncomeModel::where('income_id', $request->cancel_id)->where('income_cat', 1)->delete();
		if (Hyvikk::email_msg('email') == 1) {
			Mail::to($booking->customer->email)->send(new BookingCancelled($booking, $booking->customer->name));
			Mail::to($booking->driver->email)->send(new BookingCancelled($booking, $booking->driver->name));
		}
		return back()->with(['msg' => 'Booking cancelled successfully!']);
	}
}