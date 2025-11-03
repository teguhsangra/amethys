<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTarget extends Model
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

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
}
