<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesActivity extends Model
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

    public function prospect()
    {
        return $this->belongsTo('App\Models\Prospect', 'prospect_id');
    }

    public function previous()
    {
        return $this->belongsTo('App\Models\SalesActivity', 'previous_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Contact');
    }

    public function marketing_material()
    {
        return $this->belongsToMany('App\Models\MarketingMaterial', 's_a_and_m_m', 'sales_activity_id', 'marketing_material_id')->withTimestamps();
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }
}
