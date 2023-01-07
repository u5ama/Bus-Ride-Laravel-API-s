<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerCurrentLocation extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $table = 'passenger_current_location';
}
