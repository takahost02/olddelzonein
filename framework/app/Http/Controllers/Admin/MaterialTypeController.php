<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialTypeRequest;
use App\Model\FareSettings;
use App\Model\MaterialTypeModel;
use DataTables;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller {
	public function __construct() {
		// $this->middleware(['role:Admin']);
// 		$this->middleware('permission:MaterialType add', ['only' => ['create']]);
// 		$this->middleware('permission:MaterialType edit', ['only' => ['edit']]);
// 		$this->middleware('permission:MaterialType delete', ['only' => ['bulk_delete', 'destroy']]);
// 		$this->middleware('permission:MaterialType list');
	}
	public function index() {
		$index['data'] = MaterialTypeModel::get();
		return view('material_types.index', $index);
	}
	public function fetch_data(Request $request) {
		if ($request->ajax()) {
			$material_types = MaterialTypeModel::query();
			return DataTables::eloquent($material_types)
				->addColumn('check', function ($material) {
					$tag = '<input type="checkbox" name="ids[]" value="' . $material->id . '" class="checkbox" id="chk' . $material->id . '" onclick=\'checkcheckbox();\'>';
					return $tag;
				})
				->editColumn('icon', function ($material) {
					$src = ($material->icon != null) ? asset('uploads/' . $material->icon) : asset('assets/images/vehicle.jpeg');
					return '<img src="' . $src . '" height="70px" width="70px">';
				})
				->addColumn('isenable', function ($material) {
					return ($material->isenable) ? "YES" : "NO";
				})
				->filterColumn('isenable', function ($query, $keyword) {
					$query->whereRaw("IF(isenable = 1, 'YES', 'NO') like ?", ["%{$keyword}%"]);
				})
				->addColumn('action', function ($material) {
					return view('material_types.list-actions', ['row' => $material]);
				})
				->addIndexColumn()
				->rawColumns(['icon', 'action', 'check'])
				->make(true);
			//return datatables(User::all())->toJson();
		}
	}
	public function create() {
		return view('material_types.create');
	}
	public function store(MaterialTypeRequest $request) {
		if ($request->isenable == 1) {
			$enable = 1;
		} else {
			$enable = 0;
		}
		$new = MaterialTypeModel::create([
			'materialtype' => $request->materialtype,
			'displayname' => $request->displayname,
			'isenable' => $enable,
		
		]);
		$file = $request->file('icon');
		if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = 'material_type_' . time() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$new->icon = $fileName1;
			$new->save();
		}
		$key = $request->get('materialtype');
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_base_fare', 'key_value' => '500', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_base_km', 'key_value' => '10', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_base_time', 'key_value' => '2', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_std_fare', 'key_value' => '20', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_weekend_base_fare', 'key_value' => '500', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_weekend_base_km', 'key_value' => '10', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_weekend_wait_time', 'key_value' => '2', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_weekend_std_fare', 'key_value' => '20', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_night_base_fare', 'key_value' => '500', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_night_base_km', 'key_value' => '10', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_night_wait_time', 'key_value' => '2', 'type_id' => $new->id]);
		FareSettings::create(['key_name' => strtolower(str_replace(" ", "", $key)) . '_night_std_fare', 'key_value' => '20', 'type_id' => $new->id]);
		return redirect()->route('material-types.index');
	}
	public function edit($id) {
		$data['material_type'] = MaterialTypeModel::find($id);
		return view('material_types.edit', $data);
	}
	public function update(MaterialTypeRequest $request) {
		if ($request->isenable == 1) {
			$enable = 1;
		} else {
			$enable = 0;
		}
		$data = MaterialTypeModel::find($request->get('id'));
		$data->update([
			'materialtype' => $request->materialtype,
			'displayname' => $request->displayname,
			'isenable' => $enable,
		
		]);
		$file = $request->file('icon');
		if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
			$destinationPath = './uploads'; // upload path
			$extension = $file->getClientOriginalExtension();
			$fileName1 = 'material_type_' . time() . '.' . $extension;
			$file->move($destinationPath, $fileName1);
			$data->icon = $fileName1;
			$data->save();
		}
		$settings = FareSettings::where('type_id', $request->get('id'))->get();
		// dd($settings);
		foreach ($settings as $key) {
			// echo "old  " . $key->key_name . "  === ";
			// echo "new " . str_replace($request->get('old_type'), strtolower(str_replace(' ', '', $request->get('type'))), $key->key_name) . "<br>";
			// update key_name in fare settings
			$key->key_name = str_replace($request->get('old_type'), strtolower(str_replace(' ', '', $request->get('materialtype'))), $key->key_name);
			$key->save();
		}
		return redirect()->route('material-types.index');
	}
	public function destroy(Request $request) {
		MaterialTypeModel::find($request->get('id'))->delete();
		return redirect()->route('material-types.index');
	}
	public function bulk_delete(Request $request) {
		MaterialTypeModel::whereIn('id', $request->ids)->delete();
		return back();
	}
}
