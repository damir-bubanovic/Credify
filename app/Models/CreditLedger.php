<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLedger extends Model
{
    protected $fillable = [
        'tenant_id','delta','balance_after','reason',
        'idempotency_key','caused_by_type','caused_by_id','meta'
    ];
    protected $casts = ['meta' => 'array'];
}
