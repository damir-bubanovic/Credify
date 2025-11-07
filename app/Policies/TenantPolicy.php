<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tenant;

class TenantPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole('admin'); }
    public function view(User $user, Tenant $tenant): bool { return $user->hasRole('admin'); }
    public function suspend(User $user, Tenant $tenant): bool { return $user->hasRole('admin'); }
    public function delete(User $user, Tenant $tenant): bool { return $user->hasRole('admin'); }
    public function restore(User $user, Tenant $tenant): bool { return $user->hasRole('admin'); }
}
