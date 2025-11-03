<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complimentary extends Model
{
    use SoftDeletes;
    protected $table = 'complimentarys';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function room_category(){
        return $this->belongsTo('App\Models\RoomCategory');
    }
}
