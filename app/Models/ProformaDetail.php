<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaDetail extends Model
{
    public function proforma(){
        return $this->belongsTo('App\Models\Proforma');
    }

    public function booking_detail(){
        return $this->belongsTo('App\Models\BookingDetail');
    }

    public function order_detail(){
        return $this->belongsTo('App\Models\OrderDetail');
    }

    public function booking_cancellation(){
        return $this->belongsTo('App\Models\BookingCancellation');
    }
}
