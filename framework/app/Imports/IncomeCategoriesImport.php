<?php

namespace App\Imports;

use App\Model\IncCats;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Auth;

class IncomeCategoriesImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public $failures = [];

    public function model(array $income)
    {
        // Trim spaces and ensure category name is valid
        $income['category_name'] = trim($income['category_name'] ?? '');

        // Validate row data
        $validator = Validator::make($income, [
            'category_name' => 'required|max:255|unique:inc_cats,name',
        ]);

        if ($validator->fails()) {
            // Store validation errors
            foreach ($validator->errors()->all() as $error) {
                $this->failures[] = $error;
            }
            return null; // Skip invalid rows
        }

        return new IncCats([
            "name" => $income['category_name'],
            "user_id" => Auth::id(),
            "type" => "u",
        ]);
    }

    public function getFailures()
    {
        return $this->failures;
    }
}
    