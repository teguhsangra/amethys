<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function this_child()
    {
        return $this->hasMany('App\Models\Room', 'parent_id', 'id');
    }

    public function booking_details()
    {
        return $this->hasMany('App\Models\BookingDetail');
    }

    public function this_parent()
    {
        return $this->belongsTo('App\Models\Room', 'parent_id', 'id');
    }

    public function room_type()
    {
        return $this->belongsTo('App\Models\RoomType');
    }

    public function room_category()
    {
        return $this->belongsToMany('App\Models\RoomCategory', 'r_c_and_room', 'room_id', 'room_category_id')->withTimestamps();
    }

    public function package()
    {
        return $this->belongsToMany('App\Models\Package', 'package_and_room', 'room_id', 'package_id')->withTimestamps();
    }

    public function furniture()
    {
        return $this->belongsToMany('App\Models\Furniture', 'room_and_furniture', 'room_id', 'furniture_id')->withTimestamps()->withPivot('quantity');
    }
}
