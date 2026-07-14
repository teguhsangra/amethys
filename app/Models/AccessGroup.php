<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessGroup extends Model
{
	public function module(){
    	return $this->belongsToMany('App\Models\Module','a_g_and_module','access_group_id','module_id')->withTimestamps();
    }
	
	public function dashboard(){
    	return $this->belongsToMany('App\Models\Dashboard','a_g_and_dashboard','access_group_id','dashboard_id')->withTimestamps();
    }
    
	public function user(){
		return $this->hasMany('App\User');
	}
}
