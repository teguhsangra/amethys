<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function nature_of_business(){
        return $this->belongsTo('App\Models\NatureOfBusiness');
    }

    public function contact(){
        return $this->belongsToMany('App\Models\Contact','customer_and_contact','customer_id','contact_id')->withTimestamps()->withPivot('default_status', 'position', 'department');
    }

    public function customer_file(){
        return $this->hasMany('App\Models\CustomerFile');
    }

    public function sales_activity(){
        return $this->hasMany('App\Models\SalesActivity');
    }
}
