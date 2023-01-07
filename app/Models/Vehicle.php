<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{

    protected $guarded = [];

    public function carModel()
    {
        return $this->hasMany(CarModel::Class);
    }

    public function body()
    {
        return $this->hasMany(Body::Class);
    }

    public function fuel()
    {
        return $this->hasMany(Fuel::Class);
    }

    public function engine()
    {
        return $this->hasMany(Engine::Class);
    }
}
