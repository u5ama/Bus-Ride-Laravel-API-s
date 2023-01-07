<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCurrentLogs extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $table = 'driver_current_logs';
}
