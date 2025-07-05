<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\VownerRequest;
use App\Http\Requests\ImportRequest;
use App\Imports\VownerImport;
use App\Model\Bookings;
use App\Model\VownerLogsModel;
use App\Model\VownerVehicleModel;
use App\Model\ExpCats;
use App\Model\Expense;
use App\Model\Hyvikk;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\ServiceItemsModel;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\VehicleTypeModel;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

use Illuminate\Support\Facades\Validator;

use App\Traits\FirebasePassword;


use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Session;



class VownerController extends Controller {

	use FirebasePassword;

	public function verify_vowner($id)

	{

		$driver=User::find($id);

		$vehicle=VehicleModel::find($driver->getMeta('vehicle_id'));



		return view("vowner.verifydriver",compact('driver','vehicle'));

	}



	public function update_verify_vowner(Request $request)

	{

		$validator = Validator::make($request->all(), [

            'status' => 'required',

		]);

		if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();

        }



		$driver=User::find($request->d_id);

		$driver->is_verified=$request->status;

		$driver->save();



		return redirect('/admin/vowner');



	}




	public function __construct() {
// 		$this->middleware('permission:Vowner add', ['only' => ['create']]);
// 		$this->middleware('permission:Vowner edit', ['only' => ['edit']]);
// 		$this->middleware('permission:Vowner delete', ['only' => ['bulk_delete', 'destroy']]);
// 		$this->middleware('permission:Vowner list');
// 		$this->middleware('permission:Vowner import', ['only' => ['importvowner']]);
// 		$this->middleware('permission:Vowner map', ['only' => ['vowner_maps']]);
		$this->phone_code = config("phone_codes.codes");
	}
	public function importVowner(ImportRequest $request) {
		try {
			// Check if the file is uploaded and valid
			if (!$request->hasFile('excel') || !$request->file('excel')->isValid()) {
				return back()->withErrors(['error' => 'The uploaded file is not valid.']);
			}
	
			$file = $request->file('excel');
			$destinationPath = './uploads/xml';
			$extension = $file->getClientOriginalExtension();
			$fileName = Str::uuid() . '.' . $extension;
	
			// Ensure the uploads directory exists and is writable
			if (!is_dir($destinationPath)) {
				mkdir($destinationPath, 0755, true); // Create directory if not exists
			}
			if (!is_writable($destinationPath)) {
				return back()->withErrors(['error' => 'The upload directory is not writable.']);
			}
	
			
			$file->move($destinationPath, $fileName);
	
			// Import Excel file
			Excel::import(new VownerImport, $destinationPath . '/' . $fileName);
	
			return back()->with('success', 'Vowner imported successfully.');
		} catch (ValidationException $e) {
			// Handle validation exceptions (if any validation rules fail in the import process)
			$failures = $e->failures();
			$errorMessages = [];
	
			foreach ($failures as $failure) {
				$errorMessages[] = "Row " . $failure->row() . ": " . implode(", ", $failure->errors());
			}
	
			return back()->withErrors($errorMessages);
		} catch (\Exception $e) {
			// Log the exact error to the Laravel log file for debugging
			\Log::error('Error importing vowner: ' . $e->getMessage());
			
			// Return a more informative error to the user
			return back()->withErrors(['error' => 'An error occurred while importing vowner. Please check the server logs for more details.']);
		}
	}
	
	public function index() {
		return view("vowner.index");
	}
	public function fetch_data(Request $request) {
		if ($request->ajax()) {
			$users = User::select('users.*')
				->leftJoin('users_meta', 'users_meta.user_id', '=', 'users.id')
				->leftJoin('driver_vehicle', 'driver_vehicle.driver_id', '=', 'users.id')
				->leftJoin('vehicles', 'driver_vehicle.vehicle_id', '=', 'vehicles.id')
				->with(['metas'])->whereUser_type("V")->groupBy('users.id');
			return DataTables::eloquent($users)
				->addColumn('check', function ($user) {
					return '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick=\'checkcheckbox();\'>';
				})
				->editColumn('name', function ($user) {
					return "<a href=" . route('vowner.show', $user->id) . ">$user->name</a>";
				})
				->addColumn('driver_image', function ($user) {
                	try {
                		// Replace 'vowner_image' with the actual key used for storing image filename in user meta
                		$imageMeta = $user->metas->where('key', 'vowner_image')->first();
                		$imageFile = optional($imageMeta)->value;
                
                		if ($imageFile && file_exists(public_path('uploads/' . $imageFile))) {
                			$src = asset('uploads/' . $imageFile);
                		} else {
                			$src = asset('assets/images/no-user.jpg');
                		}
                	} catch (\Exception $e) {
                		// Log the error and fallback
                		\Log::error("Error loading driver image for user ID {$user->id}: " . $e->getMessage());
                		$src = asset('assets/images/no-user.jpg');
                	}
                
                	return '<img src="' . $src . '" height="70px" width="70px">';
                })

				->addColumn('is_active', function ($user) {
					return ($user->is_active == 1) ? "YES" : "NO";
				})
				->addColumn('phone', function ($user) {
					return $user->phone_code . ' ' . $user->phone;
				})
				->addColumn('start_date', function ($user) {
					return $user->start_date;
				})
				->addColumn('action', function ($user) {
					return view('vowner.list-actions', ['row' => $user]);
				})
				->filterColumn('is_active', function ($query, $keyword) {
					$query->whereHas("metas", function ($q) use ($keyword) {
						$q->where('key', 'is_active');
						$q->whereRaw("IF(value = 1 , 'YES', 'NO') like ? ", ["%{$keyword}%"]);
					});
					return $query;
				})
				->filterColumn('phone', function ($query, $keyword) {
					$query->whereHas("metas", function ($q) use ($keyword) {
						$q->where(function ($q) use ($keyword) {
							$q->where('key', 'phone');
							$q->where("value", 'like', "%$keyword%");
						})->orWhere(function ($q) use ($keyword) {
							$q->where('key', 'phone_code');
							$q->where("value", 'like', "%$keyword%");
						});
					});
					return $query;
				})
				->filterColumn('start_date', function ($query, $keyword) {
					$query->whereHas("metas", function ($q) use ($keyword) {
						$q->where('key', 'start_date');
						$q->where("value", 'like', "%$keyword%");
					});
					return $query;
				})
				->rawColumns(['driver_image', 'action', 'check', 'name'])
				->make(true);
			//return datatables(User::all())->toJson();
		}
	}
	public function show($id) {
		$index['vowner'] = User::find($id);
		return view('vowner.show', $index);
	}
	public function fetch_bookings_data(Request $request) {
		if ($request->ajax()) {
			$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
			if (Auth::user()->user_type == "C") {
				$bookings = Bookings::where('customer_id', Auth::id())->latest();
			} elseif (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
				$bookings = Bookings::latest();
			} else {
				$vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
				$bookings = Bookings::whereIn('vehicle_id', $vehicle_ids)->latest();
			}
			$bookings->select('bookings.*')
				->leftJoin('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
				->leftJoin('bookings_meta', function ($join) {
					$join->on('bookings_meta.booking_id', '=', 'bookings.id')
						->where('bookings_meta.key', '=', 'vehicle_typeid');
				})
				->leftJoin('vehicle_types', 'bookings_meta.value', '=', 'vehicle_types.id')
				->when($request->vowner_id, function ($q, $driver_id) {
					$q->where('bookings.vowner_id', $driver_id);
				})
				->when($request->customer_id, function ($q, $customer_id) {
					$q->where('bookings.customer_id', $customer_id);
				})
				->with(['customer', 'metas']);
			return DataTables::eloquent($bookings)
				->addColumn('check', function ($user) {
					return '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick=\'checkcheckbox();\'>';
				})
				->addColumn('customer', function ($row) {
					return ($row->customer->name) ?? "";
				})
				->addColumn('ride_status', function ($row) {
					return ($row->getMeta('ride_status')) ?? "";
				})
				->editColumn('pickup_addr', function ($row) {
					return str_replace(",", "<br/>", $row->pickup_addr);
				})
				->editColumn('dest_addr', function ($row) {
					// dd($row->dest_addr);
					return str_replace(",", "<br/>", $row->dest_addr);
				})
				->editColumn('pickup', function ($row) use ($date_format_setting) {
					$pickup = '';
					$pickup = [
						'display' => '',
						'timestamp' => '',
					];
					if (!is_null($row->pickup)) {
						$pickup = date($date_format_setting . ' h:i A', strtotime($row->pickup));
						return [
							'display' => date($date_format_setting . ' h:i A', strtotime($row->pickup)),
							'timestamp' => Carbon::parse($row->pickup),
						];
					}
					return $pickup;
				})
				->editColumn('dropoff', function ($row) use ($date_format_setting) {
					$dropoff = [
						'display' => '',
						'timestamp' => '',
					];
					if (!is_null($row->dropoff)) {
						$dropoff = date($date_format_setting . ' h:i A', strtotime($row->dropoff));
						return [
							'display' => date($date_format_setting . ' h:i A', strtotime($row->dropoff)),
							'timestamp' => Carbon::parse($row->dropoff),
						];
					}
					return $dropoff;
				})
				->editColumn('payment', function ($row) {
					if ($row->payment == 1) {
						return '<span class="text-success"> ' . __('fleet.paid1') . ' </span>';
					} else {
						return '<span class="text-warning"> ' . __('fleet.pending') . ' </span>';
					}
				})
				->editColumn('tax_total', function ($row) {
					return ($row->tax_total) ? Hyvikk::get('currency') . " " . $row->tax_total : "";
				})
				->addColumn('vehicle', function ($row) {
					$vehicle_type = VehicleTypeModel::find($row->getMeta('vehicle_typeid'));
					return !empty($row->vehicle_id) ? $row->vehicle->make_name . '-' . $row->vehicle->model_name . '-' . $row->vehicle->license_plate : ($vehicle_type->displayname) ?? "";
				})
				->filterColumn('vehicle', function ($query, $keyword) {
					$query->whereRaw("CONCAT(vehicles.make_name , '-' , vehicles.model_name , '-' , vehicles.license_plate) like ?", ["%$keyword%"])
						->orWhereRaw("(vehicle_types.displayname like ? and bookings.vehicle_id IS NULL)", ["%$keyword%"]);
					return $query;
				})
				->filterColumn('ride_status', function ($query, $keyword) {
					$query->whereHas("metas", function ($q) use ($keyword) {
						$q->where('key', 'ride_status');
						$q->whereRaw("value like ?", ["%{$keyword}%"]);
					});
					return $query;
				})
				->filterColumn('tax_total', function ($query, $keyword) {
					$query->whereHas("metas", function ($q) use ($keyword) {
						$q->where('key', 'tax_total');
						$q->whereRaw("value like ?", ["%{$keyword}%"]);
					});
					return $query;
				})
				->addColumn('action', function ($user) {
					return view('bookings.list-actions', ['row' => $user]);
				})
				->filterColumn('payment', function ($query, $keyword) {
					$query->whereRaw("IF(payment = 1 , '" . __('fleet.paid1') . "', '" . __('fleet.pending') . "') like ? ", ["%{$keyword}%"]);
				})
				->filterColumn('pickup', function ($query, $keyword) {
					$query->whereRaw("DATE_FORMAT(pickup,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
				})
				->filterColumn('dropoff', function ($query, $keyword) {
					$query->whereRaw("DATE_FORMAT(dropoff,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
				})
				->rawColumns(['payment', 'action', 'check', 'pickup_addr', 'dest_addr'])
				->make(true);
			//return datatables(User::all())->toJson();
		}
	}
	public function destroy(Request $request) {

		$u=User::find($request->get('id'));

		$this->deleteUser($u->email);

		$driver = User::find($request->id);
		if ($driver->vehicles->count()) {
			$driver->vehicles()->detach($driver->vehicles->pluck('id')->toArray());
		}
		vownerVehicleModel::where('vowner_id', $request->id)->delete();
		if (file_exists('./uploads/' . $driver->driver_image) && !is_dir('./uploads/' . $driver->driver_image)) {
			unlink('./uploads/' . $driver->driver_image);
		}
		if (file_exists('./uploads/' . $driver->license_image) && !is_dir('./uploads/' . $driver->license_image)) {
			unlink('./uploads/' . $driver->license_image);
		}
		if (file_exists('./uploads/' . $driver->documents) && !is_dir('./uploads/' . $driver->documents)) {
			unlink('./uploads/' . $driver->documents);
		}
		User::find($request->get('id'))->user_data()->delete();
		//User::find($request->get('id'))->delete();
		$driver->update([
			'email' => time() . "_deleted" . $driver->email,
		]);
		$driver->delete();
		return redirect()->route('vowner.index');
	}
	public function bulk_delete(Request $request) {
		$driver = User::whereIn('id', $request->ids)->get();
		foreach ($driver as $driver) {

			$this->deleteUser($driver->email);


			if ($driver->vehicles->count()) {
				$driver->vehicles()->detach($driver->vehicles->pluck('id')->toArray());
			}
			$driver->update([
				'email' => time() . "_deleted" . $driver->email,
			]);
			if (file_exists('./uploads/' . $driver->driver_image) && !is_dir('./uploads/' . $driver->driver_image)) {
				unlink('./uploads/' . $driver->driver_image);
			}
			if (file_exists('./uploads/' . $driver->license_image) && !is_dir('./uploads/' . $driver->license_image)) {
				unlink('./uploads/' . $driver->license_image);
			}
			if (file_exists('./uploads/' . $driver->documents) && !is_dir('./uploads/' . $driver->documents)) {
				unlink('./uploads/' . $driver->documents);
			}
			$driver->delete();
		}
		vownerVehicleModel::whereIn('vowner_id', $request->ids)->delete();
		//User::whereIn('id', $request->ids)->delete();
		// return redirect('admin/customers');
		return back();
	}
        public function create() {
            // Fetch required data
            $vehicles = VehicleModel::get();
            $phone_code = $this->phone_code;
        
            // Correctly fetch the latest VOD emp_id by numeric order
            $lastEmp = DB::table('users_meta as um')
                ->join('users as u', 'um.user_id', '=', 'u.id')
                ->where('um.key', 'emp_id')
                ->where('u.user_type', 'V')
                ->whereRaw("um.value REGEXP '^VOD[0-9]+$'")
                ->orderByRaw("CAST(SUBSTRING(um.value, 4) AS UNSIGNED) DESC")
                ->select('um.value')
                ->first();
        
            $lastNumber = 0;
            if ($lastEmp && preg_match('/VOD(\d+)/', $lastEmp->value, $matches)) {
                $lastNumber = intval($matches[1]);
            }
        
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // e.g., 004
            $emp_id = "VOD{$newNumber}";
        
            // Pass all data to the view
            return view('vowner.create', compact('vehicles', 'phone_code', 'emp_id', 'lastEmp'));
        }

        
        public function checkMetaUnique(Request $request)
        {
            $key = $request->input('key');
            $value = $request->input('value');
        
            $exists = DB::table('users_meta')
                ->where('key', $key)
                ->where('value', $value)
                ->exists();
        
            return response()->json(['unique' => !$exists]);
        }


        public function edit($id) {
            $driver = User::findOrFail($id);
        
            if ($driver->user_type != "V") {
                return redirect("admin/vowner");
            }
        
            $driver->load('vehicles');
        
            if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
                $vehicles = VehicleModel::get();
            } else {
                $vehicles = VehicleModel::where('group_id', Auth::user()->group_id)->get();
            }
        
            $phone_code = $this->phone_code;
        
            // Get emp_id from user meta
            $emp_id = $driver->getMeta('emp_id');
        
            return view("vowner.edit", compact("driver", "phone_code", "vehicles", "emp_id"));
        }


	private function upload_file($file, $field, $id) {
		$destinationPath = './uploads'; // upload path
		$extension = $file->getClientOriginalExtension();
		$fileName1 = Str::uuid() . '.' . $extension;
		$file->move($destinationPath, $fileName1);
		$user = User::find($id);
		$user->setMeta([$field => $fileName1]);
		$user->save();
	}
	
    public function checkLicensePlate(Request $request)
    {
        $license_plate = $request->input('license_plate');
        $vehicleId = $request->input('id'); // optional vehicle ID for edit check
    
        if (empty($license_plate)) {
            return response()->json([
                'unique' => false,
                'error' => 'Missing license_plate'
            ]);
        }
    
        $query = VehicleModel::where('license_plate', $license_plate);
    
        // If vehicle ID is provided (e.g., during editing), exclude that record from check
        if (!empty($vehicleId)) {
            $query->where('id', '!=', $vehicleId);
        }
    
        $exists = $query->exists();
    
        return response()->json(['unique' => !$exists]);
    }


	
	
	public function update(VownerRequest $request) {
		$id = $request->get('id');
		$user = User::find($id);
		if ($request->file('driver_image') && $request->file('driver_image')->isValid()) {
			if (file_exists('./uploads/' . $user->driver_image) && !is_dir('./uploads/' . $user->driver_image)) {
				unlink('./uploads/' . $user->driver_image);
			}
			$this->upload_file($request->file('driver_image'), "driver_image", $id);
		}
		if ($request->file('license_image') && $request->file('license_image')->isValid()) {
			if (file_exists('./uploads/' . $user->license_image) && !is_dir('./uploads/' . $user->license_image)) {
				unlink('./uploads/' . $user->license_image);
			}
			$this->upload_file($request->file('license_image'), "license_image", $id);
			$user->id_proof_type = "License";
			$user->save();
		}
		if ($request->file('documents')) {
			if (file_exists('./uploads/' . $user->documents) && !is_dir('./uploads/' . $user->documents)) {
				unlink('./uploads/' . $user->documents);
			}
			$this->upload_file($request->file('documents'), "documents", $id);
		}
		// dd($request->all());
		$user->name = $request->get("first_name") . " " . $request->get("last_name");
		$name = explode(' ', $request->name);
		$user->first_name = $name[0] ?? '';
		$user->middle_name = $name[1] ?? '';
		$user->last_name = $name[2] ?? '';
		$user->email = $request->get('email');
		$user->save();
		// $user->driver_image = $request->get('driver_image');
		$form_data = $request->all();
		unset($form_data['driver_image']);
		unset($form_data['documents']);
		unset($form_data['license_image']);
		$user->setMeta($form_data);
		$to = \Carbon\Carbon::now();
		$from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('exp_date'));
		$diff_in_days = $to->diffInDays($from);
		if ($diff_in_days > 20) {
			$t = DB::table('notifications')
				->where('type', 'like', '%RenewvownerLicence%')
				->where('data', 'like', '%"vid":' . $user->id . '%')
				->delete();
		}
		$user->save();
		return redirect()->route("vowner.index");
	}
    	public function store(VownerRequest $request) {
        // Generate emp_id
        $lastEmp = DB::table('users_meta')
            ->where('key', 'emp_id')
            ->orderByDesc('id')
            ->first();
    
        $lastNumber = 0;
        if ($lastEmp && preg_match('/VOD(\d+)/', $lastEmp->value, $matches)) {
            $lastNumber = intval($matches[1]);
        }
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $emp_id = "VOD{$newNumber}";
    
        // Create user
        $id = User::create([
            "name" => $request->get("first_name") . " " . $request->get("last_name"),
            "email" => $request->get("email"),
            "password" => bcrypt($request->get("password")),
            "user_type" => "V",
            'api_token' => str_random(60),
        ])->id;
    
        $user = User::find($id);
        $user->user_id = Auth::user()->id;
    
        // File uploads
        if ($request->file('driver_image') && $request->file('driver_image')->isValid()) {
            $this->upload_file($request->file('driver_image'), "driver_image", $id);
        }
    
        if ($request->file('license_image') && $request->file('license_image')->isValid()) {
            $this->upload_file($request->file('license_image'), "license_image", $id);
            $user->id_proof_type = "License";
            $user->save();
        }
    
        if ($request->file('documents')) {
            $this->upload_file($request->file('documents'), "documents", $id);
        }
    
        // Set meta data
        $form_data = $request->all();
        $form_data['emp_id'] = $emp_id;
    
        unset($form_data['driver_image'], $form_data['documents'], $form_data['license_image']);
    
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->setMeta($form_data);
        $user->save();
    
        // Set default permissions
        $user->givePermissionTo([
            'Notes add', 'Notes edit', 'Notes delete', 'Notes list',
            'Fuel add', 'Fuel edit', 'Fuel delete', 'Fuel list',
            'VehicleInspection add',
            'Transactions list', 'Transactions add', 'Transactions edit', 'Transactions delete'
        ]);
    
        return redirect()->route("vowner.index");
    }

	public function enable($id) {
		$driver = User::find($id);
		$driver->is_active = 1;
		$driver->save();
		return redirect()->route("vowner.index");
	}
	public function disable($id) {
		$bookings = Bookings::where('vowner_id', $id)->where('status', 0)->get();
		if (count($bookings) > 0) {
			$newErrors = [
				'error' => 'Some active Bookings still have this vowner, please either change the vowner in those bookings or you can deactivate this vowner after those bookings are complete!',
				'data' => $bookings->pluck('id')->toArray(),
			];
			return redirect()->route('vowner.index')->with('errors', $newErrors)->with('bookings', $bookings);
		} else {
			$driver = User::find($id);
			$driver->is_active = 0;
			$driver->save();
			return redirect()->route('vowner.index');
		}
	}
	public function my_bookings() {
		$bookings = Bookings::orderBy('id', 'desc')->wherevowner_id(Auth::user()->id)->get();
		$data = [];
		foreach ($bookings as $booking) {
			if ($booking->getMeta('ride_status') != 'Cancelled') {
				$data[] = $booking;
			}
		}
		return view('vowner.my_bookings', compact('data'));
	}
	public function yearly() {
		$bookings = vownerLogsModel::where('driver_id', Auth::user()->id)->get();
		$v_id = array('0');
		$c = array();
		foreach ($bookings as $key) {
			if ($key->vehicle_id != null) {
				$v_id[] = $key->vehicle_id;
			}
		}
		$years = DB::select("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc");
		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['vehicles'] = VehicleModel::whereIn('id', $v_id)->get();
		$data['year_select'] = date("Y");
		$data['vehicle_select'] = null;
		$data['years'] = $y;
		$in = join(",", $v_id);
		$data['income'] = IncomeModel::select(DB::raw("sum(IFNULL(driver_amount,amount)) as income"))->whereYear('date', date('Y'))->whereIn('vehicle_id', $v_id)->get();
		$data['expenses'] = Expense::select(DB::raw('sum(IFNULL(driver_amount,amount)) as expense'))->whereYear('date', date('Y'))->whereIn('vehicle_id', $v_id)->get();
		$data['expense_by_cat'] = Expense::select('type', 'expense_type', DB::raw('sum(amount) as expense'))->whereYear('date', date('Y'))->whereIn('vehicle_id', $v_id)->groupBy(['expense_type', 'type'])->get();
		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}
		$kk = ExpCats::get();
		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();
		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['result'] = "";
		$data['yearly_income'] = $this->yearly_income();
		$data['yearly_expense'] = $this->yearly_expense();
		return view('vowner.yearly', $data);
	}
	public function yearly_post(Request $request) {
		$bookings = vownerLogsModel::where('vowner_id', Auth::user()->id)->get();
		$v_id = array();
		foreach ($bookings as $key) {
			$v_id[] = $key->vehicle_id;
		}
		$years = DB::select("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc");
		$y = array();
		$b = array();
		$i = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['vehicles'] = VehicleModel::whereIn('id', $v_id)->get();
		$data['year_select'] = $request->get("year");
		$data['vehicle_select'] = $request->get("vehicle_id");
		$data['yearly_income'] = $this->yearly_income();
		$data['yearly_expense'] = $this->yearly_expense();
		$income1 = IncomeModel::select(DB::raw("sum(amount) as income"))->whereYear('date', $data['year_select']);
		$expense1 = Expense::select(DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select']);
		$expense2 = Expense::select('type', 'expense_type', DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select'])->groupBy(['expense_type', 'type']);
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->whereIn('vehicle_id', $v_id)->get();
			$data['expenses'] = $expense1->whereIn('vehicle_id', $v_id)->get();
			$data['expense_by_cat'] = $expense2->whereIn('vehicle_id', $v_id)->get();
		}
		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}
		$kk = ExpCats::get();
		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();
		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['years'] = $y;
		$data['result'] = "";
		return view('vowner.yearly', $data);
	}
	public function monthly() {
		$bookings = vownerLogsModel::where('driver_id', Auth::user()->id)->get();
		$v_id = array('0');
		foreach ($bookings as $key) {
			if ($key->vehicle_id != null) {
				$v_id[] = $key->vehicle_id;
			}
		}
		$years = DB::select("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc");
		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['vehicles'] = VehicleModel::whereIn('id', $v_id)->get();
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['vehicle_select'] = null;
		$data['years'] = $y;
		$data['yearly_income'] = $this->yearly_income();
		$data['yearly_expense'] = $this->yearly_expense();
		$in = join(",", $v_id);
		$data['income'] = IncomeModel::select(DB::raw('sum(IFNULL(driver_amount,amount)) as income'))->whereYear('date', date('Y'))->whereMonth('date', date('n'))->whereIn('vehicle_id', $v_id)->get();
		$data['expenses'] = Expense::select(DB::raw('sum(IFNULL(driver_amount,amount)) as expense'))->whereYear('date', date('Y'))->whereMonth('date', date('n'))->whereIn('vehicle_id', $v_id)->get();
		$data['expense_by_cat'] = DB::select("select type,expense_type,sum(amount) as expense from expense where deleted_at is null and year(date)=" . date("Y") . " and month(date)=" . date("n") . " and vehicle_id in(" . $in . ") group by expense_type,type");
		$ss = ServiceItemsModel::get();
		$c = array();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}
		$kk = ExpCats::get();
		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();
		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['result'] = "";
		return view("vowner.monthly", $data);
	}
	public function monthly_post(Request $request) {
		$bookings = vownerLogsModel::where('vowner_id', Auth::user()->id)->get();
		$v_id = array('0');
		foreach ($bookings as $key) {
			if ($key->vehicle_id != null) {
				$v_id[] = $key->vehicle_id;
			}
		}
		$years = DB::select("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc");
		$y = array();
		$b = array();
		$i = array();
		$c = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['vehicles'] = VehicleModel::whereIn('id', $v_id)->get();
		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['vehicle_select'] = $request->get("vehicle_id");
		$data['yearly_income'] = $this->yearly_income();
		$data['yearly_expense'] = $this->yearly_expense();
		$income1 = IncomeModel::select(DB::raw('sum(amount) as income'))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select']);
		$expense1 = Expense::select(DB::raw('sum(amount) as expense'))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select']);
		$expense2 = Expense::select('type', 'expense_type', DB::raw('sum(amount) as expense'))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->groupBy(['expense_type', 'type']);
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->whereIn('vehicle_id', $v_id)->get();
			$data['expenses'] = $expense1->whereIn('vehicle_id', $v_id)->get();
			$data['expense_by_cat'] = $expense2->whereIn('vehicle_id', $v_id)->get();
		}
		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}
		$kk = ExpCats::get();
		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();
		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['years'] = $y;
		$data['result'] = "";
		return view("vowner.monthly", $data);
	}
	private function yearly_income() {
		$bookings = vownerLogsModel::where('driver_id', Auth::user()->id)->get();
		$v_id = array('0');
		foreach ($bookings as $key) {
			if ($key->vehicle_id != null) {
				$v_id[] = $key->vehicle_id;
			}
		}
		$in = join(",", $v_id);
		$incomes = DB::select('select monthname(date) as mnth,sum(amount) as tot from income where year(date)=? and  deleted_at is null and vehicle_id in(' . $in . ') group by month(date)', [date("Y")]);
		$months = ["January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0];
		$income2 = array();
		foreach ($incomes as $income) {
			$income2[$income->mnth] = $income->tot;
		}
		$yr = array_merge($months, $income2);
		return implode(",", $yr);
	}
	private function yearly_expense() {
		$bookings = vownerLogsModel::where('driver_id', Auth::user()->id)->get();
		$v_id = array('0');
		foreach ($bookings as $key) {
			if ($key->vehicle_id != null) {
				$v_id[] = $key->vehicle_id;
			}
		}
		$in = join(",", $v_id);
		$incomes = DB::select('select monthname(date) as mnth,sum(amount) as tot from expense where year(date)=? and  deleted_at is null and vehicle_id in(' . $in . ') group by month(date)', [date("Y")]);
		$months = ["January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0];
		$income2 = array();
		foreach ($incomes as $income) {
			$income2[$income->mnth] = $income->tot;
		}
		$yr = array_merge($months, $income2);
		return implode(",", $yr);
	}
	public function firebase() {
		$database = app('firebase.database');
		$details = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$markers = array();
		foreach ($details as $d) {
			// echo $d['user_name'] . "</br>";
			if ($d['user_type'] == "D") {
				$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude'], 'av' => $d['availability']],
				);
			}
		}
	}
	public function vowner_maps() {
		$database = app('firebase.database');
		$all_data = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$driver = array();
		if ($all_data != null) {
			foreach ($all_data as $d) {
				if (isset($d['latitude']) && isset($d['longitude'])) {
					if ($d['latitude'] != null || $d['longitude'] != null) {
						$driver[] = array('user_name' => $d['user_name'], 'availability' => $d['availability'],
							'user_id' => $d['user_id'],
						);
					}
				}
			}
		}
		$index['details'] = $driver;
		// dd($driver);
		return view('vowner_maps', $index);
	}
	public function markers() {
		// $data = Firebase::get('/User_Locations/');
		$database = app('firebase.database');
		$details = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$markers = array();
		foreach ($details as $d) {
			if (isset($d['latitude']) && isset($d['longitude'])) {
				if ($d['latitude'] != null || $d['longitude'] != null) {
					if ($d['availability'] == "1") {
						$icon = "online.png";
						$status = "Online";
					} else {
						$icon = "offline.png";
						$status = "Offline";
					}
					$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
				}
			}
		}
		return json_encode($markers);
	}
	//temp
	public function markers_filter($id) {
		$database = app('firebase.database');
		$details = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$markers = array();
		foreach ($details as $d) {
			if (isset($d['latitude']) && isset($d['longitude'])) {
				if ($d['latitude'] != null || $d['longitude'] != null) {
					if ($d['availability'] == "1") {
						$icon = "online.png";
						$status = "Online";
					} else {
						$icon = "offline.png";
						$status = "Offline";
					}
					if ($id == 1) {
						if ($d['availability'] == "1") {
							$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
						}
					}if ($id == 0) {
						if ($d['availability'] == "0") {
							$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
						}
					}if ($id == 2) {
						$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
					}
				}
			}
		}
		return json_encode($markers);
	}
	public function track_markers($id) {
		$database = app('firebase.database');
		$details = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$markers = array();
		foreach ($details as $d) {
			if (isset($d['latitude']) && isset($d['longitude'])) {
				if ($d['latitude'] != null || $d['longitude'] != null) {
					if ($d['availability'] == "1") {
						$icon = "online.png";
						$status = "Online";
					} else {
						$icon = "offline.png";
						$status = "Offline";
					}
					if ($id == 1) {
						if ($d['availability'] == "1") {
							$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
						}
					}if ($id == 0) {
						if ($d['availability'] == "0") {
							$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
						}
					}if ($id == 2) {
						$markers[] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
					}
				}
			}
		}
		return json_encode($markers);
	}
	public function track_vowner($id) {
		$database = app('firebase.database');
		$data = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		$details = $index['details'] = $data;
		foreach ($details as $d) {
			if ($d['user_id'] == $id) {
				if ($d['availability'] == "1") {
					$icon = "online.png";
					$status = "Online";
				} else {
					$icon = "offline.png";
					$status = "Offline";
				}
				if (isset($d['latitude']) && isset($d['longitude'])) {
					$index['vowner'] = array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status);
				}
			}
		}
		return view('track_vowner', $index);
	}
	public function single_vowner($id) {
		$database = app('firebase.database');
		$data = $database
			->getReference('/User_Locations')
			->orderByChild('user_type')
			->equalTo('D')
			->getValue();
		foreach ($data as $d) {
			if ($d['user_id'] == $id) {
				if (isset($d['latitude']) && isset($d['longitude'])) {
					if ($d['latitude'] != null || $d['longitude'] != null) {
						if ($d['availability'] == "1") {
							$icon = "online.png";
							$status = "Online";
						} else {
							$icon = "offline.png";
							$status = "Offline";
						}
						$driver = [array("id" => $d["user_id"], "name" => $d["user_name"], "position" => ["lat" => $d['latitude'], "long" => $d['longitude']], "icon" => $icon, 'status' => $status)];
					}
				}
			}
		}
		return json_encode($driver);
	}
}
