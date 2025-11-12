<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLedger extends Model
{
    // Central DB
    protected $connection = 'mysql';
    protected $table = 'credit_ledgers';

    protected $fillable = [
        'tenant_id',
        'delta',
        'balance_after',   // REQUIRED
        'reason',
        'meta',
        // (optional fields if you add them later)
        // 'idempotency_key',
        // 'caused_by_type',
        // 'caused_by_id',
    ];

    protected $casts = [
        'delta'         => 'int',
        'balance_after' => 'int',
        'meta'          => 'array',
    ];
}
