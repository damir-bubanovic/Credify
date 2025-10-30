<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLedger extends Model
{
    protected $connection = 'mysql';              // central
    protected $table = 'credit_ledgers';

    protected $fillable = ['tenant_id','delta','reason','meta'];
    protected $casts = ['delta'=>'int','meta'=>'array'];
}
