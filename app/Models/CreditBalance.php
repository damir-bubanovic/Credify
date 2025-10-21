<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditBalance extends Model
{
    protected $fillable = [
        'tenant_id','balance','low_threshold',
        'auto_topup_enabled','topup_amount','stripe_price_id'
    ];
}
