<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	public function this_child(){
		return $this->hasMany('App\Models\Module','parent_id','id');
	}

	public function this_parent(){
		return $this->belongsTo('App\Models\Module','parent_id','id');
	}
}
