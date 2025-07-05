<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\ChatSettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MessageSettingsController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index() {
		return view("utilities.chat_settings");
	}
	public function store(Request $request) {
		ChatSettingsModel::where('name', 'pusher_app_id')->update(['value' => $request->pusher_app_id]);
		ChatSettingsModel::where('name', 'pusher_app_key')->update(['value' => $request->pusher_app_key]);
		ChatSettingsModel::where('name', 'pusher_app_secret')->update(['value' => $request->pusher_app_secret]);
		ChatSettingsModel::where('name', 'pusher_app_cluster')->update(['value' => $request->pusher_app_cluster]);
		$this->setEnvValue('PUSHER_APP_ID', $request->pusher_app_id ?? "");
		$this->setEnvValue('PUSHER_APP_KEY', $request->pusher_app_key ?? "");
		$this->setEnvValue('PUSHER_APP_SECRET', $request->pusher_app_secret ?? "");
		$this->setEnvValue('PUSHER_APP_CLUSTER', $request->pusher_app_cluster ?? "");
		return back()->with(['msg' => __('fleet.chat_settingsUpdated')]);
	}
	protected function setEnvValue(string $key, string $value) {
		$path = app()->environmentFilePath();
		$env = file_get_contents($path);
		$old_value = env($key);
		if (!str_contains($env, $key . '=')) {
			$env .= sprintf("%s=%s\n", $key, $value);
		} else if ($old_value) {
			$env = str_replace(sprintf('%s=%s', $key, $old_value), sprintf('%s=%s', $key, $value), $env);
		} else {
			$env = str_replace(sprintf('%s=', $key), sprintf('%s=%s', $key, $value), $env);
		}
		if (file_exists(App::getCachedConfigPath())) {
			Artisan::call("config:cache");
		}
		file_put_contents($path, $env);
	}
}
