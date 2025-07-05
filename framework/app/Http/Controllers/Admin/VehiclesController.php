<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\InsuranceRequest;
use App\Http\Requests\VehicleRequest;
use App\Http\Requests\VehicleAssignRequest;
use App\Http\Requests\VehiclReviewRequest;
use App\Imports\VehicleImport;
use App\Model\Bookings;
use App\Model\DriverLogsModel;
use App\Model\DriverVehicleModel;
use App\Model\Expense;
use App\Model\FuelModel;
use App\Model\Hyvikk;
use App\Model\IncomeModel;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleGroupModel;
use App\Model\VehicleReviewModel;
use App\Model\VehicleTypeModel;
use Auth;
use App\Model\VehicleModel;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use App\Model\ReasonsModel;
use App\Model\IncCats;

class VehiclesController extends Controller {
	public function __construct() {
// 		$this->middleware(['role:Admin']);
// 		$this->middleware('permission:Vehicles add', ['only' => ['create', 'upload_file', 'upload_doc', 'store']]);
// 		$this->middleware('permission:Vehicles edit', ['only' => ['edit', 'upload_file', 'upload_doc', 'update']]);
// 		$this->middleware('permission:Vehicles delete', ['only' => ['bulk_delete', 'destroy']]);
// 		$this->middleware('permission:Vehicles list', ['only' => ['index', 'driver_logs', 'view_event', 'store_insurance', 'assign_driver']]);
// 		$this->middleware('permission:Vehicles import', ['only' => ['importVehicles']]);
// 		$this->middleware('permission:VehicleInspection add', ['only' => ['vehicle_review', 'store_vehicle_review', 'vehicle_inspection_create']]);
// 		$this->middleware('permission:VehicleInspection edit', ['only' => ['review_edit', 'update_vehicle_review']]);
// 		$this->middleware('permission:VehicleInspection delete', ['only' => ['bulk_delete_reviews', 'destroy_vehicle_review']]);
// 		$this->middleware('permission:VehicleInspection list', ['only' => ['vehicle_review_index', 'print_vehicle_review', 'view_vehicle_review']]);
	}
	public function importVehicles(ImportRequest $request) {
		$file = $request->excel;
		$destinationPath = './uploads/xml'; // upload path
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
		Excel::import(new VehicleImport, $destinationPath . '/' . $fileName);
		return back();
	}
	public function index() {
		return view("vehicles.index");
	}
    public function table() {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $index['groups'] = VehicleGroupModel::all();
        } else {
            $index['groups'] = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
    
        $index['types'] = VehicleTypeModel::where('isenable', 1)->get();
        $index['makes'] = VehicleModel::groupBy('make_name')->get()->pluck('make_name')->toArray();
        $index['models'] = VehicleModel::groupBy('model_name')->get()->pluck('model_name')->toArray();
        $index['colors'] = VehicleModel::groupBy('color_name')->get()->pluck('color_name')->toArray();
    
        // Filter drivers based on logged-in user's ID
        if (Auth::id() != 1) {
            $userId = Auth::id();
            $index['drivers'] = User::where('user_type', 'D')
                ->whereHas('metas', function ($query) use ($userId) {
                    $query->where('key', 'owner_id')->where('value', $userId);
                })->get();
        } else {
            $index['drivers'] = User::where('user_type', 'D')->get();
        }
    
        // Filter vowners based on logged-in user's ID
        if (Auth::id() != 1) {
            $userId = Auth::id();
            $index['vowners'] = User::whereUser_type("V")->where('id', $userId)->get();
        } else {
            $index['vowners'] = User::whereUser_type("V")->get();
        }
    
        return view("vehicles.table", $index);
    }
	public function fetch_data(Request $request) {
        if ($request->ajax()) {
            $user = Auth::user();
    
            // Base query
            if ($user->group_id == null || $user->user_type == "S") {
                $vehicles = VehicleModel::select('vehicles.*', 'users.name as name');
            } else {
                $vehicles = VehicleModel::select('vehicles.*', 'users.name as name')->where('vehicles.group_id', $user->group_id);
            }
    
            // Join relations
            $vehicles = $vehicles
                ->leftJoin('driver_vehicle', 'driver_vehicle.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('users', 'users.id', '=', 'driver_vehicle.driver_id');
    
             if ($user->id != 1) {
                $vehicles = $vehicles
                    ->leftJoin('vehicles_meta as vm', function ($join) {
                        $join->on('vm.vehicle_id', '=', 'vehicles.id')
                             ->where('vm.key', '=', 'assign_vowner_id');
                    })
                    ->where('vm.value', $user->id);
            }
    
            $vehicles = $vehicles->groupBy('vehicles.id');
            $vehicles->with(['group', 'types', 'drivers']);
    
            return DataTables::eloquent($vehicles)
                ->addColumn('check', function ($vehicle) {
                    return '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>'; 
                })
                ->editColumn('vehicle_image', function ($vehicle) {
                    $src = ($vehicle->vehicle_image != null) ? asset('uploads/' . $vehicle->vehicle_image) : asset('assets/images/vehicle.jpeg');
                    return '<img src="' . $src . '" height="70px" width="70px">';
                })
                ->addColumn('make', function ($vehicle) {
                    return $vehicle->make_name ?? '';
                })
                ->addColumn('model', function ($vehicle) {
                    return $vehicle->model_name ?? '';
                })
                ->addColumn('displayname', function ($vehicle) {
                    return $vehicle->types->displayname ?? '';
                })
                ->addColumn('color', function ($vehicle) {
                    return $vehicle->color_name ?? '';
                })
                ->editColumn('license_plate', function ($vehicle) {
                    return $vehicle->license_plate;
                })
                ->addColumn('group', function ($vehicle) {
                    return $vehicle->group->name ?? '';
                })
                ->addColumn('LXBXH', function ($vehicle) {
                    $LBH = ($vehicle->length ? $vehicle->length . ' X ' : '') .
                           ($vehicle->breadth ? $vehicle->breadth . ' X ' : '') .
                           $vehicle->height;
                    return $LBH;
                })
                ->addColumn('weight', function ($vehicle) {
                    return $vehicle->weight;
                })
                ->addColumn('in_service', function ($vehicle) {
                    return ($vehicle->in_service) ? "YES" : "NO";
                })
                ->filterColumn('in_service', function ($query, $keyword) {
                    $query->whereRaw("IF(in_service = 1, 'YES', 'NO') like ?", ["%{$keyword}%"]);
                })
                ->addColumn('assigned_driver', function ($vehicle) {
                    $driverId = $vehicle->getMeta('assign_driver_id');
                    if (!is_null($driverId)) {
                        $driver = User::find($driverId);
                        return $driver ? $driver->name : null;
                    }
                    return null;
                })
                ->addColumn('assigned_owner', function ($vehicle) {
                    $driverId = $vehicle->getMeta('assign_vowner_id');
                    if (!is_null($driverId)) {
                        $driver = User::find($driverId);
                        return $driver ? $driver->name : null;
                    }
                    return null;
                })
                
                ->addColumn('action', function ($vehicle) {
                    return view('vehicles.list-actions', ['row' => $vehicle]);
                })
                ->addIndexColumn()
                ->rawColumns(['vehicle_image', 'action', 'check'])
                ->make(true);
        }
    }
    
    	public function my_bookings_vowner() {
		$bookings = Bookings::orderBy('id', 'desc')->whereDriver_id(Auth::user()->id)->get();
		$data = [];
		foreach ($bookings as $booking) {
			if ($booking->getMeta('ride_status') != 'Cancelled') {
				$data[] = $booking;
			}
		}
		return view('vowner.my_bookings', compact('data'));
	    }
    
    public function fetch_table_data(Request $request) {
        if ($request->ajax()) {
            $user = Auth::user();
    
            // Base query
            if ($user->group_id == null || $user->user_type == "S") {
                $vehicles = VehicleModel::select('vehicles.*', 'users.name as name');
            } else {
                $vehicles = VehicleModel::select('vehicles.*', 'users.name as name')->where('vehicles.group_id', $user->group_id);
            }
    
            // Join relations
            $vehicles = $vehicles
                ->leftJoin('driver_vehicle', 'driver_vehicle.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('users', 'users.id', '=', 'driver_vehicle.driver_id');
    
           
            if ($user->id != 1) {
                $vehicles = $vehicles
                    ->leftJoin('vehicles_meta as vm', function ($join) {
                        $join->on('vm.vehicle_id', '=', 'vehicles.id')
                             ->where('vm.key', '=', 'assign_vowner_id');
                    })
                    ->where('vm.value', $user->id);
            }
    
            $vehicles = $vehicles->groupBy('vehicles.id');
            $vehicles->with(['group', 'types', 'drivers']);
    
            return DataTables::eloquent($vehicles)
                ->addColumn('check', function ($vehicle) {
                    return '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>'; 
                })
               
                ->addColumn('make', function ($vehicle) {
                    return $vehicle->make_name ?? '';
                })
                ->addColumn('model', function ($vehicle) {
                    return $vehicle->model_name ?? '';
                })
                ->addColumn('displayname', function ($vehicle) {
                    return $vehicle->types->displayname ?? '';
                })
               
                ->editColumn('license_plate', function ($vehicle) {
                    return $vehicle->license_plate;
                })
                
                ->addColumn('LXBXH', function ($vehicle) {
                    $LBH = ($vehicle->length ? $vehicle->length . ' X ' : '') .
                           ($vehicle->breadth ? $vehicle->breadth . ' X ' : '') .
                           $vehicle->height;
                    return $LBH;
                })
                ->addColumn('weight', function ($vehicle) {
                    return $vehicle->weight;
                })
                ->addColumn('in_service', function ($vehicle) {
                    return ($vehicle->in_service) ? "YES" : "NO";
                })
                ->addColumn('assigned_owner', function ($vehicle) {
                    $driverId = $vehicle->getMeta('assign_vowner_id');
                    if (!is_null($driverId)) {
                        $driver = User::find($driverId);
                        return $driver ? $driver->name : null;
                    }
                    return null;
                })
                ->addColumn('assigned_driver', function ($vehicle) {
                    $driverId = $vehicle->getMeta('assign_driver_id');
                    if (!is_null($driverId)) {
                        $driver = User::find($driverId);
                        return $driver ? $driver->name : null;
                    }
                    return null;
                })
                ->addColumn('action', function ($vehicle) {
                    return view('vehicles.list-actions_table', ['row' => $vehicle]);
                })
                ->addIndexColumn()
                ->rawColumns(['vehicle_image', 'action', 'check'])
                ->make(true);
        }
    }



	public function driver_logs() {
		return view('vehicles.driver_logs');
	}
	public function driver_logs_fetch_data(Request $request) {
		if ($request->ajax()) {
			$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
			$user = Auth::user();
			if ($user->group_id == null || $user->user_type == "S") {
				$vehicle_ids = VehicleModel::select('id')->get('id')->pluck('id')->toArray();
			} else {
				$vehicle_ids = VehicleModel::select('id')->where('group_id', $user->group_id)->get('id')->pluck('id')->toArray();
			}
			$logs = DriverLogsModel::select('driver_logs.*')->with('driver')
				->whereIn('vehicle_id', $vehicle_ids)
				->leftJoin('vehicles', 'vehicles.id', '=', 'driver_logs.vehicle_id');
			return DataTables::eloquent($logs)
				->addColumn('check', function ($vehicle) {
					$tag = '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>';
					return $tag;
				})
				->addColumn('vehicle', function ($user) {
					return $user->make_name . '-' . $user->model_name . '-' . $user->vehicle->license_plate;
				})
				->addColumn('driver', function ($log) {
					return ($log->driver->name) ?? "";
				})
				->editColumn('date', function ($log) use ($date_format_setting) {
					// return date($date_format_setting . ' g:i A', strtotime($log->date));
					return [
						'display' => date($date_format_setting . ' g:i A', strtotime($log->date)),
						'timestamp' => Carbon::parse($log->date),
					];
				})
				->filterColumn('date', function ($query, $keyword) {
					$query->whereRaw("DATE_FORMAT(date,'%d-%m-%Y %h:%i %p') LIKE ?", ["%$keyword%"]);
				})
				->filterColumn('vehicle', function ($query, $keyword) {
					$query->whereRaw("CONCAT(vehicles.make_name , '-' , vehicles.model_name , '-' , vehicles.license_plate) like ?", ["%$keyword%"]);
					return $query;
				})
				->addColumn('action', function ($vehicle) {
					return view('vehicles.driver-logs-list-actions', ['row' => $vehicle]);
				})
				->addIndexColumn()
				->rawColumns(['action', 'check'])
				->make(true);
		}
	}
	
    public function checkLicensePlate(Request $request)
    {
        $license_plate = $request->input('license_plate');
    
        if (empty($license_plate)) {
            return response()->json([
                'unique' => false,
                'error' => 'Missing license_plate'
            ]);
        }
    
        $exists = VehicleModel::where('license_plate', $license_plate)->exists();
    
        return response()->json(['unique' => !$exists]);
    }
 


	
    public function create() {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $index['groups'] = VehicleGroupModel::all();
        } else {
            $index['groups'] = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
    
        $index['types'] = VehicleTypeModel::where('isenable', 1)->get();
        $index['makes'] = VehicleModel::groupBy('make_name')->get()->pluck('make_name')->toArray();
        $index['models'] = VehicleModel::groupBy('model_name')->get()->pluck('model_name')->toArray();
        $index['colors'] = VehicleModel::groupBy('color_name')->get()->pluck('color_name')->toArray();
    
        // Filter drivers based on logged-in user's ID
        if (Auth::id() != 1) {
            $userId = Auth::id();
            $index['drivers'] = User::where('user_type', 'D')
                ->whereHas('metas', function ($query) use ($userId) {
                    $query->where('key', 'owner_id')->where('value', $userId);
                })->get();
        } else {
            $index['drivers'] = User::where('user_type', 'D')->get();
        }
    
        // Filter vowners based on logged-in user's ID
        if (Auth::id() != 1) {
            $userId = Auth::id();
            $index['vowners'] = User::whereUser_type("V")->where('id', $userId)->get();
        } else {
            $index['vowners'] = User::whereUser_type("V")->get();
        }
    
        return view("vehicles.create", $index);
    }


	public function get_models($name) {
		$makes = VehicleModel::groupBy('make_name')->where('make_name', $name)->get();
		$data = array();
		foreach ($makes as $make) {
			array_push($data, array("id" => $make->model_name, "text" => $make->model_name));
		}
		return $data;
	}
	public function destroy(Request $request) {
		$vehicle = VehicleModel::find($request->get('id'));
		if ($vehicle->driver_id) {
			if ($vehicle->drivers->count()) {
				$vehicle->drivers()->detach($vehicle->drivers->pluck('id')->toArray());
			}
		}
		if (file_exists('./uploads/' . $vehicle->vehicle_image) && !is_dir('./uploads/' . $vehicle->vehicle_image)) {
			unlink('./uploads/' . $vehicle->vehicle_image);
		}
		DriverVehicleModel::where('vehicle_id', $request->id)->delete();
		VehicleModel::find($request->get('id'))->income()->delete();
		VehicleModel::find($request->get('id'))->expense()->delete();
		VehicleModel::find($request->get('id'))->delete();
		VehicleReviewModel::where('vehicle_id', $request->get('id'))->delete();
		ServiceReminderModel::where('vehicle_id', $request->get('id'))->delete();
		FuelModel::where('vehicle_id', $request->get('id'))->delete();
		return redirect()->route('vehicles.index');
	}
    public function edit($id) {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $groups = VehicleGroupModel::all();
        } else {
            $groups = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
    
        
    
        // Add this line to define $vowners
        if (Auth::id() != 1) {
            $vowners = User::where('user_type', 'V')->where('id', Auth::id())->get();
       
        } else {
            $vowners = User::where('user_type', 'V')->get();

        }
        
        if (Auth::id() != 1) {
            $drivers = User::with('metas')
                ->where('user_type', 'D')
                ->whereHas('metas', function ($query) {
                    $query->where('key', 'owner_id')
                          ->where('value', auth()->id());
                })
                ->get();
        } else {
            $drivers = User::with('metas')
                ->where('user_type', 'D')
                ->get();
        }
    
        $vehicle = VehicleModel::findOrFail($id);
        $vehicle->load('drivers');
    
        $udfs = unserialize($vehicle->getMeta('udf'));
    
        $makes = VehicleModel::groupBy('make_name')->get()->pluck('make_name')->toArray();
        $models = VehicleModel::groupBy('model_name')->get()->pluck('model_name')->toArray();
        $colors = VehicleModel::groupBy('color_name')->get()->pluck('color_name')->toArray();
    
        $types = VehicleTypeModel::where('isenable', 1)->get();
    
        // Add 'vowners' to the view
        return view("vehicles.edit", compact('vehicle', 'groups', 'drivers', 'vowners', 'udfs', 'types', 'makes', 'models', 'colors'));
    }
    
    public function modify($id) {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $groups = VehicleGroupModel::all();
        } else {
            $groups = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
    
        
    
        // Add this line to define $vowners
        if (Auth::id() != 1) {
            $vowners = User::where('user_type', 'V')->where('id', Auth::id())->get();
       
        } else {
            $vowners = User::where('user_type', 'V')->get();

        }
        
        if (Auth::id() != 1) {
            $drivers = User::with('metas')
                ->where('user_type', 'D')
                ->whereHas('metas', function ($query) {
                    $query->where('key', 'owner_id')
                          ->where('value', auth()->id());
                })
                ->get();
        } else {
            $drivers = User::with('metas')
                ->where('user_type', 'D')
                ->get();
        }
    
        $vehicle = VehicleModel::findOrFail($id);
        $vehicle->load('drivers');
    
        $udfs = unserialize($vehicle->getMeta('udf'));
    
        $makes = VehicleModel::groupBy('make_name')->get()->pluck('make_name')->toArray();
        $models = VehicleModel::groupBy('model_name')->get()->pluck('model_name')->toArray();
        $colors = VehicleModel::groupBy('color_name')->get()->pluck('color_name')->toArray();
    
        $types = VehicleTypeModel::where('isenable', 1)->get();
    
        // Add 'vowners' to the view
        return view("vehicles.modify", compact('vehicle', 'groups', 'drivers', 'vowners', 'udfs', 'types', 'makes', 'models', 'colors'));
    }


	private function upload_file($file, $field, $id) {
		$destinationPath = './uploads'; // upload path
		$extension = $file->getClientOriginalExtension();
		$fileName1 = Str::uuid() . '.' . $extension;
		$file->move($destinationPath, $fileName1);
		$x = VehicleModel::find($id)->update([$field => $fileName1]);
	}
	private function upload_doc($file, $field, $id) {
		$destinationPath = './uploads'; // upload path
		$extension = $file->getClientOriginalExtension();
		$fileName1 = Str::uuid() . '.' . $extension;
		$file->move($destinationPath, $fileName1);
		$vehicle = VehicleModel::find($id);
		$vehicle->setMeta([$field => $fileName1]);
		$vehicle->save();
	}
	public function update(VehicleRequest $request) {

		
		$id = $request->get('id');
		$vehicle = VehicleModel::find($request->get("id"));
		if ($request->file('vehicle_image') && $request->file('vehicle_image')->isValid()) {
			if (file_exists('./uploads/' . $vehicle->vehicle_image) && !is_dir('./uploads/' . $vehicle->vehicle_image)) {
				unlink('./uploads/' . $vehicle->vehicle_image);
			}
			$this->upload_file($request->file('vehicle_image'), "vehicle_image", $id);
		}

		if ($request->file('icon') && $request->file('icon')->isValid()) {
			if (file_exists('./uploads/' . $vehicle->icon) && !is_dir('./uploads/' . $vehicle->icon)) {
				unlink('./uploads/' . $vehicle->icon);
			}
			$icon=$request->file('icon');
			$icon_path=uniqid().$icon->getClientOriginalName();
            $icon->move('./uploads/', $icon_path);

			$vehicle->setMeta([
				'icon'=>$icon_path
			]);
		}

		$form_data = $request->all();
		// dd($form_data);
		unset($form_data['vehicle_image']);
		unset($form_data['documents']);
		unset($form_data['udf']);
		$vehicle->update($form_data);
		$vehicle->setMeta([
			'traccar_device_id' => $request->traccar_device_id,
			'traccar_vehicle_id' => $request->traccar_vehicle_id,
			'luggage'=>$request->luggage,
			'price'=>$request->price,
		]);
		if ($request->get("in_service")) {
			$vehicle->in_service = 1;
		} else {
			$vehicle->in_service = 0;
		}
		$vehicle->int_mileage = $request->get("int_mileage");
		$vehicle->lic_exp_date = $request->get('lic_exp_date');
		$vehicle->reg_exp_date = $request->get('reg_exp_date');
		$vehicle->udf = serialize($request->get('udf'));
		$vehicle->average = $request->average;
		$vehicle->save();
		$to = \Carbon\Carbon::now();
		$from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('reg_exp_date'));
		$diff_in_days = $to->diffInDays($from);
		if ($diff_in_days > 20) {
			$t = DB::table('notifications')
				->where('type', 'like', '%RenewRegistration%')
				->where('data', 'like', '%"vid":' . $vehicle->id . '%')
				->delete();
		}
		$from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('lic_exp_date'));
		$diff_in_days = $to->diffInDays($from);
		if ($diff_in_days > 20) {
			DB::table('notifications')
				->where('type', 'like', '%RenewVehicleLicence%')
				->where('data', 'like', '%"vid":' . $vehicle->id . '%')
				->delete();
		}
		return Redirect::route("vehicles.index");
	}
	
