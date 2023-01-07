<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    protected $table = 'route';
    protected $guarded = [];


    // Routes Station Relation
    public function routetations()
    {
        return $this->hasMany('App\Models\RouteStations', 'route_id');
    }

    // Routes Station Relation Last
    public function lastroutetations()
    {
        return $this->hasOne('App\Models\RouteStations', 'route_id')->latest();
    }

    //Station Relation
    public function stations()
    {
        return $this->hasOne('App\Models\BusStations', 'station_id');
    }
}
