<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverAlertModel extends Model 
{
  
    use SoftDeletes;
    protected $table = 'driver_alert';
    protected $fillable = ['name'];
}
