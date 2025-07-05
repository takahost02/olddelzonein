<?php
/*
@copyright

Fleet Manager v7.0

Copyright (C) 2017-2023 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

*/

namespace App\Imports;

use App\Model\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable; // Enables importing and catching validation errors


    public function model(array $customer)
    {
        if ($customer['email'] != null) {

            if (!$this->validateEmail($customer['email'])) {
                return null;
            }
            $id = User::create([
                "name" => $customer['first_name'] . " " . $customer['last_name'],
                "email" => $customer['email'],
                "password" => bcrypt($customer['password']),
                "user_type" => "C",
                "api_token" => str_random(60),
            ])->id;
            $user = User::find($id);
            $user->first_name = $customer['first_name'];
            $user->last_name = $customer['last_name'];
            $user->address = $customer['address'];
            $user->mobno = $customer['phone'];
            if ($customer['gender'] == "female") {
                $user->gender = 0;
            } else {
                $user->gender = 1;
            }
            $user->save();
            $user->givePermissionTo(['Bookings add','Bookings edit','Bookings list','Bookings delete']);
        }
    }

    private function validateEmail($email)
    {
        $emailExists = User::where('email', $email)->where('user_type', 'C')->exists();
        return !$emailExists;
    }

    // Add validation rules
    public function rules(): array
    {
        return [
            'email'      => ['required', 'email', 'unique:users,email'],
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'password'   => ['required', 'min:6'],
            'phone'      => ['nullable'],
            'gender'     => ['nullable','in:male,female'],
        ];
    }

    
}
