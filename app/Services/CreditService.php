<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreditService
{
    public function add(Tenant $tenant, int $amount, string $reason = null, array $meta = []): void
    {
        DB::transaction(function () use ($tenant, $amount, $reason, $meta) {
            DB::table('credit_transactions')->insert([
                'tenant_id' => $tenant->id,
                'type' => 'earn',
                'amount' => $amount,
                'reason' => $reason,
                'meta' => json_encode($meta),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $tenant->increment('credit_balance', $amount);
        });
    }

    public function spend(Tenant $tenant, int $amount, string $reason = null, array $meta = []): bool
    {
        return DB::transaction(function () use ($tenant, $amount, $reason, $meta) {
            $tenant->refresh();
            if ($tenant->credit_balance < $amount) {
                return false;
            }
            DB::table('credit_transactions')->insert([
                'tenant_id' => $tenant->id,
                'type' => 'spend',
                'amount' => $amount,
                'reason' => $reason,
                'meta' => json_encode($meta),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $tenant->decrement('credit_balance', $amount);
            return true;
        });
    }
}
