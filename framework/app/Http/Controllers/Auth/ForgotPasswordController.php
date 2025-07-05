<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Auth;
use App;
use App\Http\Controllers\Controller;
use Hyvikk;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller {
	use SendsPasswordResetEmails;
	public function __construct() {
		App::setLocale(Hyvikk::get('language'));
		$this->middleware('guest');
	}
}
