<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickServiceTranslation extends Model
{
    public function usesTimestamps(): bool
    {
        return false;
    }

    protected $guarded = [];
}
