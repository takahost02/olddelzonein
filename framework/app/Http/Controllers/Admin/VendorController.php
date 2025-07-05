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

use App\Http\Requests\VendorRequest;

use App\Imports\VendorImport;

use App\Model\Hyvikk;

use App\Model\Vendor;

use DataTables;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Validators\ValidationException;


class VendorController extends Controller {

	public function __construct() {

		// $this->middleware(['role:Admin']);

		$this->middleware('permission:Vendors add', ['only' => ['create']]);

		$this->middleware('permission:Vendors edit', ['only' => ['edit']]);

		$this->middleware('permission:Vendors delete', ['only' => ['bulk_delete', 'destroy']]);

		$this->middleware('permission:Vendors list');

		$this->middleware('permission:Vendors import', ['only' => ['importVendors']]);

	}

	public function importVendors(Request $request) 
	{
		// Validate file input
		$validator = Validator::make($request->all(), [
			'excel' => 'required|mimes:xlsx,csv|max:2048', // Allow only Excel files up to 2MB
		]);
	
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}
	
		try {
			$file = $request->file('excel'); // Get the uploaded file
			$destinationPath = './uploads/xml'; // Define upload path
			$fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();


			// Ensure the uploads directory exists and is writable
			if (!is_dir($destinationPath)) {
				mkdir($destinationPath, 0755, true); // Create directory if not exists
			}
			if (!is_writable($destinationPath)) {
				return back()->withErrors(['error' => 'The upload directory is not writable.']);
			}
	
			$file->move($destinationPath, $fileName); // Move file to destination
	
			// Import file with validation
			Excel::import(new VendorImport, $destinationPath . $fileName);
	
			return back()->with('success', 'Vendors imported successfully.');
		} catch (ValidationException $e) {
			$failures = $e->failures();
	
			$errors = [];
			foreach ($failures as $failure) {
				$errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
			}
	
			return back()->withErrors(['error' => 'Import failed due to validation errors.', 'details' => $errors]);
		} catch (\Exception $e) {
			
			return back()->withErrors(['error' => 'Vendor import failed. Please check the file format and try again.']);
		}
	}

	public function index() {

		return view('vendors.index');

	}

	public function fetch_data(Request $request) {

		if ($request->ajax()) {

			$vendors = Vendor::select('*');

			//orderBy('id',$request->order[0]['dir']);

			$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';

			return DataTables::eloquent($vendors)

				->addColumn('check', function ($vendor) {

					$tag = '<input type="checkbox" name="ids[]" value="' . $vendor->id . '" class="checkbox" id="chk' . $vendor->id . '" onclick=\'checkcheckbox();\'>';

					return $tag;

				})

				->editColumn('photo', function ($vendor) {

					$src = ($vendor->photo != null) ? asset('uploads/' . $vendor->photo) : asset('assets/images/no-user.jpg');

					return '<img src="' . $src . '" height="70px" width="70px">';

				})

				->addColumn('id', function ($vendor) {

					return $vendor->id;

				})

				->addColumn('address', function ($vendor) {

					$add = '';

					$add .= $vendor->address1 . "<br/>";

					$add .= $vendor->address2 . "&nbsp";

					$add .= $vendor->city . "&nbsp";

					if ($vendor->postal_code) {

						$add .= ", " . $vendor->postal_code . "&nbsp;";

					}

					$add .= $vendor->province . "&nbsp;";

					$add .= $vendor->country . "&nbsp;";

					return $add;

				})

				->editColumn('created_at', function ($vendor) use ($date_format_setting) {

					return date($date_format_setting . ' g:i A', strtotime($vendor->created_at));

				})

				->addColumn('action', function ($vendor) {

					return view('vendors.list-actions', ['row' => $vendor]);

				})

				->rawColumns(['photo', 'action', 'check', 'address'])

				->addIndexColumn()

				->make(true);

			//return datatables(User::all())->toJson();

		}

	}

	public function create() {

		$vendor_types = Vendor::orderBy("name")->groupBy("type")->get()->pluck("type")->toArray();

		array_push($vendor_types, __("fleet.machinaries"), __("fleet.fuel"), __("fleet.parts"), __("fleet.add_new"));

		$vendor_types = array_unique($vendor_types);

		return view('vendors.create', compact("vendor_types"));

	}

	private function upload_file($file, $field, $id) {

		$destinationPath = './uploads'; // upload path

		$extension = $file->getClientOriginalExtension();

		$fileName1 = Str::uuid() . '.' . $extension;

		$file->move($destinationPath, $fileName1);

		$x = Vendor::find($id)->update([$field => $fileName1]);

	}

	public function store(VendorRequest $request) {

		$form_data = $request->all();

		unset($form_data['photo']);

		unset($form_data['udf']);

		$id = Vendor::create($form_data)->id;

		$vendor = Vendor::find($id);

		$vendor->udf = serialize($request->get('udf'));

		$vendor->save();

		if ($request->file('photo') && $request->file('photo')->isValid()) {

			$this->upload_file($request->file('photo'), "photo", $id);

		}

		return redirect()->route('vendors.index');

	}

	public function edit($id) {

		$index['data'] = Vendor::whereId($id)->first();

		$vendor_types = Vendor::orderBy("name")->groupBy("type")->get()->pluck("type")->toArray();

		array_push($vendor_types, "Machinaries", __("fleet.fuel"), __("fleet.parts"));

		$index['vendor_types'] = array_unique($vendor_types);

		$index['udfs'] = unserialize($index['data']->udf);

		return view("vendors.edit", $index);

	}

	public function update(VendorRequest $request) {

		$vendor = $request->get('id');

		$vendor = Vendor::find($request->get("id"));

		$vendor->name = $request->get('name');

		$vendor->type = $request->get('type');

		$vendor->website = $request->get('website');

		$vendor->note = $request->get('note');

		$vendor->phone = $request->get('phone');

		$vendor->address1 = $request->get('address1');

		$vendor->address2 = $request->get('address2');

		$vendor->city = $request->get('city');

		$vendor->province = $request->get('province');

		$vendor->email = $request->get('email');

		// $vendor->photo = $request->get('photo');

		$vendor->country = $request->country;

		$vendor->postal_code = $request->postal_code;

		$vendor->udf = serialize($request->get('udf'));

		$vendor->save();

		if ($request->file('photo') && $request->file('photo')->isValid()) {

			if (file_exists('./uploads/' . $vendor->photo) && !is_dir('./uploads/' . $vendor->photo)) {

				unlink('./uploads/' . $vendor->photo);

			}

			$this->upload_file($request->file('photo'), "photo", $vendor->id);

		}

		return redirect()->route('vendors.index');

	}

	public function destroy(Request $request) {

		$vendor = Vendor::find($request->get('id'));

		if (file_exists('./uploads/' . $vendor->photo) && !is_dir('./uploads/' . $vendor->photo)) {

			unlink('./uploads/' . $vendor->photo);

		}

		$vendor->delete();

		return redirect()->route('vendors.index');

	}

	public function bulk_delete(Request $request) {

		$vendors = Vendor::whereIn('id', $request->ids)->get();

		foreach ($vendors as $vendor) {

			if (file_exists('./uploads/' . $vendor->photo) && !is_dir('./uploads/' . $vendor->photo)) {

				unlink('./uploads/' . $vendor->photo);

			}

			$vendor->delete();

		}

		return back();

	}

}

