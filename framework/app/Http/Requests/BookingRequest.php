<?php

/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2023 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users
        return true;
    }

    public function rules()
    {
        return [
            // All fields optional now
            'customer_id' => 'nullable',
            'pickup' => 'nullable|date',
            'dropoff' => 'nullable|after:pickup',
            'vehicle_id' => 'nullable',
            'pickup_addr' => 'nullable',
            'dest_addr' => 'nullable|different:pickup_addr',
        ];
    }

    public function messages()
    {
        return [
            'dest_addr.different' => 'Pickup address and drop-off address must be different',
            'dropoff.after' => __('fleet.dropoff_msg_validation'),
        ];
    }
}
