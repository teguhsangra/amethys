<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
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

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function contact(){
        return $this->belongsTo('App\Models\Contact');
    }

    public function referral(){
        return $this->belongsTo('App\Models\Referral');
    }

    public function agent(){
        return $this->belongsTo('App\Models\Agent');
    }

    public function sales_activity(){
        return $this->hasMany('App\Models\SalesActivity');
    }
}
