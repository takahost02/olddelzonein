<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller {
	public function __construct() {
		// $this->middleware(['role:Admin']);
		$this->middleware('permission:Mechanics add', ['only' => ['create']]);
		$this->middleware('permission:Mechanics edit', ['only' => ['edit']]);
		$this->middleware('permission:Mechanics delete', ['only' => ['bulk_delete', 'destroy']]);
		$this->middleware('permission:Mechanics list');
	}
	public function index() {
		$index['data'] = Mechanic::orderBy('id', 'desc')->get();
		return view('mechanics.index', $index);
	}
	public function create() {
		return view('mechanics.create');
	}
	public function store(Request $request) {
		$form_data = $request->all();
		$id = Mechanic::create($form_data)->id;
		return redirect()->route('mechanic.index');
	}
	public function edit($id) {
		$index['data'] = Mechanic::whereId($id)->first();
		return view("mechanics.edit", $index);
	}
	public function update(Request $request, $id) {
		$mechanic = $request->get('id');
		$mechanic = Mechanic::find($request->get("id"));
		$mechanic->name = $request->get('name');
		$mechanic->email = $request->get('email');
		$mechanic->contact_number = $request->get('contact_number');
		$mechanic->category = $request->get('category');
		$mechanic->save();
		return redirect()->route('mechanic.index');
	}
	public function destroy(Request $request) {
		Mechanic::find($request->get('id'))->delete();
		return redirect()->route('mechanic.index');
	}
	public function bulk_delete(Request $request) {
		Mechanic::whereIn('id', $request->ids)->delete();
		return back();
	}
}
