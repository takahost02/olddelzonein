<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2023 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */


namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kodeine\Metable\Metable;

class Bookings extends Model
{
    use HasFactory, SoftDeletes, Metable;

    protected $dates = ['deleted_at'];
    protected $table = "bookings";
    protected $metaTable = 'bookings_meta';


    protected $fillable = [
        'customer_id', 'vehicle_id', 'user_id', 'pickup', 'dropoff',
        'pickup_addr', 'dest_addr', 'travellers', 'status', 'comment',
        'dropoff_time', 'driver_id', 'note', 'cancellation', 'completed_at',
        'journey_date', 'journey_time', 'accept_status', 'ride_status',
        'booking_type', 'vehicle_typeid', 'parent_booking_id', 'ride_type',
        'dropoff_address','booking_schedule','material_type'
    ];

    
	protected function getMetaKeyName() {
		return 'booking_id'; // The parent foreign key
	}

    public function vehicle()
    {
        return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id")->withTrashed();
    }

    public function customer()
    {
        return $this->hasOne("App\Model\User", "id", "customer_id")->withTrashed();
    }

    public function driver()
    {
        return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
    }

    public function user()
    {
        return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
    }

    public function vehicletype()
    {
        return $this->hasOne("App\Model\VehicleTypeModel", "id", "type_id")->withTrashed();
    }
}
