<?php

/*
@copyright

Fleet Manager v7.0

Copyright (C) 2017-2023 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest {

    public function authorize() {
            return true;  // No restriction, allow all users
        }

	public function rules() {
		// dd($this->request->get("_method"));
		if($this->request->get("_method") == 'PATCH'){
			return [
				'make_name' => 'required',
				'model_name' => 'required',
				'year' => 'required|numeric|digits:4',
				'engine_type' => 'required',
				'horse_power' => 'integer',
				'color_name' => 'required',
				'lic_exp_date' => 'required|date|date_format:Y-m-d',
				'reg_exp_date' => 'required|date|date_format:Y-m-d',
				'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
				'int_mileage' => 'required|alpha_num',
				'vehicle_image' => 'nullable|mimes:jpg,png,jpeg|max:5120|dimensions:width=630,height=420',
                'icon' => 'nullable|mimes:jpg,png,jpeg|max:5120|dimensions:min_width=83,max_height=107',
				'average' => 'numeric',
				'type_id' => 'required|integer',
				'traccar_device_id' => 'nullable',
			];
		}
		else{
			return [
				'make_name' => 'required',
				'model_name' => 'required',
				'year' => 'required|numeric|digits:4',
				'engine_type' => 'required',
				'horse_power' => 'integer',
				'color_name' => 'required',
				'lic_exp_date' => 'required|date|date_format:Y-m-d|after:'. date('Y-m-d'),
				'reg_exp_date' => 'required|date|date_format:Y-m-d',
				'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
				'int_mileage' => 'required|alpha_num',
				'vehicle_image' => 'nullable|mimes:jpg,png,jpeg|max:5120|dimensions:width=630,height=420',
				'icon' => 'nullable|mimes:jpg,png,jpeg|max:5120|dimensions:ratio=5/7',
				'average' => 'numeric',
				'type_id' => 'required|integer',
				'traccar_device_id' => 'nullable',
			];
		}
	}
}
