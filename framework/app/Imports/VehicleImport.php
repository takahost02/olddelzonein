<?php

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use App\Model\VehicleModel;
use Auth;

class VehicleImport implements ToModel, WithHeadingRow
{
    public function model(array $vehicle)
    {
        $validator = Validator::make($vehicle, [
            'vehicle_maker' => 'required|max:255',
            'vehicle_model' => 'required|max:255',
            'vehicle_year' => 'required|integer|min:1900|max:' . date('Y'),
            'initial_mileage' => 'nullable|integer|min:0',
            'registration_expiry_date' => 'nullable|date',
            'vehicle_engine_type' => 'nullable|max:255',
            'vehicle_horse_power' => 'nullable|integer|min:0',
            'color' => 'nullable|max:255',
            'vin' => 'nullable|max:255',
            'license_plate' => 'required|max:255',
            'license_expiry_date' => 'nullable|date',
            'insurance_number' => 'nullable|max:255',
            'insurance_expiration_date' => 'nullable|date',
            'averagempg' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new \Exception("Validation error: " . implode(", ", $validator->errors()->all()));
        }

        $vehicleModel = VehicleModel::create([
            'make' => $vehicle['vehicle_maker'],
            'model' => $vehicle['vehicle_model'],
            'year' => $vehicle['vehicle_year'],
            'int_mileage' => $vehicle['initial_mileage'],
            'reg_exp_date' => date('Y-m-d', strtotime($vehicle['registration_expiry_date'])),
            'engine_type' => $vehicle['vehicle_engine_type'],
            'horse_power' => $vehicle['vehicle_horse_power'],
            'color' => $vehicle['color'],
            'vin' => $vehicle['vin'],
            'license_plate' => $vehicle['license_plate'],
            'lic_exp_date' => date('Y-m-d', strtotime($vehicle['license_expiry_date'])),
            'user_id' => Auth::id(),
            'group_id' => Auth::user()->group_id,
        ]);

        $vehicleModel->setMeta([
            'ins_number' => $vehicle['insurance_number'] ?? "",
            'ins_exp_date' => isset($vehicle['insurance_expiration_date']) ? date('Y-m-d', strtotime($vehicle['insurance_expiration_date'])) : "",
            'documents' => "",
        ]);

        $vehicleModel->average = $vehicle['averagempg'];
        $vehicleModel->save();
    }
}
