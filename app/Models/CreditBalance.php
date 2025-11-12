<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditBalance extends Model
{
    // Central DB
    protected $connection = 'mysql';
    protected $table = 'credit_balances';

    // Primary key is tenant_id (string, non-incrementing)
    protected $primaryKey = 'tenant_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'balance',
        'low_threshold',
        'auto_topup_enabled',
        'topup_amount',
        'stripe_price_id',
    ];

    protected $casts = [
        'balance'            => 'int',
        'low_threshold'      => 'int',
        'auto_topup_enabled' => 'bool',
        'topup_amount'       => 'int',
    ];
}
