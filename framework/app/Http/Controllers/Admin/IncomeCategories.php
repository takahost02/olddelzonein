<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\IncomeCatRequest;
use App\Imports\IncomeCategoriesImport;
use App\Model\IncCats;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class IncomeCategories extends Controller {
	public function __construct() {
		// $this->middleware(['role:Admin']);
		$this->middleware('permission:Settings list');
	}
	public function importIncome(ImportRequest $request)
	{
		$file = $request->file('excel');
	
		// Define the upload path
		$destinationPath = './uploads/xml';
		$fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
	
		
		// Ensure the uploads directory exists and is writable
		if (!is_dir($destinationPath)) {
			mkdir($destinationPath, 0755, true); // Create directory if not exists
		}
		if (!is_writable($destinationPath)) {
			return back()->withErrors(['error' => 'The upload directory is not writable.']);
		}


		$file->move($destinationPath, $fileName);
	
		$import = new IncomeCategoriesImport();
		
		try {
			Excel::import($import, $destinationPath . $fileName);
	
			// Check if there were any validation failures
			if (!empty($import->getFailures())) {
				return back()->withErrors($import->getFailures());
			}
	
			return back()->with('success', 'Income categories imported successfully.');
		} catch (\Exception $e) {
			return back()->with('error', 'Error importing file: ' . $e->getMessage());
		}
	}
	public function index(Request $request) {
		$data['data'] = IncCats::get();
		return view("income.cats", $data);
	}
	public function create() {
		return view("income.catadd");
	}
	public function destroy(Request $request) {
		IncCats::find($request->get('id'))->income()->delete();
		IncCats::find($request->get('id'))->delete();
		return redirect()->route('incomecategories.index');
	}
	public function store(IncomeCatRequest $request) {
		IncCats::create([
			"name" => $request->get("name"),
			"user_id" => Auth::id(),
			"type" => "u",
		]);
		return redirect()->route("incomecategories.index");
	}
	public function edit(IncCats $incomecategory) {
		return view("income.catedit", compact("incomecategory"));
	}
	public function update(IncomeCatRequest $request) {
		$user = IncCats::whereId($request->get("id"))->first();
		$user->name = $request->get("name");
		$user->user_id = Auth::id();
		$user->save();
		return redirect()->route("incomecategories.index");
	}
}
