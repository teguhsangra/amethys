<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
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

    public function invoice(){
        return $this->hasMany('App\Models\Invoice');
    }

    public function order_detail(){
        return $this->hasMany('App\Models\OrderDetail');
    }
}