	public function change(VehicleAssignRequest $request) {

		
		$id = $request->get('id');
		$vehicle = VehicleModel::find($request->get("id"));
		if ($request->file('vehicle_image') && $request->file('vehicle_image')->isValid()) {
			if (file_exists('./uploads/' . $vehicle->vehicle_image) && !is_dir('./uploads/' . $vehicle->vehicle_image)) {
				unlink('./uploads/' . $vehicle->vehicle_image);
			}
			$this->upload_file($request->file('vehicle_image'), "vehicle_image", $id);
		}

		if ($request->file('icon') && $request->file('icon')->isValid()) {
			if (file_exists('./uploads/' . $vehicle->icon) && !is_dir('./uploads/' . $vehicle->icon)) {
				unlink('./uploads/' . $vehicle->icon);
			}
			$icon=$request->file('icon');
			$icon_path=uniqid().$icon->getClientOriginalName();
            $icon->move('./uploads/', $icon_path);

			$vehicle->setMeta([
				'icon'=>$icon_path
			]);
		}

		$form_data = $request->all();
		// dd($form_data);
		unset($form_data['vehicle_image']);
		unset($form_data['documents']);
		unset($form_data['udf']);
		$vehicle->update($form_data);
		$vehicle->setMeta([
			'traccar_device_id' => $request->traccar_device_id,
			'traccar_vehicle_id' => $request->traccar_vehicle_id,
			'luggage'=>$request->luggage,
			'price'=>$request->price,
		]);
		if ($request->get("in_service")) {
			$vehicle->in_service = 1;
		} else {
			$vehicle->in_service = 0;
		}
		$vehicle->int_mileage = $request->get("int_mileage");
		$vehicle->lic_exp_date = $request->get('lic_exp_date');
		$vehicle->reg_exp_date = $request->get('reg_exp_date');
		$vehicle->udf = serialize($request->get('udf'));
		$vehicle->average = $request->average;
		$vehicle->save();
		$to = \Carbon\Carbon::now();
		
		return Redirect::route("vehicles.modify", ['id' => $vehicle->id]);
	}
	
