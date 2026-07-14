<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
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

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

    public function prospect(){
        return $this->belongsTo('App\Models\Prospect');
    }

    public function referral(){
        return $this->belongsTo('App\Models\Referral');
    }

    public function agent(){
        return $this->belongsTo('App\Models\Agent');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function contact(){
        return $this->belongsTo('App\Models\Contact');
    }

    public function room(){
        return $this->belongsTo('App\Models\Room');
    }

    public function room_category(){
        return $this->belongsTo('App\Models\RoomCategory');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function package(){
        return $this->belongsTo('App\Models\Package');
    }

    public function booking(){
        return $this->hasMany('App\Models\Booking');
    }

    public function proforma(){
        return $this->hasMany('App\Models\Proforma');
    }

    public function rooms(){
        return $this->belongsToMany('App\Models\Room','inquiry_and_room','inquiry_id','room_id')->withTimestamps()->withPivot('detail_price');
    }

    public function packages()
    {
        return $this->belongsToMany('App\Models\Package', 'inquiry_and_package', 'inquiry_id', 'package_id')->withTimestamps()->withPivot('price_type', 'detail_price', 'quantity', 'start_time', 'end_time', 'start_date', 'end_date', 'length_of_term');
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product','inquiry_and_product','inquiry_id','product_id')->withTimestamps()->withPivot('detail_price', 'quantity', 'start_time', 'end_time', 'start_date', 'end_date', 'length_of_term');
    }
}
