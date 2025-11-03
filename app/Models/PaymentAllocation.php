<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    public function payment(){
        return $this->belongsTo('App\Models\Payment');
    }

    public function invoice(){
        return $this->belongsTo('App\Models\Invoice');
    }

    public function deposit(){
        return $this->belongsTo('App\Models\Deposit');
    }
}
