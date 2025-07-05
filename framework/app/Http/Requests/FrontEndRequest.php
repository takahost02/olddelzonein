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

class FrontEndRequest extends FormRequest {

	public function authorize() {
		if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
			return true;
		} else {
			return false;
		}
	}

	public function rules() {
		return [
			'about' => 'required|max:130',
			'customer_support' => 'required',
			'phone' => 'required|numeric',
			'email' => 'required',
			'about_description' => 'required',
			'about_title' => 'required',
			'faq_link' => 'nullable|url',
			'cancellation' => 'nullable|url',
			'terms' => 'nullable|url',
			'privacy_policy' => 'nullable|url',
			'sign_up_title' => 'string|max:50',
            'sign_up_sub_title' => 'string|max:80',
            'signup_file.*' => 'mimes:jpeg,jpg,png,svg,webp|dimentions:max_width=103,max_height=104|max:1024',
            'signup_title.*' => 'required|max:50',
            'signup_subtitle.*' => 'required|max:100',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
			'signup_file.*'=>'required|image|dimensions:width=103,height=104'
		];
	}
}
