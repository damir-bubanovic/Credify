<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use Billable;

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];
}
