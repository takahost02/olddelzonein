<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Model\ServiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class ServiceApiController extends Controller {
	public function upload_documents(Request $request, $id) {
		$validation = Validator::make($request->all(), [
			// 'id' => 'required|integer',
			'image' => 'required|image|mimes:jpg,png,jpeg,gif',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			$service = ServiceModel::find($id);
			$file = $request->file('image');
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$destinationPath = './uploads'; // upload path
				$extension = $file->getClientOriginalExtension();
				$fileName1 = Str::uuid() . '.' . $extension;
				$file->move($destinationPath, $fileName1);
				$service->image = $fileName1;
				$service->save();
			}
			$data['success'] = "1";
			$data['message'] = "Image uploaded successfully!";
			$data['data'] = "";
		}
		return $data;
	}
	public function bulk_delete(Request $request) {
		$validation = Validator::make($request->all(), [
			'ids' => 'required|array',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			ServiceModel::whereIn('id', $request->ids)->delete();
			$data['success'] = "1";
			$data['message'] = "Records deleted successfully!";
			$data['data'] = "";
		}
		return $data;
	}
	public function delete(Request $request) {
		$validation = Validator::make($request->all(), [
			'id' => 'required|integer',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			ServiceModel::find($request->id)->delete();
			$data['success'] = "1";
			$data['message'] = "Record deleted successfully!";
			$data['data'] = "";
		}
		return $data;
	}
	public function update(Request $request) {
		$validation = Validator::make($request->all(), [
			'id' => 'required|integer',
			'name' => 'required',
			'details' => 'required|max:350',
			'image' => 'image|mimes:jpg,png,gif,jpeg',
			'designation' => 'required',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			$service = ServiceModel::find($request->id);
			$service->name = $request->name;
			$service->details = $request->details;
			$service->designation = $request->designation;
			$service->save();
			$file = $request->file('image');
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$destinationPath = './uploads'; // upload path
				$extension = $file->getClientOriginalExtension();
				$fileName1 = Str::uuid() . '.' . $extension;
				$file->move($destinationPath, $fileName1);
				$service->image = $fileName1;
				$service->save();
			}
			$data['success'] = "1";
			$data['message'] = "Service updated successfully!";
			$data['data'] = "";
		}
		return $data;
	}
	public function store(Request $request) {
		$validation = Validator::make($request->all(), [
			'name' => 'required',
			'details' => 'required|max:350',
			'image' => 'image|mimes:jpg,png,gif,jpeg',
			'designation' => 'required',
		]);
		$errors = $validation->errors();
		if (count($errors) > 0) {
			$data['success'] = "0";
			$data['message'] = implode(", ", $errors->all());
			$data['data'] = "";
		} else {
			$service = ServiceModel::create(['name' => $request->name, 'details' => $request->details, 'designation' => $request->designation]);
			$file = $request->file('image');
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$destinationPath = './uploads'; // upload path
				$extension = $file->getClientOriginalExtension();
				$fileName1 = Str::uuid() . '.' . $extension;
				$file->move($destinationPath, $fileName1);
				$service->image = $fileName1;
				$service->save();
			}
			$data['success'] = "1";
			$data['message'] = "Service added successfully!";
			$data['data'] = array('id' => $service->id);
		}
		return $data;
	}
	public function services() {
		$records = ServiceModel::orderBy('id', 'desc')->get();
		$details = array();
		foreach ($records as $row) {
			$image = asset('assets/images/no-user.jpg');
			if ($row->image != null) {
				$image = asset('uploads/' . $row->image);
			}
			$details[] = array(
				'id' => $row->id,
				'name' => $row->name,
				'details' => $row->details,
				'designation' => $row->designation,
				'image' => $image,
			);
		}
		$data['success'] = "1";
		$data['message'] = "Data fetched!";
		$data['data'] = $details;
		return $data;
	}
}
