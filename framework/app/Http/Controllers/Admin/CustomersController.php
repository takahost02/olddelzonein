<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers as CustomerRequest;
use App\Http\Requests\ImportRequest;
use App\Imports\CustomerImport;
use App\Model\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

use App\Traits\FirebasePassword;

use Illuminate\Support\Facades\Session;

use Maatwebsite\Excel\Validators\ValidationException;


class CustomersController extends Controller {

	use FirebasePassword;

	public function __construct() {
		// $this->middleware(['role:Admin']);
		$this->middleware('permission:Customer add', ['only' => ['create']]);
		$this->middleware('permission:Customer edit', ['only' => ['edit']]);
		$this->middleware('permission:Customer delete', ['only' => ['bulk_delete', 'destroy']]);
		$this->middleware('permission:Customer list');
		$this->middleware('permission:Customer import', ['only' => ['importCutomers']]);
	}
	public function importCutomers(ImportRequest $request) {
		try {
			$file = $request->file('excel');
			$destinationPath = './uploads/xml'; // Upload path
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
			Excel::import(new CustomerImport, $destinationPath . $fileName);
	
			return back()->with('success', 'Customers imported successfully.');
		} catch (ValidationException $e) {
			$failures = $e->failures();
			$errorMessages = [];
	
			foreach ($failures as $failure) {
				$errorMessages[] = "Row " . $failure->row() . ": " . implode(", ", $failure->errors());
			}
	
			return back()->withErrors($errorMessages);
		} catch (\Exception $e) {
			return back()->withErrors(['error' => 'An error occurred while importing Customers.']);
		}
	}
	public function index() {
		return view("customers.index");
	}
	public function fetch_data(Request $request) {
		if ($request->ajax()) {
			$users = User::select('users.*')->with(['user_data'])->whereUser_type("C")->orderBy('users.id', 'desc')->groupBy('users.id');
			return DataTables::eloquent($users)
				->addColumn('check', function ($user) {
					$tag = '<input type="checkbox" name="ids[]" value="' . $user->id . '" class="checkbox" id="chk' . $user->id . '" onclick=\'checkcheckbox();\'>';
					return $tag;
				})
				->addColumn('mobno', function ($user) {
					return $user->getMeta('mobno');
				})
				->editColumn('name', function ($user) {
					return "<a href=" . route('customers.show', $user->id) . ">$user->name</a>";
				})
				->addColumn('gender', function ($user) {
					return ($user->getMeta('gender')) ? "Male" : "Female";
				})
				->addColumn('address', function ($user) {
					return $user->getMeta('address');
				})
				->addColumn('action', function ($user) {
					return view('customers.list-actions', ['row' => $user]);
				})
				->rawColumns(['action', 'check', 'name'])
				->make(true);
		}
	}
	public function create() {
		return view("customers.create");
	}
	public function store(CustomerRequest $request) {
		$id = User::create([
			"name" => $request->get("first_name") . " " . $request->get("last_name"),
			"email" => $request->get("email"),
			"password" => bcrypt("password"),
			"user_type" => "C",
			"api_token" => str_random(60),
		])->id;
		$user = User::find($id);
		$user->user_id = Auth::user()->id;
		$user->first_name = $request->get("first_name");
		$user->last_name = $request->get("last_name");
		$user->address = $request->get("address");
		$user->mobno = $request->get("phone");
		$user->gender = $request->get('gender');
		$user->save();
		$user->givePermissionTo(['Bookings add', 'Bookings edit', 'Bookings list', 'Bookings delete']);
		return redirect()->route("customers.index");
	}
	public function ajax_store(Request $request) {
		$v = Validator::make($request->all(), [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'unique:users,email',
			'phone' => 'required|numeric|digits_between:7,15',
			'gender' => 'required',
			'address' => 'required',
		]);
		if ($v->fails()) {
			$d = ['error' => 'true', 'messages' => $v->errors()];
		} else {
			$id = User::create([
				"name" => $request->get("first_name") . " " . $request->get("last_name"),
				"email" => $request->get("email"),
				"password" => bcrypt("password"),
				"user_type" => "C",
				"api_token" => str_random(60),
			])->id;
			$user = User::find($id);
			$user->first_name = $request->get("first_name");
			$user->last_name = $request->get("last_name");
			$user->address = $request->get("address");
			$user->mobno = $request->get("phone");
			$user->gender = $request->get('gender');
			$user->save();
			$user->givePermissionTo(['Bookings add', 'Bookings edit', 'Bookings list', 'Bookings delete']);
			$d = User::whereUser_type("C")->get(["id", "name as text"]);
		}
		return $d;
	}
	public function show($id) {
		$index['customer'] = User::find($id);
		return view('customers.show', $index);
	}
	public function destroy(Request $request) {


		$u=User::find($request->get('id'));

		$this->deleteUser($u->email);

		User::find($request->get('id'))->user_data()->delete();
		$user = User::find($request->get('id'));
		$user->update([
			'email' => time() . "_deleted" . $user->email,
		]);
		$user->delete();
		return redirect()->route('customers.index');
	}
	public function edit($id) {
		$index['data'] = User::whereId($id)->first();
		return view("customers.edit", $index);
	}
	public function update(CustomerRequest $request) {
		$user = User::find($request->id);
		$user->name = $request->get("first_name") . " " . $request->get("last_name");
		$user->email = $request->get('email');
		$user->first_name = $request->get("first_name");
		$user->last_name = $request->get("last_name");
		$user->address = $request->get("address");
		$user->mobno = $request->get("phone");
		$user->gender = $request->get('gender');
		$user->save();
		return redirect()->route("customers.index");
	}
	public function bulk_delete(Request $request) {
		$users = User::whereIn('id', $request->ids)->get();
		foreach ($users as $user) {
			$this->deleteUser($user->email);

			$user->update([
				'email' => time() . "_deleted" . $user->email,
			]);
			$user->delete();
		}
		return back();
	}
}
