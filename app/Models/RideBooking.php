<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideBooking extends Model
{
    protected $table = 'ride_booking';
    protected $guarded = [];


    public function passenger()
    {
        return $this->belongsTo('App\Models\User', 'passenger_id');
    }

    public function busDetail()
    {
        return $this->belongsTo('App\Models\Buses', 'bus_id');
    }

    public function stationDetail()
    {
        return $this->belongsTo('App\Models\BusStations', 'station_id');
    }
}