	public function store(VehicleRequest $request) {
		// dd($request->all());
		$user_id = $request->get('user_id');
		$vehicle = VehicleModel::create([
			'make_name' => $request->get("make_name"),
			'model_name' => $request->get("model_name"),
			// 'type' => $request->get("type"),
			'year' => $request->get("year"),
			'engine_type' => $request->get("engine_type"),
			'horse_power' => $request->get("horse_power"),
			'color_name' => $request->get("color_name"),
			'vin' => $request->get("vin"),
			'license_plate' => $request->get("license_plate"),
			'int_mileage' => $request->get("int_mileage"),
			'group_id' => $request->get('group_id'),
			'user_id' => $request->get('user_id'),
			'lic_exp_date' => $request->get('lic_exp_date'),
			'reg_exp_date' => $request->get('reg_exp_date'),
			'in_service' => $request->get("in_service"),
			'type_id' => $request->get('type_id'),
			// 'vehicle_image' => $request->get('vehicle_image'),
			'height' => $request->height,
			'length' => $request->length,
			'breadth' => $request->breadth,
			'weight' => $request->weight,
		])->id;
		if ($request->file('vehicle_image') && $request->file('vehicle_image')->isValid()) {
			$this->upload_file($request->file('vehicle_image'), "vehicle_image", $vehicle);
		}
		


		$meta = VehicleModel::find($vehicle);
		
		if ($request->file('icon') && $request->file('icon')->isValid()) {
			if (!empty($meta->icon) && file_exists('./uploads/' . $meta->icon) && !is_dir('./uploads/' . $meta->icon)) {
				unlink('./uploads/' . $meta->icon);
			}
		
			$icon = $request->file('icon');
			$icon_path = uniqid() . $icon->getClientOriginalName();
			$icon->move('./uploads/', $icon_path);
		
			$meta->setMeta([
				'icon' => $icon_path
			]);
		}

		$meta->setMeta([
			'ins_number' => "",
			'ins_exp_date' => "",
			'documents' => "",
			'traccar_device_id' => $request->traccar_device_id,
			'traccar_vehicle_id' => $request->traccar_vehicle_id,
			'assign_driver_id' => $request->driver_id,
			'assign_vowner_id' => $request->vowner_id,
			'luggage'=>$request->luggage,
			'price'=>$request->price,
		]);
		$meta->udf = serialize($request->get('udf'));
		$meta->average = $request->average;
		$meta->save();
		$meta->drivers()->sync($request->driver_id);
		DriverLogsModel::create(['driver_id' => $request->driver_id, 'vehicle_id' => $meta->id, 'date' => date('Y-m-d H:i:s')]);
		$vehicle_id = $vehicle;
		return view("vehicles.index");
	}
	

	
	public function store_insurance(InsuranceRequest $request) {
		$vehicle = VehicleModel::find($request->get('vehicle_id'));
		$vehicle->setMeta([
			'ins_number' => $request->get("insurance_number"),
			'ins_exp_date' => $request->get('exp_date'),
			// 'documents' => $request->get('documents'),
		]);
		$vehicle->save();
		if ($vehicle->getMeta('ins_exp_date') != null) {
			$ins_date = $vehicle->getMeta('ins_exp_date');
			$to = \Carbon\Carbon::now();
			$from = \Carbon\Carbon::createFromFormat('Y-m-d', $ins_date);
			$diff_in_days = $to->diffInDays($from);
			if ($diff_in_days > 20) {
				$t = DB::table('notifications')
					->where('type', 'like', '%RenewInsurance%')
					->where('data', 'like', '%"vid":' . $vehicle->id . '%')
					->delete();
			}
		}
		if ($request->file('documents') && $request->file('documents')->isValid()) {
			$this->upload_doc($request->file('documents'), 'documents', $vehicle->id);
		}
		// return $vehicle;
		return redirect('admin/vehicles/' . $request->get('vehicle_id') . '/edit?tab=insurance');
	}
	public function view_event($id) {
		$data['vehicle'] = VehicleModel::with(['drivers.metas', 'types', 'metas'])->where('id', $id)->get()->first();
		return view("vehicles.view_event", $data);
	}
    public function assign_driver(Request $request) {
        $vehicle = VehicleModel::find($request->get('vehicle_id'));
    
        // Save driver and vowner as vehicle meta
        $vehicle->setMeta([
            'assign_driver_id' => $request->driver_id,
            'assign_vowner_id' => $request->vowner_id,
        ]);
    
        $vehicle->save();
    
        // Sync driver in pivot table
        $vehicle->drivers()->sync($request->driver_id);
    
        // Create driver log
        DriverLogsModel::create([
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->get('vehicle_id'),
            'date' => now(),
        ]);
    
        return redirect('admin/vehicles/' . $request->get('vehicle_id') . '/edit?tab=driver');
    }

