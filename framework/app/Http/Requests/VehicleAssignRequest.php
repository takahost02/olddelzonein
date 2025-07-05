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

class VehicleAssignRequest extends FormRequest {

    public function authorize() {
            return true;  // No restriction, allow all users
        }

	public function rules() {
		// dd($this->request->get("_method"));
		if($this->request->get("_method") == 'PATCH'){
			return [
				'make_name' => 'required',
				'model_name' => 'required',
				
				'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
				
				'type_id' => 'required|integer',
			
			];
		}
		else{
			return [
				'make_name' => 'required',
				'model_name' => 'required',
			
				'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
				
				'type_id' => 'required|integer',
			
			];
		}
	}
}
