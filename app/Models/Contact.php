<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function customer()
    {
        return $this->belongsToMany('App\Models\Customer', 'customer_and_contact',  'contact_id', 'customer_id')->withTimestamps()->withPivot('default_status', 'position', 'department');
    }
}
