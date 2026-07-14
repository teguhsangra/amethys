<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    public function payment(){
        return $this->belongsTo('App\Models\Status');
    }

    public function bank_account(){
        return $this->belongsTo('App\Models\BankAccount');
    }

    public function non_cash(){
        return $this->belongsTo('App\Models\NonCash');
    }
}