	public function vehicle_review() {
		$user = Auth::user();
		if ($user->group_id == null || $user->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', $user->group_id)->get();
		}
		return view('vehicles.vehicle_review', $data);
	}
	public function vehicle_inspection_create() {
		// // old get vehicles before driver vehicles many-to-many
		// $data['vehicles'] = DriverLogsModel::where('driver_id', Auth::user()->id)->get();
		if (Auth::user()->user_type == "D") {
			$assign_vehicles = VehicleModel::whereIn_service("1")->whereMeta('assign_driver_id', Auth::user()->id)->pluck('id')->toArray();
			$booking_associated_vehicle_1 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Upcoming')
				->pluck('vehicle_id')->toArray();
			$booking_associated_vehicle_2 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Ongoing')
				->pluck('vehicle_id')->toArray();
			$mergedArray = array_unique(array_merge($booking_associated_vehicle_1, $booking_associated_vehicle_2, $assign_vehicles));
			$data['vehicles'] = VehicleModel::whereIn('id', $mergedArray)->get();
		} else {
			$data['vehicles'] = Auth::user()->vehicles()->with('metas')->get();
		}
		return view('vehicles.vehicle_inspection_create', $data);
	}
	public function vehicle_inspection_index() {
		$vehicle = DriverLogsModel::where('driver_id', Auth::user()->id)->get()->toArray();
		if ($vehicle) {
			// $data['reviews'] = VehicleReviewModel::where('vehicle_id', $vehicle[0]['vehicle_id'])->orderBy('id', 'desc')->get();
			$data['reviews'] = VehicleReviewModel::select('vehicle_review.*')
				->whereHas('vehicle', function ($q) {
					$q->whereHas('drivers', function ($q) {
						$q->where('users.id', auth()->id());
					});
				})
				->orderBy('vehicle_review.id', 'desc')->get();
		} else {
			$data['reviews'] = [];
		}
		// dd($data);
		return view('vehicles.vehicle_inspection_index', $data);
	}
	public function view_vehicle_booking($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.view_vehicle_booking', $data);
	}
	public function print_vehicle_booking($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.print_vehicle_booking', $data);
	}
	public function store_vehicle_booking_review(VehiclReviewRequest $request) {
		$petrol_card = array('flag' => $request->get('petrol_card'), 'text' => $request->get('petrol_card_text'));
		$lights = array('flag' => $request->get('lights'), 'text' => $request->get('lights_text'));
		$invertor = array('flag' => $request->get('invertor'), 'text' => $request->get('invertor_text'));
		$car_mats = array('flag' => $request->get('car_mats'), 'text' => $request->get('car_mats_text'));
		$int_damage = array('flag' => $request->get('int_damage'), 'text' => $request->get('int_damage_text'));
		$int_lights = array('flag' => $request->get('int_lights'), 'text' => $request->get('int_lights_text'));
		$ext_car = array('flag' => $request->get('ext_car'), 'text' => $request->get('ext_car_text'));
		$tyre = array('flag' => $request->get('tyre'), 'text' => $request->get('tyre_text'));
		$ladder = array('flag' => $request->get('ladder'), 'text' => $request->get('ladder_text'));
		$leed = array('flag' => $request->get('leed'), 'text' => $request->get('leed_text'));
		$power_tool = array('flag' => $request->get('power_tool'), 'text' => $request->get('power_tool_text'));
		$ac = array('flag' => $request->get('ac'), 'text' => $request->get('ac_text'));
		$head_light = array('flag' => $request->get('head_light'), 'text' => $request->get('head_light_text'));
		$lock = array('flag' => $request->get('lock'), 'text' => $request->get('lock_text'));
		$windows = array('flag' => $request->get('windows'), 'text' => $request->get('windows_text'));
		$condition = array('flag' => $request->get('condition'), 'text' => $request->get('condition_text'));
		$oil_chk = array('flag' => $request->get('oil_chk'), 'text' => $request->get('oil_chk_text'));
		$suspension = array('flag' => $request->get('suspension'), 'text' => $request->get('suspension_text'));
		$tool_box = array('flag' => $request->get('tool_box'), 'text' => $request->get('tool_box_text'));
		$data = VehicleReviewModel::create([
			'user_id' => $request->get('user_id'),
			'vehicle_id' => $request->get('vehicle_id'),
			'reg_no' => $request->get('reg_no'),
			'kms_outgoing' => $request->get('kms_out'),
			'kms_incoming' => $request->get('kms_in'),
			'fuel_level_out' => $request->get('fuel_out'),
			'fuel_level_in' => $request->get('fuel_in'),
			'datetime_outgoing' => $request->get('datetime_out'),
			'datetime_incoming' => $request->get('datetime_in'),
			'petrol_card' => serialize($petrol_card),
			'lights' => serialize($lights),
			'invertor' => serialize($invertor),
			'car_mats' => serialize($car_mats),
			'int_damage' => serialize($int_damage),
			'int_lights' => serialize($int_lights),
			'ext_car' => serialize($ext_car),
			'tyre' => serialize($tyre),
			'ladder' => serialize($ladder),
			'leed' => serialize($leed),
			'power_tool' => serialize($power_tool),
			'ac' => serialize($ac),
			'head_light' => serialize($head_light),
			'lock' => serialize($lock),
			'windows' => serialize($windows),
			'condition' => serialize($condition),
			'oil_chk' => serialize($oil_chk),
			'suspension' => serialize($suspension),
			'tool_box' => serialize($tool_box),
		]);
		$data->udf = serialize($request->get('udf'));
		$file = $request->file('image');
		if ($request->file('image') && $file->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$data->image = $fileName1;
		}
		$data->save();
		if (Auth::user()->user_type == "D") {
			return redirect()->route('vehicle_booking');
		}
		return redirect()->route('vehicle_reviews');
	}
		public function vehicle_booking_create() {
		// // old get vehicles before driver vehicles many-to-many
		// $data['vehicles'] = DriverLogsModel::where('driver_id', Auth::user()->id)->get();
		if (Auth::user()->user_type == "D") {
			$assign_vehicles = VehicleModel::whereIn_service("1")->whereMeta('assign_driver_id', Auth::user()->id)->pluck('id')->toArray();
			$booking_associated_vehicle_1 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Upcoming')
				->pluck('vehicle_id')->toArray();
			$booking_associated_vehicle_2 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Ongoing')
				->pluck('vehicle_id')->toArray();
			$mergedArray = array_unique(array_merge($booking_associated_vehicle_1, $booking_associated_vehicle_2, $assign_vehicles));
			$data['vehicles'] = VehicleModel::whereIn('id', $mergedArray)->get();
		} else {
			$data['vehicles'] = Auth::user()->vehicles()->with('metas')->get();
		}
		return view('vehicles.vehicle_booking_create', $data);
	}
	public function vehicle_booking_index() {
		$data['types'] = IncCats::get();
		$data['reasons'] = ReasonsModel::get();
		// dd($data);
		return view('vehicles.vehicle_booking_index', $data);
	}
public function fetch_booking_data(Request $request)
{
    if ($request->ajax()) {
        $date_format_setting = Hyvikk::get('date_format') ?? 'd-m-Y';

        $bookings = DB::table('bookings')
            ->select([
                'id',
                'pickup_addr',
                'dest_addr',
                'pickup',
                'dropoff',
                'travellers',
                'payment',
                'ride_status',
                'tax_total',
                'booking_type',
            ])
            ->orderByDesc('id');

        return DataTables::of($bookings)
            ->addColumn('check', fn($row) =>
                '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="checkbox" onclick="checkcheckbox();">'
            )

            ->editColumn('pickup_addr', fn($row) => nl2br($row->pickup_addr))
            ->editColumn('dest_addr', fn($row) => nl2br($row->dest_addr))

            ->editColumn('pickup', function ($row) use ($date_format_setting) {
                return [
                    'display' => $row->pickup ? date($date_format_setting . ' h:i A', strtotime($row->pickup)) : '',
                    'timestamp' => $row->pickup ? \Carbon\Carbon::parse($row->pickup)->timestamp : ''
                ];
            })

            ->editColumn('dropoff', function ($row) use ($date_format_setting) {
                return [
                    'display' => $row->dropoff ? date($date_format_setting . ' h:i A', strtotime($row->dropoff)) : '',
                    'timestamp' => $row->dropoff ? \Carbon\Carbon::parse($row->dropoff)->timestamp : ''
                ];
            })

            ->editColumn('payment', fn($row) =>
                $row->payment == 1
                    ? '<span class="text-success">' . __('fleet.paid1') . '</span>'
                    : '<span class="text-warning">' . __('fleet.pending') . '</span>'
            )

            ->editColumn('tax_total', fn($row) =>
                $row->tax_total ? Hyvikk::get('currency') . ' ' . $row->tax_total : ''
            )

            ->editColumn('booking_type', fn($row) =>
                $row->booking_type == 'oneway'
                    ? '<span class="badge badge-info">One Way</span>'
                    : '<span class="badge badge-secondary">Round Trip</span>'
            )

            ->addColumn('action', function ($row) {
                return '<a href="' . route('bookings.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
            })

            ->rawColumns(['check', 'pickup_addr', 'dest_addr', 'payment', 'booking_type', 'action'])
            ->make(true);
    }
}

    

	public function view_vehicle_inspection($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.view_vehicle_inspection', $data);
	}
	public function print_vehicle_inspection($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.print_vehicle_inspection', $data);
	}
	public function store_vehicle_review(VehiclReviewRequest $request) {
		$petrol_card = array('flag' => $request->get('petrol_card'), 'text' => $request->get('petrol_card_text'));
		$lights = array('flag' => $request->get('lights'), 'text' => $request->get('lights_text'));
		$invertor = array('flag' => $request->get('invertor'), 'text' => $request->get('invertor_text'));
		$car_mats = array('flag' => $request->get('car_mats'), 'text' => $request->get('car_mats_text'));
		$int_damage = array('flag' => $request->get('int_damage'), 'text' => $request->get('int_damage_text'));
		$int_lights = array('flag' => $request->get('int_lights'), 'text' => $request->get('int_lights_text'));
		$ext_car = array('flag' => $request->get('ext_car'), 'text' => $request->get('ext_car_text'));
		$tyre = array('flag' => $request->get('tyre'), 'text' => $request->get('tyre_text'));
		$ladder = array('flag' => $request->get('ladder'), 'text' => $request->get('ladder_text'));
		$leed = array('flag' => $request->get('leed'), 'text' => $request->get('leed_text'));
		$power_tool = array('flag' => $request->get('power_tool'), 'text' => $request->get('power_tool_text'));
		$ac = array('flag' => $request->get('ac'), 'text' => $request->get('ac_text'));
		$head_light = array('flag' => $request->get('head_light'), 'text' => $request->get('head_light_text'));
		$lock = array('flag' => $request->get('lock'), 'text' => $request->get('lock_text'));
		$windows = array('flag' => $request->get('windows'), 'text' => $request->get('windows_text'));
		$condition = array('flag' => $request->get('condition'), 'text' => $request->get('condition_text'));
		$oil_chk = array('flag' => $request->get('oil_chk'), 'text' => $request->get('oil_chk_text'));
		$suspension = array('flag' => $request->get('suspension'), 'text' => $request->get('suspension_text'));
		$tool_box = array('flag' => $request->get('tool_box'), 'text' => $request->get('tool_box_text'));
		$data = VehicleReviewModel::create([
			'user_id' => $request->get('user_id'),
			'vehicle_id' => $request->get('vehicle_id'),
			'reg_no' => $request->get('reg_no'),
			'kms_outgoing' => $request->get('kms_out'),
			'kms_incoming' => $request->get('kms_in'),
			'fuel_level_out' => $request->get('fuel_out'),
			'fuel_level_in' => $request->get('fuel_in'),
			'datetime_outgoing' => $request->get('datetime_out'),
			'datetime_incoming' => $request->get('datetime_in'),
			'petrol_card' => serialize($petrol_card),
			'lights' => serialize($lights),
			'invertor' => serialize($invertor),
			'car_mats' => serialize($car_mats),
			'int_damage' => serialize($int_damage),
			'int_lights' => serialize($int_lights),
			'ext_car' => serialize($ext_car),
			'tyre' => serialize($tyre),
			'ladder' => serialize($ladder),
			'leed' => serialize($leed),
			'power_tool' => serialize($power_tool),
			'ac' => serialize($ac),
			'head_light' => serialize($head_light),
			'lock' => serialize($lock),
			'windows' => serialize($windows),
			'condition' => serialize($condition),
			'oil_chk' => serialize($oil_chk),
			'suspension' => serialize($suspension),
			'tool_box' => serialize($tool_box),
		]);
		$data->udf = serialize($request->get('udf'));
		$file = $request->file('image');
		if ($request->file('image') && $file->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$data->image = $fileName1;
		}
		$data->save();
		if (Auth::user()->user_type == "D") {
			return redirect()->route('vehicle_inspection');
		}
		return redirect()->route('vehicle_reviews');
	}
	public function vehicle_review_index() {
		$data['reviews'] = VehicleReviewModel::orderBy('id', 'desc')->get();
		return view('vehicles.vehicle_review_index', $data);
	}
	public function vehicle_review_fetch_data(Request $request) {
		if ($request->ajax()) {
			$reviews = VehicleReviewModel::select('vehicle_review.*')->with('user')
				->leftJoin('vehicles', 'vehicle_review.vehicle_id', '=', 'vehicles.id')
				->leftJoin('vehicle_types', 'vehicle_types.id', '=', 'vehicles.type_id')
				->orderBy('id', 'desc');
			return DataTables::eloquent($reviews)
				->addColumn('check', function ($vehicle) {
					$tag = '<input type="checkbox" name="ids[]" value="' . $vehicle->id . '" class="checkbox" id="chk' . $vehicle->id . '" onclick=\'checkcheckbox();\'>';
					return $tag;
				})
				->editColumn('vehicle_image', function ($vehicle) {
					$src = ($vehicle->vehicle_image != null) ? asset('uploads/' . $vehicle->vehicle_image) : asset('assets/images/vehicle.jpeg');
					return '<img src="' . $src . '" height="70px" width="70px">';
				})
				->addColumn('user', function ($vehicle) {
					return ($vehicle->user->name) ?? '';
				})
				->addColumn('vehicle', function ($review) {
					return $review->vehicle->make_name . '-' . $review->vehicle->model_name . '-' . $review->vehicle->types->displayname;
				})
				->addColumn('action', function ($vehicle) {
					return view('vehicles.vehicle_review_index_list_actions', ['row' => $vehicle]);
				})
				->filterColumn('vehicle', function ($query, $keyword) {
					$query->whereRaw("CONCAT(vehicles.make_name , '-' , vehicles.model_name , '-' , vehicle_types.displayname) like ?", ["%$keyword%"]);
					return $query;
				})
				->addIndexColumn()
				->rawColumns(['vehicle_image', 'action', 'check'])
				->make(true);
			//return datatables(User::all())->toJson();
		}
	}
	public function review_edit($id) {
		// dd($id);
		$data['review'] = VehicleReviewModel::find($id);
		$user = Auth::user();
		if ($user->group_id == null || $user->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', $user->group_id)->get();
		}
		$vehicleReview = VehicleReviewModel::where('id', $id)->get()->first();
		$data['udfs'] = unserialize($vehicleReview->udf);
		return view('vehicles.vehicle_review_edit', $data);
	}
	public function update_vehicle_review(VehiclReviewRequest $request) {
		// dd($request->all());
		$petrol_card = array('flag' => $request->get('petrol_card'), 'text' => $request->get('petrol_card_text'));
		$lights = array('flag' => $request->get('lights'), 'text' => $request->get('lights_text'));
		$invertor = array('flag' => $request->get('invertor'), 'text' => $request->get('invertor_text'));
		$car_mats = array('flag' => $request->get('car_mats'), 'text' => $request->get('car_mats_text'));
		$int_damage = array('flag' => $request->get('int_damage'), 'text' => $request->get('int_damage_text'));
		$int_lights = array('flag' => $request->get('int_lights'), 'text' => $request->get('int_lights_text'));
		$ext_car = array('flag' => $request->get('ext_car'), 'text' => $request->get('ext_car_text'));
		$tyre = array('flag' => $request->get('tyre'), 'text' => $request->get('tyre_text'));
		$ladder = array('flag' => $request->get('ladder'), 'text' => $request->get('ladder_text'));
		$leed = array('flag' => $request->get('leed'), 'text' => $request->get('leed_text'));
		$power_tool = array('flag' => $request->get('power_tool'), 'text' => $request->get('power_tool_text'));
		$ac = array('flag' => $request->get('ac'), 'text' => $request->get('ac_text'));
		$head_light = array('flag' => $request->get('head_light'), 'text' => $request->get('head_light_text'));
		$lock = array('flag' => $request->get('lock'), 'text' => $request->get('lock_text'));
		$windows = array('flag' => $request->get('windows'), 'text' => $request->get('windows_text'));
		$condition = array('flag' => $request->get('condition'), 'text' => $request->get('condition_text'));
		$oil_chk = array('flag' => $request->get('oil_chk'), 'text' => $request->get('oil_chk_text'));
		$suspension = array('flag' => $request->get('suspension'), 'text' => $request->get('suspension_text'));
		$tool_box = array('flag' => $request->get('tool_box'), 'text' => $request->get('tool_box_text'));
		$review = VehicleReviewModel::find($request->get('id'));
		$review->user_id = $request->get('user_id');
		$review->vehicle_id = $request->get('vehicle_id');
		$review->reg_no = $request->get('reg_no');
		$review->kms_outgoing = $request->get('kms_out');
		$review->kms_incoming = $request->get('kms_in');
		$review->fuel_level_out = $request->get('fuel_out');
		$review->fuel_level_in = $request->get('fuel_in');
		$review->datetime_outgoing = $request->get('datetime_out');
		$review->datetime_incoming = $request->get('datetime_in');
		$review->petrol_card = serialize($petrol_card);
		$review->lights = serialize($lights);
		$review->invertor = serialize($invertor);
		$review->car_mats = serialize($car_mats);
		$review->int_damage = serialize($int_damage);
		$review->int_lights = serialize($int_lights);
		$review->ext_car = serialize($ext_car);
		$review->tyre = serialize($tyre);
		$review->ladder = serialize($ladder);
		$review->leed = serialize($leed);
		$review->power_tool = serialize($power_tool);
		$review->ac = serialize($ac);
		$review->head_light = serialize($head_light);
		$review->lock = serialize($lock);
		$review->windows = serialize($windows);
		$review->condition = serialize($condition);
		$review->oil_chk = serialize($oil_chk);
		$review->suspension = serialize($suspension);
		$review->tool_box = serialize($tool_box);
		$file = $request->file('image');
		if ($request->file('image') && $file->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$review->image = $fileName1;
		}
		$review->udf = serialize($request->get('udf'));
		$review->save();
		// return back();
		return redirect()->route('vehicle_reviews');
	}
	public function destroy_vehicle_review(Request $request) {
		VehicleReviewModel::find($request->get('id'))->delete();
		return redirect()->route('vehicle_reviews');
	}
	public function view_vehicle_review($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.view_vehicle_review', $data);
	}
	public function print_vehicle_review($id) {
		$data['review'] = VehicleReviewModel::find($id);
		return view('vehicles.print_vehicle_review', $data);
	}
	public function bulk_delete(Request $request) {
		$vehicles = VehicleModel::whereIn('id', $request->ids)->get();
		foreach ($vehicles as $vehicle) {
			if ($vehicle->drivers->count()) {
				$vehicle->drivers()->detach($vehicle->drivers->pluck('id')->toArray());
			}
			if (file_exists('./uploads/' . $vehicle->vehicle_image) && !is_dir('./uploads/' . $vehicle->vehicle_image)) {
				unlink('./uploads/' . $vehicle->vehicle_image);
			}
		}
		DriverVehicleModel::whereIn('vehicle_id', $request->ids)->delete();
		VehicleModel::whereIn('id', $request->ids)->delete();
		IncomeModel::whereIn('vehicle_id', $request->ids)->delete();
		Expense::whereIn('vehicle_id', $request->ids)->delete();
		VehicleReviewModel::whereIn('vehicle_id', $request->ids)->delete();
		ServiceReminderModel::whereIn('vehicle_id', $request->ids)->delete();
		FuelModel::whereIn('vehicle_id', $request->ids)->delete();
		return back();
	}
	public function bulk_delete_reviews(Request $request) {
		VehicleReviewModel::whereIn('id', $request->ids)->delete();
		return back();
	}
	public function enable($id) {
		$vehicle = VehicleModel::find($id);
		$vehicle->in_service = 1;
		$vehicle->save();
		return redirect()->back();
	}
	public function disable($id) {
		$vehicle = VehicleModel::find($id);
		$vehicle->in_service = 0;
		$vehicle->save();
		return redirect()->back();
	}
}
