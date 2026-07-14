<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingComplimentary extends Model
{
    public function booking()
    {
        return $this->belongsTo('App\Models\Booking');
    }
    public function complimentary()
    {
        return $this->belongsTo('App\Models\Complimentary');
    }
}
