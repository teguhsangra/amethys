<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingCancellation extends Model
{
    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }
}
