<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

class DevTenantSeeder extends Seeder
{
    public function run(): void
    {
        $id     = config('app.dev_tenant_id', 'acme');
        $domain = config('app.dev_tenant_domain', 'acme.credify.test');
        $dbName = 'tenant' . $id;

        // --- Central cleanup ---
        Domain::where('tenant_id', $id)->orWhere('domain', $domain)->delete();
        Tenant::withTrashed()->where('id', $id)->forceDelete();

        // Drop tenant DB (idempotent)
        if (! in_array($dbName, ['mysql','information_schema','performance_schema','sys'], true)) {
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
        }

        // --- Create tenant row + domain ---
        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->firstOrCreate(['domain' => $domain]);

        // --- Create tenant database explicitly ---
        $tenant->database()->manager()->createDatabase($tenant);

        // Refresh tenant connection so it points at the new DB
        DB::purge('tenant');

        // --- Initialize tenancy and run tenant migrations on tenant connection ---
        tenancy()->initialize($tenant);
        try {
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path'     => 'database/migrations/tenant',
                '--force'    => true,
            ]);
        } finally {
            tenancy()->end();
        }

        // --- Seed starting credits in CENTRAL context ---
        tenancy()->central(function () use ($tenant) {
            app(\App\Services\CreditService::class)->add(
                $tenant,
                (int) config('credits.starting_balance', 100),
                'initial.credits'
            );
        });

        $this->command?->info("Tenant [$id] ready at [$domain] using DB [$dbName].");
    }
}
