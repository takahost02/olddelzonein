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
use App\Model\BookingMeta;
use App\Model\CompanyServicesModel;
use App\Model\Hyvikk;
use App\Model\MessageModel;
use App\Model\TeamModel;
use App\Model\ServiceModel;
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

class FrontendApiController extends Controller {
	public function __construct() {
		if (file_exists(storage_path('installed'))) {
			app()->setLocale(Hyvikk::frontend('language'));
		}
	}
	public function language() {
		$data['success'] = "1";
		$data['message'] = "Data fetched!";
		$l = explode('-', Hyvikk::get("language"));
		$data['data'] = array('language' => $l[1]);
		return $data;
	}
	public function user_booking_history($id) {
		$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
		$user = User::find($id);
		// $bookings = Bookings::where('user_id', $id)->latest()->get();
		if ($user->group_id == null || $user->user_type == "S") {
			$bookings = Bookings::orderBy('id', 'desc')->get();
		} else {
			$vehicle_ids = VehicleModel::where('group_id', $user->group_id)->pluck('id')->toArray();
			$bookings = Bookings::whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'desc')->get();
		}
		$data = array();
		foreach ($bookings as $booking) {
			$type = null;
			if ($booking->vehicle_id) {
				$type = $booking->vehicle->types->displayname;
			} elseif ($booking->vehicle_typeid) {
				$v_type = VehicleTypeModel::find($booking->vehicle_typeid);
				$type = $v_type->displayname;
			}
			$data[] = array(
				'journey_date' => date($date_format_setting, strtotime($booking->journey_date)),
				'journey_time' => $booking->journey_time,
				'pickup_addr' => $booking->pickup_addr,
				'dest_addr' => $booking->dest_addr,
				'no_of_persons' => "$booking->travellers",
				'vehicle_type' => $type,
				'ride_status' => $booking->ride_status,
				'distance' => "$booking->total_kms",
				'amount' => "$booking->tax_total",
				'time' => $booking->driving_time,
				'created_date' => date($date_format_setting, strtotime($booking->created_at)),
				'created_time' => date('H:i:s', strtotime($booking->created_at)),
			);
		}
		return response()->json($data);
	}
    public function reject(Request $request) {
        $booking = Bookings::find($request->booking_id);
    
        if (!$booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }
    
        if ($request->action === 'approve') {
            $booking->ride_status = 'Upcoming';
            $booking->save();
            return response()->json(['message' => 'Booking approved successfully.']);
        }
    
        if ($request->action === 'reject') {
            $booking->ride_status = 'Rejected';
            $booking->tax_total = $request->tax_total;
            $booking->save();
            return response()->json(['message' => 'Booking rejected successfully.']);
        }
    
        return response()->json(['message' => 'Invalid action.'], 400);
    }

	public function redirect_payment(Request $request) {
		$validation = Validator::make($request->all(), [
			'booking_id' => 'required',
			'method' => 'required',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
			return $data;
		} else {
			$booking = Bookings::find($request->booking_id);
			if ($booking->receipt) {
				if ($request->method == "cash") {
					return url('cash/' . $request->booking_id);
				}
				if ($request->method == "stripe") {
					return url('stripe/' . $request->booking_id);
				}
				if ($request->method == "razorpay") {
					return url('razorpay/' . $request->booking_id);
				}
			} else {
				$data['success'] = "0";
				$data['message'] = "Booking receipt not generated, try after generation of booking receipt.";
				$data['data'] = "";
				return $data;
			}
		}
	}
	public function methods() {
		return json_decode(Hyvikk::payment('method'));
	}
	public function reset_password(Request $request) {
		$validation = Validator::make($request->all(), [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:6',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			$response = $this->broker()->reset(
				$this->credentials($request), function ($user, $password) {
					$this->resetPassword($user, $password);
				}
			);
			if ($response == Password::PASSWORD_RESET) {
				$data['success'] = "1";
				$data['message'] = __($response);
				$data['data'] = "";
			} else {
				$data['success'] = "0";
				$data['message'] = __($response);
				$data['data'] = "";
			}
		}
		return $data;
	}
	protected function resetPassword($user, $password) {
		$user->password = Hash::make($password);
		$user->setRememberToken(Str::random(60));
		$user->save();
	}
	protected function credentials(Request $request) {
		return $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);
	}
	public function forgot_password(Request $request) {
		// $url = str_replace('forget-password', '', $_SERVER['HTTP_REFERER']);
		$url = str_replace('forget-password', '', $request->getSchemeAndHttpHost());
		if (!env('front_url')) {
			file_put_contents(base_path('.env'), "front_url=" . $url . PHP_EOL, FILE_APPEND);
		}
		$this->validateEmail($request);
		$response = $this->broker()->sendResetLink(
			$request->only('email')
		);
		if ($response == Password::RESET_LINK_SENT) {
			$data['success'] = "1";
			$data['message'] = __($response);
			$data['data'] = "";
		} else {
			$data['success'] = "0";
			$data['message'] = __($response);
			$data['data'] = "";
		}
		return $data;
	}
	protected function validateEmail(Request $request) {
		$this->validate($request, ['email' => 'required|email']);
	}
	public function broker() {
		return Password::broker();
	}
	public function company_info() {
		$date_setting = "DD-MM-YYYY";
		if (Hyvikk::get('date_format') == 'Y-m-d') {
			$date_setting = "YYYY-MM-DD";
		}
		if (Hyvikk::get('date_format') == 'm-d-Y') {
			$date_setting = "MM-DD-YYYY";
		}
		$data['company_logo'] = url('assets/images/' . Hyvikk::get('logo_img'));
		$data['contact_email'] = Hyvikk::frontend('contact_email');
		$data['company_address'] = Hyvikk::get('badd1') . ", " . Hyvikk::get('badd2') . ", " . Hyvikk::get('city') . ", " . Hyvikk::get('state') . ", " . Hyvikk::get('country') . ".";
		$data['company_phone'] = Hyvikk::frontend('contact_phone');
		$data['customer_support'] = Hyvikk::frontend('customer_support');
		$data['about_breif'] = Hyvikk::frontend('about_us');
		$data['faq_link'] = Hyvikk::frontend('faq_link');
		$data['driver_login_url'] = url('admin/login');
		$data['gmap_api_key'] = Hyvikk::api('api_key');
		$data['facebook'] = Hyvikk::frontend('facebook');
		$data['twitter'] = Hyvikk::frontend('twitter');
		$data['instagram'] = Hyvikk::frontend('instagram');
		$data['linkedin'] = Hyvikk::frontend('linkedin');
		$data['cancellation'] = Hyvikk::frontend('cancellation');
		$data['terms'] = Hyvikk::frontend('terms');
		$data['privacy_policy'] = Hyvikk::frontend('privacy_policy');
		$data['date_format'] = $date_setting;
		return response()->json($data);
	}
	public function vehicle_types() {
		$vehicle_types = VehicleTypeModel::select('id', 'vehicletype', 'displayname', 'icon', 'seats')->where('isenable', 1)->get();
		$vehicle_type_data = array();
		foreach ($vehicle_types as $vehicle_type) {
			if ($vehicle_type->icon != null) {
				$url = $vehicle_type->icon;
			} else {
				$url = null;
			}
			$vehicle_type_data[] = array('id' => "$vehicle_type->id",
				'vehicle_type' => $vehicle_type->displayname,
			);
		}
		return response()->json($vehicle_type_data);
	}
	public function our_services() {
		$services = CompanyServicesModel::get();
		$data = array();
		foreach ($services as $service) {
			if ($service->image != null) {
				$image = url('uploads/' . $service->image);
			} else {
				$image = null;
			}
			$data[] = array('id' => "$service->id", 'title' => $service->title, 'description' => $service->description, 'image' => $image);
		}
		return response()->json($data);
	}
	public function about_fleet() {
		$data['description'] = Hyvikk::frontend('about_description');
		$data['title'] = Hyvikk::frontend('about_title');
		$data['cities'] = Hyvikk::frontend('cities');
		$data['vehicles'] = Hyvikk::frontend('vehicles');
		$team = TeamModel::get();
		$records = array();
		foreach ($team as $test) {
			if ($test->image != null) {
				$image = url('uploads/' . $test->image);
			} else {
				$image = url('assets/images/no-user.jpg');
			}
			$records[] = array('id' => "$test->id", 'name' => $test->name, 'designation' => $test->designation, 'description' => $test->details, 'image' => $image);
		}
		$data['team'] = $records;
		return response()->json($data);
	}
		public function service_fleet() {
		$data['description'] = Hyvikk::frontend('service_description');
		$data['title'] = Hyvikk::frontend('service_title');
		$data['cities'] = Hyvikk::frontend('cities');
		$data['vehicles'] = Hyvikk::frontend('vehicles');
		$service = ServiceModel::get();
		$records = array();
		foreach ($service as $test) {
			if ($test->image != null) {
				$image = url('uploads/' . $test->image);
			} else {
				$image = url('assets/images/no-user.jpg');
			}
			$records[] = array('id' => "$test->id", 'name' => $test->name, 'designation' => $test->designation, 'description' => $test->details, 'image' => $image);
		}
		$data['service'] = $records;
		return response()->json($data);
	}
	public function testimonials() {
		$testimonials = Testimonial::get();
		$data = array();
		foreach ($testimonials as $test) {
			if ($test->image != null) {
				$image = url('uploads/' . $test->image);
			} else {
				$image = url('assets/images/no-user.jpg');
			}
			$data[] = array('id' => "$test->id", 'name' => $test->name, 'description' => $test->details, 'image' => $image);
		}
		return response()->json($data);
	}
	public function footer_data() {
		$data['about_us'] = Hyvikk::frontend('about_us');
		$data['contact_email'] = Hyvikk::frontend('contact_email');
		$data['contact_phone'] = Hyvikk::frontend('contact_phone');
		$data['address'] = Hyvikk::get('badd1') . ", " . Hyvikk::get('badd2') . ", " . Hyvikk::get('city') . ", " . Hyvikk::get('state') . ", " . Hyvikk::get('country') . ".";
		$data['icon'] = url('assets/images/' . Hyvikk::get('icon_img'));
		$data['logo'] = url('assets/images/' . Hyvikk::get('logo_img'));
		$data['facebook'] = Hyvikk::frontend('facebook');
		$data['twitter'] = Hyvikk::frontend('twitter');
		$data['instagram'] = Hyvikk::frontend('instagram');
		$data['linkedin'] = Hyvikk::frontend('linkedin');
		$data['cancellation'] = Hyvikk::frontend('cancellation');
		$data['terms'] = Hyvikk::frontend('terms');
		$data['privacy_policy'] = Hyvikk::frontend('privacy_policy');
		return response()->json($data);
	}
	public function vehicles() {
		$vehicles = VehicleModel::where('type_id', '!=', null)->get();
		$data = array();
		foreach ($vehicles as $v) {
			$url = asset("assets/images/vehicle.jpeg");
			if ($v->vehicle_image) {
				$url = url('uploads/' . $v->vehicle_image);
			}
			$data[] = array(
				'id' => "$v->id",
				'make' => $v->make,
				'model' => $v->model,
				'year' => $v->year,
				'lic_plate' => $v->license_plate,
				'vehicle_type' => $v->types->displayname,
				'vehicle_image' => $url,
				'average' => $v->average,
				'color' => $v->color,
				'no_of_persons' => $v->types->seats,
				'engine_type' => $v->engine_type,
			);
		}
		return response()->json($data);
	}
	public function booking_history($id) {
    	$date_format_setting = Hyvikk::get('date_format') ?? 'd-m-Y';
    	// Only 'oneway' rides for this customer
    	$bookings = Bookings::where('customer_id', $id)
    		->where('ride_type', 'oneway')
    		->latest()
    		->get();
    
    	$data = [];
    	foreach ($bookings as $booking) {
        $type = null;
        if ($booking->vehicle_id && $booking->vehicle && $booking->vehicle->types) {
            $type = $booking->vehicle->types->displayname;
        } elseif ($booking->vehicle_typeid) {
            $v_type = VehicleTypeModel::find($booking->vehicle_typeid);
            $type = $v_type ? $v_type->displayname : null;
        }
    
        $hasReturn = Bookings::where('parent_booking_id', $booking->id)->exists();
        $icon_type = $hasReturn ? 'return_way' : 'one_way';
    
        $data[] = [
            'journey_date' => date($date_format_setting, strtotime($booking->journey_date)),
            'journey_time' => $booking->journey_time,
            'pickup_addr' => $booking->pickup_addr,
            'dest_addr' => $booking->dest_addr,
            'no_of_persons' => "$booking->travellers",
            'vehicle_type' => $type,
            'ride_status' => $booking->ride_status,
            'distance' => "$booking->total_kms",
            'amount' => "$booking->tax_total",
            'time' => $booking->driving_time,
            'created_date' => date('Y-m-d', strtotime($booking->created_at)),
            'created_date_formatted' => date($date_format_setting, strtotime($booking->created_at)),
            'created_time' => date('H:i:s', strtotime($booking->created_at)),
            'receipt' => $booking->receipt ?? 0,
            'id' => $booking->id,
            'paid' => $booking->payment ?? 0,
            'ride_icon' => $icon_type,
        ];
    }
    	return response()->json($data);
    }


	public function book_now(Request $request)
    {
        // Validation
        $validation = Validator::make($request->all(), [
        'source_address'         => 'required|string',
        'dest_address'           => 'required|string',
        'no_of_persons'          => 'required|integer',
        'user_id'                => 'required|integer',
        'vehicle_typeid'         => 'required|integer',
        'ride_type'              => 'required|in:oneway,return_way',
        'booking_schedule_type'  => 'required|in:0,1',
        'material_typeid'        => 'required|integer|exists:material_types,id',
        'return_pickup_time'     => 'required_if:ride_type,return_way|nullable|date_format:H:i',
        'dropoff_address'        => 'nullable|array',
    ]);

    
        if ($validation->fails()) {
            $failedFields = implode(', ', array_keys($validation->errors()->toArray()));
            return response()->json([
                'success' => "0",
                'message' => "Validation failed for: {$failedFields}",
                'errors'  => $validation->errors(),
                'data'    => ""
            ]);
        }
    
        if ((int)$request->booking_schedule_type !== 0) {
            return response()->json([
                'success' => "0",
                'message' => "Invalid booking schedule type for immediate booking.",
                'data'    => ""
            ]);
        }
    
        try {
            $pickupDateTime = now()->addHours(Hyvikk::frontend('booking_time'));
            $currentDate    = now()->format('Y-m-d');
            $currentTime    = now()->format('H:i:s');
    
            // STEP 1: Create main booking
            $booking = Bookings::create([
                'customer_id'     => $request->user_id,
                'pickup_addr'     => $request->source_address,
                'dest_addr'       => $request->dest_address,
                'travellers'      => $request->no_of_persons,
                'note'            => $request->note,
                'pickup'          => $pickupDateTime,
                'user_id'         => auth()->id(),
                'journey_date'    => $currentDate,
                'journey_time'    => $currentTime,
                'accept_status'   => 0,
                'ride_status'     => 'Pending',
                'material_type'    => $request->material_typeid,
                'booking_type'    => 'immediate',
                'booking_schedule'  => (int) $request->booking_schedule,
                'vehicle_typeid'  => $request->vehicle_typeid,
                'ride_type'       => 'oneway',
                
            ]);
    
            // STEP 2: Save frequent addresses
            Address::updateOrCreate(['customer_id' => $request->user_id, 'address' => $request->source_address]);
            Address::updateOrCreate(['customer_id' => $request->user_id, 'address' => $request->dest_address]);
    
            // STEP 3: Handle return booking
            if ($request->ride_type === 'return_way') {
                try {
                    $returnPickupDateTime = now()->addHours(Hyvikk::frontend('booking_time') + 4); // Or any logic to delay return ride
                    $returnPickupTime = $request->return_pickup_time ?? $returnPickupDateTime->format('H:i:s');
    
                    $returnBooking = Bookings::create([
                        'customer_id'       => $request->user_id,
                        'pickup_addr'       => $request->dest_address,
                        'dest_addr'         => $request->source_address,
                        'travellers'        => $request->no_of_persons,
                        'note'              => $request->note,
                        'pickup'            => $returnPickupDateTime,
                        'user_id'           => auth()->id(),
                        'journey_date'      => $returnPickupDateTime->format('Y-m-d'),
                        'journey_time'      => $returnPickupTime,
                        'accept_status'     => 0,
                        'ride_status'       => 'Pending',
                        'booking_type'      => 'immediate',
                        'booking_schedule'  => (int) $request->booking_schedule,
                        'material_type'    => $request->material_typeid,
                        'vehicle_typeid'    => $request->vehicle_typeid,
                        'ride_type'         => 'return_way',
                        'parent_booking_id' => $booking->id,
                        'dropoff_address'   => is_array($request->dropoff_address) 
                            ? implode(', ', $request->dropoff_address) 
                            : $request->dropoff_address,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => "0",
                        'message' => "Main booking created, but return booking failed: " . $e->getMessage(),
                        'data'    => ['booking_id' => (string)$booking->id]
                    ]);
                }
            }
    
            // STEP 4: Notify
            $this->book_now_notification($booking->id, $request->vehicle_typeid);
    
            return response()->json([
                'success' => "1",
                'message' => "Your Request has been Submitted Successfully.",
                'data'    => ['booking_id' => (string)$booking->id]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => "Server error: " . $e->getMessage(),
                'data'    => ""
            ]);
        }
    }



	public function user_login(Request $request) {
		$email = $request->username;
		$password = $request->password;
		if (Login::attempt(['email' => $email, 'password' => $password, 'user_type' => 'C'])) {
			$user = Login::user();
			$user->login_status = 1;
			$user->save();
			$data['success'] = "1";
			$data['message'] = "You have Signed in Successfully!";
			if ($user->profile_pic == null) {
				$src = url('assets/images/user-noimage.png');
			} elseif (starts_with($user->profile_pic, 'http')) {
				$src = $user->profile_pic;
			} else {
				$src = url('uploads/' . $user->profile_pic);
			}
			$data['userinfo'] = array("user_id" => "$user->id",
				"api_token" => $user->api_token,
				"user_name" => $user->name,
				"user_type" => $user->user_type,
				"mobno" => $user->mobno,
				"emailid" => $user->email,
				"gender" => $user->gender,
				"password" => $user->password,
				"profile_pic" => $src,
				"status" => "$user->login_status",
				"timestamp" => date('Y-m-d H:i:s', strtotime($user->created_at)));
		} else {
			$data['success'] = "0";
			$data['message'] = "Invalid Login Credentials";
			$data['userinfo'] = "";
		}
		return response()->json($data);
	}
	
    public function book_later(Request $request)
    {
        // Convert journey_date
        if ($request->has('journey_date')) {
            try {
                $request->merge([
                    'journey_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->journey_date)->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => "0",
                    'message' => "Invalid journey date format. Expected dd-mm-yyyy.",
                    'data'    => ""
                ]);
            }
        }
    
        // Convert return_pickup_date
        if ($request->ride_type === 'return_way' && $request->filled('return_pickup_date')) {
            try {
                $request->merge([
                    'return_pickup_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->return_pickup_date)->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => "0",
                    'message' => "Invalid return pickup date format. Expected dd-mm-yyyy.",
                    'data'    => ""
                ]);
            }
        }
    
        // Validation
        $validation = Validator::make($request->all(), [
        'source_address'         => 'required|string',
        'dest_address'           => 'required|string',
        'journey_date'           => 'required|date',
        'journey_time'           => 'required|date_format:H:i',
        'no_of_persons'          => 'required|integer',
        'user_id'                => 'required|integer',
        'vehicle_typeid'         => 'required|integer',
        'ride_type'              => 'required|in:oneway,return_way',
        'booking_schedule_type'  => 'required|in:0,1',
        'material_typeid'        => 'required|integer|exists:material_types,id',
        'return_pickup_date'     => 'required_if:ride_type,return_way|nullable|date',
        'return_pickup_time'     => 'required_if:ride_type,return_way|nullable|date_format:H:i',
        'dropoff_address'        => 'nullable|array',
        ]);

    
        if ($validation->fails()) {
            $failedFields = implode(', ', array_keys($validation->errors()->toArray()));
            return response()->json([
                'success' => "0",
                'message' => "Validation failed for: {$failedFields}",
                'errors'  => $validation->errors(),
                'data'    => ""
            ]);
        }


    
        if ((int)$request->booking_schedule_type !== 1) {
            return response()->json([
                'success' => "0",
                'message' => "Invalid booking schedule type for later booking.",
                'data'    => ""
            ]);
        }
    
        try {
        // STEP 1: Create main booking with all fields
        $pickupDateTime = $request->journey_date . ' ' . date('H:i:s', strtotime($request->journey_time));
    
        $booking = \App\Model\Bookings::create([
            'customer_id'       => $request->user_id,
            'user_id'           => auth()->id(),
            'pickup_addr'       => $request->source_address,
            'dest_addr'         => $request->dest_address,
            'travellers'        => $request->no_of_persons,
            'note'              => $request->note,
            'pickup'            => $pickupDateTime,
            'journey_date'      => $request->journey_date,
            'journey_time'      => $request->journey_time,
            'booking_schedule'  => (int) $request->booking_schedule,
            'booking_type'      => 'later',
            'accept_status'     => 0,
            'ride_status'       => 'Pending',
            'vehicle_typeid'    => $request->vehicle_typeid,
            'material_type'    => $request->material_typeid,
            'ride_type'         => 'oneway',
        

        ]);

    
        // STEP 2: Save frequent addresses
        \App\Model\Address::updateOrCreate(['customer_id' => $request->user_id, 'address' => $request->source_address]);
        \App\Model\Address::updateOrCreate(['customer_id' => $request->user_id, 'address' => $request->dest_address]);
    
        // STEP 3: Handle return booking if applicable
        if ($request->ride_type === 'return_way') {
            try {
                $returnPickupDateTime = $request->return_pickup_date . ' ' . date('H:i:s', strtotime($request->return_pickup_time));
    
                $returnBooking = \App\Model\Bookings::create([
                    'customer_id'       => $request->user_id,
                    'user_id'           => auth()->id(),
                    'pickup_addr'       => $request->dest_address,
                    'dest_addr'         => $request->source_address,
                    'travellers'        => $request->no_of_persons,
                    'note'              => $request->note,
                    'pickup'            => $returnPickupDateTime,
                    'journey_date'      => $request->return_pickup_date,
                    'journey_time'      => $request->return_pickup_time,
                    'booking_type'      => 'later',
                    'accept_status'     => 0,
                    'ride_status'       => 'Pending',
                    'material_type'     => $request->material_typeid,
                    'vehicle_typeid'    => $request->vehicle_typeid,
                    'booking_schedule'  => (int) $request->booking_schedule,
                    'ride_type'         => 'return_way',
                    'parent_booking_id' => $booking->id,
                    'dropoff_address'   => is_array($request->dropoff_address) 
                            ? implode(', ', $request->dropoff_address) 
                            : $request->dropoff_address,

                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => "0",
                    'message' => "Main booking created, but return booking failed: " . $e->getMessage(),
                    'data'    => ['booking_id' => (string)$booking->id]
                ]);
            }
        }
    
        // STEP 4: Notify
        $this->book_later_notification($booking->id, $request->vehicle_typeid);
    
        return response()->json([
            'success' => "1",
            'message' => "Your Request has been Submitted Successfully.",
            'data'    => ['booking_id' => (string)$booking->id]
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => "Server error: " . $e->getMessage(),
                'data'    => ""
            ]);
        }
    
    }




	public function message_us(Request $request) {
		$validation = Validator::make($request->all(), [
			'message' => 'required',
			'name' => 'required',
			'email' => 'required',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = "Oops, Something got Wrong. Please, Try again Later!";
			$data['data'] = "";
		} else {
			MessageModel::create(['message' => $request->message, 'name' => $request->name, 'email' => $request->email]);
			$data['success'] = "1";
			$data['message'] = "Thank you ! We will get back to you Soon...";
			$data['data'] = "";
		}
		return response()->json($data);
	}
	public function user_register(Request $request) {
		$messages = [
			'emailid.unique' => 'User already exists.',
		];
		$validation = Validator::make($request->all(), [
			'mobno' => 'required',
			'gender' => 'required',
			'password' => 'required|same:confirm_password',
			'emailid' => 'required|unique:users,email',
			'first_name' => 'required',
			'last_name' => 'required',
		], $messages);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['userinfo'] = "";
		} else {
			$id = User::create(['name' => $request->first_name . " " . $request->last_name, 'email' => $request->emailid, 'password' => bcrypt($request->password), 'user_type' => 'C', 'api_token' => str_random(60)])->id;
			$user = User::find($id);
			$user->login_status = 1;
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->mobno = $request->mobno;
			$user->gender = $request->gender;
			$user->address = $request->address;
			$user->wpenable = $request->wpenable;
			$user->save();
			$data['success'] = "1";
			$data['message'] = "You have Registered Successfully!";
			$data['userinfo'] = array('user_id' => "$user->id", 'api_token' => $user->api_token, 'user_name' => $user->name, 'mobno' => $user->mobno, 'emailid' => $user->email, 'gender' => "$user->gender", 'password' => $user->password, 'status' => "$user->login_status", 'timestamp' => date('Y-m-d H:i:s', strtotime($user->created_at)), 'address' => $user->address);
		}
		return response()->json($data);
	}
	// book now notification
	public function book_now_notification($id, $type_id) {
		$booking = Bookings::find($id);
		$data['success'] = 1;
		$data['key'] = "book_now_notification";
		$data['message'] = 'Data Received.';
		$data['title'] = "New Ride Request (Book Now)";
		$data['description'] = "Do you want to Accept it ?";
		$data['timestamp'] = date('Y-m-d H:i:s');
		$data['data'] = array('riderequest_info' => array(
			'user_id' => $booking->customer_id,
			'booking_id' => $booking->id,
			'source_address' => $booking->pickup_addr,
			'dest_address' => $booking->dest_addr,
			'book_date' => date('Y-m-d'),
			'book_time' => date('H:i:s'),
			'journey_date' => date('d-m-Y'),
			'journey_time' => date('H:i:s'),
			'accept_status' => $booking->accept_status));
		if ($type_id == null) {
			$vehicles = VehicleModel::get()->pluck('id')->toArray();
		} else {
			$vehicles = VehicleModel::where('type_id', $type_id)->get()->pluck('id')->toArray();
		}
		$drivers = User::where('user_type', 'D')->get();
		foreach ($drivers as $d) {
			if (in_array($d->vehicle_id, $vehicles)) {
				if ($d->fcm_id != null && $d->is_available == 1 && $d->is_on != 1) {
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
	// book later notification
	public function book_later_notification($id, $type_id) {
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
