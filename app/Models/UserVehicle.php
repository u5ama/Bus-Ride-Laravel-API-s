<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVehicle extends Model
{

    protected $guarded = [];

    public function carModel()
    {
        return $this->belongsTo(CarModel::Class);
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::Class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::Class);
    }

    public function modelYear()
    {
        return $this->belongsTo(ModelYear::Class);
    }

    public function body()
    {
        return $this->belongsTo(Body::Class);
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::Class);
    }

    public function user()
    {
        return $this->belongsTo(User::Class);
    }

    public function engine()
    {
        return $this->belongsTo(Engine::Class);
    }
}
