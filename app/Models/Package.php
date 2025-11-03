<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function room(){
        return $this->belongsToMany('App\Models\Room','package_and_room','package_id','room_id')->withTimestamps();
    }

    public function product(){
        return $this->belongsToMany('App\Models\Product','package_and_product','package_id','product_id')->withTimestamps();
    }
}
