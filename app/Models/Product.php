<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function product_category(){
        return $this->belongsToMany('App\Models\ProductCategory','p_c_and_product','product_id','product_category_id')->withTimestamps();
    }

    public function package(){
        return $this->belongsToMany('App\Models\Package','package_and_product','product_id','package_id')->withTimestamps();
    }

    public function booking_details()
    {
        return $this->hasMany('App\Models\BookingDetail');
    }
}
