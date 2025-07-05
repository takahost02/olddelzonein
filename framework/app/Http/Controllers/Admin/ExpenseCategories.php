<?php

/*

@copyright

Fleet Manager v7.0

Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.

Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\ExpenseCatRequest;

use App\Http\Requests\ImportRequest;

use App\Imports\ExpenseCategoriesImport;

use App\Model\ExpCats;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Validator;



class ExpenseCategories extends Controller {

	public function __construct() {

		// $this->middleware(['role:Admin']);

		$this->middleware('permission:Settings list');

	}

	public function importExpense(ImportRequest $request) {
		// Validate the uploaded file before processing
		$validator = Validator::make($request->all(), [
			'excel' => 'required|mimes:xlsx,csv|max:2048',
		]);
	
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}
	
		try {
			$file = $request->file('excel');
			$destinationPath = './uploads/xml'; // Ensure the folder exists
			$fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
	

		// Ensure the uploads directory exists and is writable
		if (!is_dir($destinationPath)) {
			mkdir($destinationPath, 0755, true); // Create directory if not exists
		}
		if (!is_writable($destinationPath)) {
			return back()->withErrors(['error' => 'The upload directory is not writable.']);
		}


			$file->move($destinationPath, $fileName);
	
			// Import the file
			Excel::import(new ExpenseCategoriesImport, $destinationPath . $fileName);
	
			return back()->with('success', 'Expenses imported successfully.');
	
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
			// Capture validation errors from Excel rows
			$failures = $e->failures();
			return back()->withErrors($failures)->withInput();
		} catch (\Exception $e) {
			return back()->withErrors(['excel' => 'Error importing file: ' . $e->getMessage()]);
		}
	}

	public function index() {

		$data['data'] = ExpCats::get();

		return view("expense.cats", $data);

	}

	public function create() {

		return view("expense.catadd");

	}

	public function destroy(Request $request) {

		ExpCats::find($request->get('id'))->expense()->delete();

		ExpCats::find($request->get('id'))->delete();

		return redirect()->route('expensecategories.index');

	}

	public function store(ExpenseCatRequest $request) {

		ExpCats::create([

			"name" => $request->get("name"),

			"user_id" => Auth::id(),

			"type" => "u",

		]);

		return redirect()->route("expensecategories.index");

	}

	public function edit(ExpCats $expensecategory) {

		return view("expense.catedit", compact("expensecategory"));

	}

	public function update(ExpenseCatRequest $request) {

		$user = ExpCats::whereId($request->get("id"))->first();

		$user->name = $request->get("name");

		$user->user_id = Auth::id();

		$user->save();

		return redirect()->route("expensecategories.index");

	}

}

