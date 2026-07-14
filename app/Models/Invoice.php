<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
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

    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public function bank_account(){
        return $this->belongsTo('App\Models\BankAccount');
    }

    public function proforma(){
        return $this->belongsTo('App\Models\Proforma');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    
    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    public function booking(){
        return $this->belongsTo('App\Models\Booking');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function invoice_detail(){
        return $this->hasMany('App\Models\InvoiceDetail');
    }

    public function payment_allocation(){
        return $this->hasMany('App\Models\PaymentAllocation');
    }

    public function deposits()
    {
        return $this->belongsToMany('App\Models\Deposit', 'invoice_and_deposit', 'invoice_id', 'deposit_id')->withTimestamps();
    }
}
