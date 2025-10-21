<?php

// app/Services/Credits/CreditAccount.php
namespace App\Services\Credits;

use App\Models\CreditBalance;
use App\Models\CreditLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\Credits\CreditsLow;
use App\Events\Credits\CreditsDepleted;

class CreditAccount
{
    public function add(int $amount, string $reason, array $meta = [], ?string $key = null): int
    {
        return DB::transaction(function () use ($amount, $reason, $meta, $key) {
            $tenantId = tenant('id');
            $balance = CreditBalance::lockForUpdate()->firstOrCreate(['tenant_id' => $tenantId]);
            if ($key && CreditLedger::where('idempotency_key', $key)->exists()) {
                return $balance->balance; // idempotent
            }
            $balance->balance += $amount;
            $balance->save();

            CreditLedger::create([
                'tenant_id' => $tenantId,
                'delta' => +$amount,
                'balance_after' => $balance->balance,
                'reason' => $reason,
                'idempotency_key' => $key ?? Str::uuid(),
                'caused_by_type' => auth()->user()?->getMorphClass() ?? 'system',
                'caused_by_id' => auth()->id() ?? 0,
                'meta' => $meta,
            ]);

            return $balance->balance;
        });
    }

    public function deduct(int $amount, string $reason, array $meta = [], ?string $key = null): bool
    {
        return DB::transaction(function () use ($amount, $reason, $meta, $key) {
            $tenantId = tenant('id');
            $balance = CreditBalance::lockForUpdate()->firstOrCreate(['tenant_id' => $tenantId]);
            if ($key && CreditLedger::where('idempotency_key', $key)->exists()) {
                return true; // already deducted
            }
            if ($balance->balance < $amount) {
                event(new CreditsDepleted($tenantId, $balance->balance));
                return false;
            }

            $balance->balance -= $amount;
            $balance->save();

            CreditLedger::create([
                'tenant_id' => $tenantId,
                'delta' => -$amount,
                'balance_after' => $balance->balance,
                'reason' => $reason,
                'idempotency_key' => $key ?? Str::uuid(),
                'caused_by_type' => auth()->user()?->getMorphClass() ?? 'system',
                'caused_by_id' => auth()->id() ?? 0,
                'meta' => $meta,
            ]);

            if ($balance->balance <= $balance->low_threshold) {
                event(new CreditsLow($tenantId, $balance->balance, $balance->low_threshold));
            }

            return true;
        });
    }
}
