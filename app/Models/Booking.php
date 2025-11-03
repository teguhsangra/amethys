<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function bank_account()
    {
        return $this->belongsTo('App\Models\BankAccount');
    }

    public function inquiry()
    {
        return $this->belongsTo('App\Models\Inquiry');
    }

    public function other_booking()
    {
        return $this->belongsTo('App\Models\Booking', 'booking_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function referral()
    {
        return $this->belongsTo('App\Models\Referral');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    public function room_category()
    {
        return $this->belongsTo('App\Models\RoomCategory');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Package');
    }

    public function deposit()
    {
        return $this->belongsTo('App\Models\Deposit');
    }

    public function booking_detail()
    {
        return $this->hasMany('App\Models\BookingDetail');
    }

    public function booking_cancellation()
    {
        return $this->hasMany('App\Models\BookingCancellation');
    }

    public function booking_complimentary()
    {
        return $this->hasMany('App\Models\BookingComplimentary');
    }

    public function booking_guest_comment()
    {
        return $this->hasMany('App\Models\BookingGuestComment');
    }

    public function proforma()
    {
        return $this->hasMany('App\Models\Proforma');
    }

    public function invoice()
    {
        return $this->hasMany('App\Models\Invoice');
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Models\Room', 'booking_and_room', 'booking_id', 'room_id')->withTimestamps()->withPivot('complimentary_id', 'detail_price', 'other_price', 'detail_use_complimentary');
    }

    public function furniture()
    {
        return $this->belongsToMany('App\Models\Furniture', 'booking_and_furniture', 'booking_id', 'furniture_id')->withTimestamps()->withPivot('quantity');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Models\Contact', 'booking_and_contact', 'booking_id', 'contact_id')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'booking_and_product', 'booking_id', 'product_id')->withTimestamps()->withPivot('detail_price', 'quantity', 'start_time', 'end_time', 'start_date', 'end_date', 'length_of_term');
    }

    public function packages()
    {
        return $this->belongsToMany('App\Models\Package', 'booking_and_package', 'booking_id', 'package_id')->withTimestamps()->withPivot('price_type', 'detail_price', 'quantity', 'start_time', 'end_time', 'start_date', 'end_date', 'length_of_term');
    }

    public function dedicated_phones()
    {
        return $this->belongsToMany('App\Models\DedicatedPhone', 'booking_dedicated_phones', 'booking_id', 'dedicated_phone_id')->withTimestamps();
    }

    public function complimentarys()
    {
        return $this->belongsToMany('App\Models\Complimentary', 'booking_and_complimentary', 'booking_id', 'complimentary_id')->withTimestamps()->withPivot('total_complimentary');
    }
}
