<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyServiceTranslation extends Model
{
    public function usesTimestamps(): bool
    {
        return false;
    }

    protected $guarded = [];
}
