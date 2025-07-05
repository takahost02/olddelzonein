<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\DriverAlertModel;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DriverAlertController extends Controller {

	public function index() {
		$data=DriverAlertModel::all();
		return view('driver_alert.index',compact('data'));
	}
	public function create() {
        return view('driver_alert.create');
	}
	public function store(Request $request) {
	
        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        } 

        $data=new DriverAlertModel();
        $data->name=$request->name;
        $data->save();

		return redirect(asset('/admin/driver-alert'));
	}
	public function edit($id) {
		$data=DriverAlertModel::find($id);

		return view('driver_alert.edit',compact('data'));
    
	}
	public function update(Request $request) {
		
		$validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        } 
		
		$data=DriverAlertModel::find($request->id);
        $data->name=$request->name;
        $data->save();

		return redirect(asset('/admin/driver-alert'));
	}
	public function destroy(Request $request) {
	
		DriverAlertModel::where('id', $request->id)->delete();
		return redirect(asset('/admin/driver-alert'));

	}
	public function bulk_delete(Request $request) {
		$ids=$request->ids;
		foreach($ids as $id)
		{
			$data=DriverAlertModel::where('id', $id)->delete();
		}
		return redirect(asset('/admin/driver-alert'));
	}
}
