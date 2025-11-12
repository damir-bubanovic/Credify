<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantApiKey extends Model
{
    // Use the central connection (same as CreditBalance, etc.)
    protected $connection = 'mysql';
    protected $table = 'tenant_api_keys';

    protected $fillable = [
        'tenant_id',
        'name',
        'key',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
