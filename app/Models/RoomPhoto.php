<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    public function room(){
        return $this->belongsTo('App\Models\Room');
    }
}
