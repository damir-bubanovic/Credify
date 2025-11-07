<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tenant;

class TenantPolicy
{
    // Optional global override for admin users
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->role === 'admin';
    }

    public function suspend(User $user, Tenant $tenant): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Tenant $tenant): bool
    {
        return $user->role === 'admin';
    }
}
