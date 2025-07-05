<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingAlert extends Model 
{
  
    use SoftDeletes;
    protected $table = 'booking_alerts';

    
	public function booking() {
		return $this->hasOne("App\Model\Bookings", "id", "booking_id")->withTrashed();
	}

}
