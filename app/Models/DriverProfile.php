<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'driver_profiles';

    public function transportType(){
        return $this->hasOne('App\TransportType','id');
    }
}
