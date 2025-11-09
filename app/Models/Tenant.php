<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription as CashierSubscription;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use Billable, HasDatabase, HasDomains, SoftDeletes, Notifiable;

    protected $casts = [
        'trial_ends_at'  => 'datetime',
        'data'           => 'array',
        'credit_balance' => 'integer',
    ];

    // Scopes for admin filters
    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeSuspended($q)
    {
        return $q->where('status', 'suspended');
    }

    // Helpers for admin actions
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    // Convenience accessor
    public function getPrimaryDomainAttribute(): ?string
    {
        return optional($this->domains()->orderBy('id')->first())->domain;
    }

    // Cashier: subscriptions keyed by user_id = tenant.id
    public function subscriptions()
    {
        return $this->hasMany(CashierSubscription::class, 'user_id')
            ->orderByDesc('created_at');
    }

    // Email routing for notifications
    public function routeNotificationForMail(): ?string
    {
        // Prefer explicit billing_email if set
        if ($this->billing_email) {
            return $this->billing_email;
        }

        // Fallback to data JSON
        $data = $this->data ?? [];

        return $data['owner_email'] ?? $data['email'] ?? null;
    }
}
