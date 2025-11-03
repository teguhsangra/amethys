<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function status(){
        return $this->belongsTo('App\Models\Status');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function contact(){
        return $this->belongsTo('App\Models\Contact');
    }

    public function deposit(){
        return $this->belongsTo('App\Models\Deposit');
    }

    public function payment_detail(){
        return $this->hasMany('App\Models\PaymentDetail');
    }

    public function payment_allocation(){
        return $this->hasMany('App\Models\PaymentAllocation');
    }
}
