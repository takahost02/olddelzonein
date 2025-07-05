<?php

namespace App\Imports;

use App\Model\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class VendorImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $vendor)
    {
        if (!isset($vendor['name']) || empty($vendor['name'])) {
            return null; // Skip empty rows
        }

        return new Vendor([
            'name' => $vendor['name'],
            'phone' => $vendor['phone'] ?? null,
            'email' => $vendor['email'] ?? null,
            'type' => $vendor['type'] ?? null,
            'website' => $vendor['website'] ?? null,
            'address1' => $vendor['address1'] ?? null,
            'address2' => $vendor['address2'] ?? null,
            'city' => $vendor['city'] ?? null,
            'province' => $vendor['stateprovince'] ?? null,
            'postal_code' => $vendor['postal_code'] ?? null,
            'country' => $vendor['country'] ?? null,
            'note' => $vendor['note'] ?? null,
        ]);
    }

    /**
    * Define validation rules for each row
    */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|max:15',
            'type' => 'nullable|max:50',
            'website' => 'nullable|url',
            'address1' => 'nullable|max:255',
            'address2' => 'nullable|max:255',
            'city' => 'nullable|max:100',
            'stateprovince' => 'nullable|max:100',
            'postal_code' => 'nullable|max:20',
            'country' => 'nullable|max:100',
            'note' => 'nullable|max:500',
        ];
    }

    /**
    * Handle validation failures
    */
    public function onFailure(\Maatwebsite\Excel\Validators\ValidationException $exception)
    {
        foreach ($exception->failures() as $failure) {
            Log::error("Row {$failure->row()} failed: " . implode(', ', $failure->errors()));
        }
    }
}
