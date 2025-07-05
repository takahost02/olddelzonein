<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitiesModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'cities';
	protected $fillable = ['city', 'cost', 'image', 'other', 'slug'];
}
