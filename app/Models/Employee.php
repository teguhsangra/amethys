<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	public function this_child(){
		return $this->hasMany('App\Models\Employee','parent_id','id');
	}

	public function this_parent(){
		return $this->belongsTo('App\Models\Employee','parent_id','id');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

    public function prospect()
    {
        return $this->hasMany('App\Models\Prospect');
    }

    public function sales_activity()
    {
        return $this->hasMany('App\Models\Prospect');
    }

    public function inquiry()
    {
        return $this->hasMany('App\Models\Prospect');
    }

    public function booking()
    {
        return $this->hasMany('App\Models\Prospect');
    }
}
