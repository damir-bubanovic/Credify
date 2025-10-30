<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditBalance extends Model
{
    protected $connection = 'mysql';              // central
    protected $table = 'credit_balances';

    protected $fillable = ['tenant_id','balance','low_threshold'];
    protected $casts = ['balance'=>'int','low_threshold'=>'int'];
}
