<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Model\ServiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller {
	public function __construct() {
    //  $this->middleware(['role:Admin']);
// 		$this->middleware('permission:Service add', ['only' => ['create']]);
// 		$this->middleware('permission:Service edit', ['only' => ['edit']]);
// 		$this->middleware('permission:Service delete', ['only' => ['bulk_delete', 'destroy']]);
// 		$this->middleware('permission:Service list');
	}
	public function index() {
		$data = ServiceModel::orderBy('id', 'desc')->get();
		return view('service.index', compact('data'));
	}
	public function create() {
		return view('service.create');
	}
	public function store(serviceRequest $request) {
		$data = ServiceModel::create(['name' => $request->name, 'details' => $request->details, 'designation' => $request->designation]);
		$file = $request->file('image');
		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$data->image = $fileName1;
			$data->save();
		}
		return redirect('admin/service');
	}
	public function edit($id) {
		$data = ServiceModel::find($id);
		return view('service.edit', compact('data'));
	}
	public function update(ServiceRequest $request) {
		$data = ServiceModel::find($request->id);
		$data->name = $request->name;
		$data->details = $request->details;
		$data->designation = $request->designation;
		$data->save();
		$file = $request->file('image');
		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = Str::uuid() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$data->image = $fileName1;
			$data->save();
		}
		return redirect('admin/service');
	}
	public function destroy(Request $request) {
		ServiceModel::find($request->id)->delete();
		return redirect('admin/service');
	}
	public function bulk_delete(Request $request) {
		ServiceModel::whereIn('id', $request->ids)->delete();
		return back();
	}
}
