<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingReminder extends Model
{
    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function billing_reminder_detail(){
        return $this->hasMany('App\Models\BillingReminderDetail');
    }

    public function proforma(){
        return $this->hasMany('App\Models\Proforma');
    }

    public function invoice(){
        return $this->hasMany('App\Models\Invoice');
    }
}
