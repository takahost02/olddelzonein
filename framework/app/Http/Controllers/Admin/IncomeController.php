<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncRequest;
use App\Model\Bookings;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\VehicleModel;
use Auth;
use DB;
use Illuminate\Http\Request;

class IncomeController extends Controller {
	public function __construct() {
		$this->middleware('permission:Transactions add', ['only' => ['store']]);
		$this->middleware('permission:Transactions edit', ['only' => ['edit']]);
		$this->middleware('permission:Transactions delete', ['only' => ['bulk_delete', 'destroy']]);
		$this->middleware('permission:Transactions list');
	}
	public function index() {
		$date1 = date('Y-m-01');
		$date2 = date('Y-m-t');
		$data['date1'] = null;
		$data['date2'] = null;
		$user = Auth::user();
		if ($user->user_type == "D") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
			// $data['vehicels'] = VehicleModel::whereIn_service(1)->get();
			$assign_vehicles = VehicleModel::whereIn_service("1")->whereMeta('assign_driver_id', Auth::user()->id)->pluck('id')->toArray();
			$booking_associated_vehicle_1 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Upcoming')
				->pluck('vehicle_id')->toArray();
			$booking_associated_vehicle_2 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Ongoing')
				->pluck('vehicle_id')->toArray();
			$mergedArray = array_unique(array_merge($booking_associated_vehicle_1, $booking_associated_vehicle_2, $assign_vehicles));
			$data['vehicels'] = VehicleModel::whereIn('id', $mergedArray)->get();
		} else {
			if ($user->group_id == null || $user->user_type == "S") {
				$data['vehicels'] = VehicleModel::whereIn_service(1)->get();
				$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
			} else {
				$data['vehicels'] = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->get();
				$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
			}
		}
		$data['types'] = IncCats::get();
		if (Auth::user()->user_type == "D") {
			$income = IncomeModel::with(['category'])->where('user_id', Auth::user()->id)->whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$date1, $date2]);
			$data['today'] = $income->get();
			$data['total'] = $income->sum('amount');
		} else {
			$income = IncomeModel::with(['category'])->whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$date1, $date2]);
			$data['today'] = $income->get();
			$data['total'] = $income->sum('amount');
		}
		return view("income.index", $data);
	}
	public function store(IncRequest $request) {
		IncomeModel::create([
			"vehicle_id" => $request->get("vehicle_id"),
			// "amount" => $request->get("revenue"),
			"amount" => $request->get("tax_total"),
			"user_id" => Auth::id(),
			"date" => $request->get('date'),
			"mileage" => $request->get("mileage"),
			"income_cat" => $request->get("income_type"),
			"tax_percent" => $request->tax_percent,
			"tax_charge_rs" => $request->tax_charge_rs,
		]);
		$v = VehicleModel::find($request->get("vehicle_id"));
		$v->mileage = $request->get("mileage");
		$v->save();
		return redirect()->route("income.index");
	}
	public function destroy(Request $request) {
		$date1 = date('Y-m-01');
		$date2 = date('Y-m-t');
		IncomeModel::find($request->get('id'))->delete();
		$user = Auth::user();
		if ($user->user_type == "D") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
			$data['vehicels'] = VehicleModel::whereIn_service(1)->get();
		} else {
			if ($user->group_id == null || $user->user_type == "S") {
				$vehicle_ids = VehicleModel::with(['metas'])->whereIn_service(1)->pluck('id')->toArray();
			} else {
				$vehicle_ids = VehicleModel::with(['metas'])->whereIn_service(1)->where('group_id', $user->group_id)->pluck('id')->toArray();
			}
		}
		$income = IncomeModel::where('user_id', Auth::user()->id)->whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$date1, $date2]);
		$data['today'] = $income->get();
		$data['total'] = $income->sum('amount');
		// dd($data);
		return view("income.ajax_income", $data);
		// return redirect()->route('income.index');
	}
	public function income_records(Request $request) {
		$data['date1'] = $request->date1;
		$data['date2'] = $request->date2;
		$user = Auth::user();
		if ($user->user_type == "D") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
			//	$data['vehicels'] = VehicleModel::whereIn_service(1)->with('metas')->get();
			$assign_vehicles = VehicleModel::whereIn_service("1")->whereMeta('assign_driver_id', Auth::user()->id)->pluck('id')->toArray();
			$booking_associated_vehicle_1 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Upcoming')
				->pluck('vehicle_id')->toArray();
			$booking_associated_vehicle_2 = Bookings::where('driver_id', Auth::user()->id)
				->whereMeta('ride_status', 'Ongoing')
				->pluck('vehicle_id')->toArray();
			$mergedArray = array_unique(array_merge($booking_associated_vehicle_1, $booking_associated_vehicle_2, $assign_vehicles));
			$data['vehicels'] = VehicleModel::whereIn('id', $mergedArray)->get();
		} else {
			if ($user->group_id == null || $user->user_type == "S") {
				$data['vehicels'] = VehicleModel::with(['metas'])
					->whereIn_service(1)->get();
				$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
			} else {
				$data['vehicels'] = VehicleModel::with(['metas'])
					->whereIn_service(1)->where('group_id', $user->group_id)->get();
				$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
			}
		}
		$data['types'] = IncCats::get();
		$data['today'] = IncomeModel::with(['category'])->where('user_id', Auth::user()->id)->whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$request->get('date1'), $request->get('date2')])->get();
		$data['total'] = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');
		return view("income.index", $data);
	}
	public function bulk_delete(Request $request) {
		IncomeModel::whereIn('id', $request->ids)->delete();
		return redirect('admin/income');
	}
}
