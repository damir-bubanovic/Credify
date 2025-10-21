<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenant:create {id} {domain}
                            {--recreate : Delete existing tenant with the same id before creating}';
    protected $description = 'Create a tenant with its domain and seed credits';

    public function handle(): int
    {
        $id     = (string) $this->argument('id');
        $domain = (string) $this->argument('domain');

        if ($this->option('recreate')) {
            Tenant::where('id', $id)->delete();
            $this->warn("Existing tenant {$id} deleted.");
        }

        // create tenant + domain
        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->create(['domain' => $domain]);

        $this->info("Tenant {$id} created with domain {$domain}");

        // starting credits from config, fallback 100
        $starting = (int) config('credits.starting_balance', 100);

        // seed initial credits in CENTRAL context
        tenancy()->central(function () use ($tenant, $starting) {
            // idempotent: ensure a balance row exists, then top it up to at least $starting
            // prefer your existing CreditService
            /** @var \App\Services\CreditService $svc */
            $svc = app(\App\Services\CreditService::class);

            DB::transaction(function () use ($svc, $tenant, $starting) {
                // Ensure a balance row exists; add() can create it if missing.
                // Top up to target starting balance only if below it.
                $current = $svc->balance($tenant); // implement to return int balance; if not available, skip and just add
                if ($current === null) {
                    // no balance yet; just add starting
                    $svc->add($tenant, $starting, 'initial.credits');
                    return;
                }
                if ($current < $starting) {
                    $svc->add($tenant, $starting - $current, 'initial.credits.topup');
                }
            });
        });

        $this->info("Starting credits ensured to {$starting}.");

        return self::SUCCESS;
    }
}
