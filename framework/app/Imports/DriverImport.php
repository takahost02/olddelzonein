<?php

namespace App\Imports;

use App\Model\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class DriverImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable; // Enables importing and catching validation errors

    public function model(array $driver)
    {
    

        if ($driver['email'] != null) {
            if (!$this->validateEmail($driver['email'])) {
                return null;
            }

            $user = User::create([
                "name" => $driver['first_name'] . " " . $driver['last_name'],
                "email" => $driver['email'],
                "password" => bcrypt($driver['password']),
                "user_type" => "D",
                'api_token' => str_random(60),
            ]);

            $user->is_active = 1;
            $user->is_available = 0;
            $user->first_name = $driver['first_name'];
            $user->middle_name = $driver['middle_name'];
            $user->last_name = $driver['last_name'];
            $user->address = $driver['address'];
            $user->phone = $driver['phone'];
            $user->phone_code = "+" . $driver['country_code'];
            $user->emp_id = $driver['employee_id'];
            $user->contract_number = $driver['contract_number'];
            $user->license_number = $driver['licence_number'];
            
            if ($driver['issue_date'] != null) {
                $user->issue_date = date('Y-m-d', strtotime($driver['issue_date']));
            }

            if ($driver['expiration_date'] != null) {
                $user->exp_date = date('Y-m-d', strtotime($driver['expiration_date']));
            }

            if ($driver['join_date'] != null) {
                $user->start_date = date('Y-m-d', strtotime($driver['join_date']));
            }

            if ($driver['leave_date'] != null) {
                $user->end_date = date('Y-m-d', strtotime($driver['leave_date']));
            }

            $user->gender = (($driver['gender'] == 'female') ? 0 : 1);
            $user->econtact = $driver['emergency_contact_details'];

            $user->givePermissionTo([
                'Notes add', 'Notes edit', 'Notes delete', 'Notes list',
                'Drivers list', 'VehicleInspection add', 'VehicleInspection list',
                'VehicleInspection edit', 'VehicleInspection delete'
            ]);

            $user->save();
        }
    }

    // Email validation to check if email exists in database
    private function validateEmail($email)
    {
        return !User::where('email', $email)->where('user_type', 'D')->exists();
    }

    // Add validation rules
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:6'],
            'phone' => ['nullable'],
            'contract_number' => ['nullable'],
            'licence_number' => ['nullable'],
            'join_date' => ['nullable', 'date'],
            'leave_date' => ['nullable', 'date'],
        ];
    }
}
