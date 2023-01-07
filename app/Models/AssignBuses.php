<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignBuses extends Model
{
    protected $table = 'assign_buses';
    protected $guarded = [];


    //Station Relation
    public function buses()
    {
        return $this->hasOne('App\Models\Buses', 'bus_id');
    }

    //Station Relation
    public function drivers()
    {
        return $this->hasOne('App\Models\Driver', 'driver_id');
    }

    public function route()
    {
        return $this->belongsTo('App\Models\Routes', 'route_id');
    }
}
