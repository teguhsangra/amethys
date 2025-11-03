<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function vendor_category(){
        return $this->belongsToMany('App\Models\RoomCategory','v_c_and_vendor','vendor_id','vendor_category_id')->withTimestamps();
    }
}
