<?php

namespace App\Imports;

use App\Model\ExpCats;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;

class ExpenseCategoriesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Map Excel rows to database models
     */
    public function model(array $expense)
    {
        if (!isset($expense['category_name']) || empty($expense['category_name'])) {
            return null; // Skip empty rows
        }

        return new ExpCats([
            "name" => $expense['category_name'],
            "user_id" => Auth::id(),
            "type" => "u",
        ]);
    }

    /**
     * Define validation rules for each row
     */
    public function rules(): array
    {
        return [
            '*.category_name' => 'required|max:255',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            '*.category_name.required' => 'The category name field is required.',
            '*.category_name.string' => 'The category name must be a valid string.',
            '*.category_name.max' => 'The category name may not be greater than 255 characters.',
        ];
    }
}
