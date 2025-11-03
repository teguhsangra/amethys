<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    public function status(){
        return $this->belongsTo('App\Models\Status');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }

    public function purchase_order_detail(){
        return $this->hasMany('App\Models\PurchaseOrderDetail');
    }
}
