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

class SettingsRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
			return true;
		} else {
			abort(404);
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'name.*' => 'required',
			'name.country' => 'alpha',
			'name.invoice_text' => 'nullable',
			'name.vehicle_interval' => 'required|numeric|min:0|max:1440',
			'name.driver_interval' => 'required|numeric|min:0|max:1440',
			'icon_img' => 'mimes:jpg,png,gif,jpeg|max:5120',
			'logo_img' => 'mimes:jpg,png,gif,jpeg|max:5120|dimensions:width=172,height=76',
			'footer_logo_img' => 'mimes:jpg,png,gif,jpeg|max:5120|dimensions:width=50,height=68'
		];
	}

	public function messages() {
		return [
			'name.vehicle_interval.max' => 'The Vehicle Interval must not be greater than 1440 Minutes.',
			'name.vehicle_interval.min' => 'The Vehicle Interval must not be less than 0.',
			'name.driver_interval.max' => 'The Driver Interval must not be greater than 1440 Minutes.',
			'name.driver_interval.min' => 'The Driver Interval must not be less than 0.',
			'name.vehicle_interval.required' => 'Vehicle Interval is required.',
			'name.driver_interval.required' => 'Driver Interval is required.',
			'name.vehicle_interval.numeric' => 'Vehicle Interval must be a number.',            
			'name.driver_interval.numeric' => 'Driver Interval must be a number.',

			// Image validation messages
			'icon_img.mimes' => 'The Icon Image must be a file of type: jpg, png, gif, jpeg.',
			'icon_img.max' => 'The Icon Image must not be larger than 5MB.',
			
			'logo_img.mimes' => 'The Logo Image must be a file of type: jpg, png, gif, jpeg.',
			'logo_img.max' => 'The Logo Image must not be larger than 5MB.',
			'logo_img.dimensions' => 'The Logo Image must be exactly 172px wide and 76px high.',

			'footer_logo_img.mimes' => 'The Footer Logo Image must be a file of type: jpg, png, gif, jpeg.',
			'footer_logo_img.max' => 'The Footer Logo Image must not be larger than 5MB.',
			'footer_logo_img.dimensions' => 'The Footer Logo Image must be exactly 50px wide and 68px high.'
		];
	}
}
