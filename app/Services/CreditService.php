<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\CreditBalance;
use App\Models\CreditLedger;
use Illuminate\Support\Facades\DB;

class CreditService
{
    public function balance(Tenant $tenant): int
    {
        return tenancy()->central(function () use ($tenant) {
            $row = CreditBalance::firstOrCreate(
                ['tenant_id' => (string) $tenant->getKey()],
                [
                    'balance'       => (int) config('credits.starting_balance', 100),
                    'low_threshold' => (int) config('credits.low_threshold', 10),
                ]
            );

            return (int) $row->balance;
        });
    }

    public function add(Tenant $tenant, int $amount, ?string $reason = null, array $meta = []): void
    {
        tenancy()->central(function () use ($tenant, $amount, $reason, $meta) {
            DB::connection('mysql')->transaction(function () use ($tenant, $amount, $reason, $meta) {
                $tid = (string) $tenant->getKey();

                $bal = CreditBalance::lockForUpdate()->firstOrCreate(
                    ['tenant_id' => $tid],
                    [
                        'balance'       => 0,
                        'low_threshold' => (int) config('credits.low_threshold', 10),
                    ]
                );

                $before = (int) $bal->balance;
                $bal->balance = $before + $amount;
                $bal->save();

                $after = (int) $bal->balance;

                CreditLedger::create([
                    'tenant_id'      => $tid,
                    'delta'          => $amount,
                    'balance_before' => $before,
                    'balance_after'  => $after,
                    'reason'         => $reason,
                    'meta'           => $meta,
                ]);
            });
        });
    }

    public function spend(Tenant $tenant, int $amount, ?string $reason = null, array $meta = []): bool
    {
        return tenancy()->central(function () use ($tenant, $amount, $reason, $meta) {
            return DB::connection('mysql')->transaction(function () use ($tenant, $amount, $reason, $meta) {
                $tid = (string) $tenant->getKey();

                $bal = CreditBalance::lockForUpdate()->firstOrCreate(
                    ['tenant_id' => $tid],
                    [
                        'balance'       => 0,
                        'low_threshold' => (int) config('credits.low_threshold', 10),
                    ]
                );

                if ($bal->balance < $amount) {
                    return false;
                }

                $before = (int) $bal->balance;
                $bal->balance = $before - $amount;
                $bal->save();

                $after = (int) $bal->balance;

                CreditLedger::create([
                    'tenant_id'      => $tid,
                    'delta'          => -$amount,
                    'balance_before' => $before,
                    'balance_after'  => $after,
                    'reason'         => $reason,
                    'meta'           => $meta,
                ]);

                return true;
            });
        });
    }
}
