<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticketing extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function ticketing_subject()
    {
        return $this->belongsTo('App\Models\TicketingSubject');
    }


    public function ticketing_replies()
    {
        return $this->hasMany('App\Models\TicketingReply');
    }
}
