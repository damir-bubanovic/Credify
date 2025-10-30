<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription as CashierSubscription;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use Billable, HasDatabase, HasDomains;

    protected $casts = ['trial_ends_at' => 'datetime'];

    // Force Cashier to use 'user_id' as FK
    public function subscriptions()
    {
        return $this->hasMany(CashierSubscription::class, 'user_id')
            ->orderBy('created_at', 'desc');
    }
}
