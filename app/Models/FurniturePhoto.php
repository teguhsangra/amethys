<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FurniturePhoto extends Model
{
	public function furniture(){
		return $this->belongsTo('App\Models\Furniture');
	}
}
