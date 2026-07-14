<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceList extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function contact(){
        return $this->belongsTo('App\Models\Contact');
    }

    public function invoices(){
        return $this->belongsToMany('App\Models\Invoice', 'invoice_list_details', 'invoice_list_id', 'invoice_id')->withTimestamps();
    }
}
