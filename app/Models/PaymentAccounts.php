<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class PaymentAccounts extends Model
{
    protected $table = 'payment_accounts';
    protected $guarded = [];
}
