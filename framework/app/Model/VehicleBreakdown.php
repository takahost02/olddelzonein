<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleBreakdown extends Model 
{
  
    use SoftDeletes;
    protected $table = 'vehicle_breakdown';
    protected $fillable = ['name'];
}
