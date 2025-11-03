<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    public function booking()
    {
        return $this->belongsTo('App\Models\Booking', 'booking_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }
    public function package()
    {
        return $this->belongsTo('App\Models\Package');
    }
    public function complimentary()
    {
        return $this->belongsTo('App\Models\Complimentary');
    }
}
