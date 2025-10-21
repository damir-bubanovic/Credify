<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class DevTenantSeeder extends Seeder
{
    public function run(): void
    {
        $id     = config('app.dev_tenant_id', 'acme');
        $domain = config('app.dev_tenant_domain', 'acme.credify.localhost');
        $dbName = 'tenant'.$id;

        // central: reset tenant + domain
        Domain::where('domain', $domain)->delete();
        Tenant::where('id', $id)->delete();

        // drop + create tenant DB
        if (!in_array($dbName, ['mysql','information_schema','performance_schema','sys'], true)) {
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        // create tenant row + domain
        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->firstOrCreate(['domain' => $domain]);

        // run TENANT migrations on the tenant connection
        tenancy()->initialize($tenant);
        try {
            // make sure default connection is tenant for safety
            config(['database.default' => 'tenant']);
            DB::purge('tenant'); // refresh connection after config change

            $migrator = app('migrator');
            $path = database_path('migrations/tenant');

            // use tenant connection explicitly for repo + run
            $migrator->usingConnection('tenant', function () use ($migrator, $path) {
                if (! $migrator->repositoryExists()) {
                    $migrator->getRepository()->createRepository();
                }
                $migrator->run([$path]);
            });

            // seed starting credits in CENTRAL
            tenancy()->central(function () use ($tenant) {
                app(\App\Services\CreditService::class)->add(
                    $tenant,
                    (int) config('credits.starting_balance', 100),
                    'initial.credits'
                );
            });
        } finally {
            tenancy()->end();
        }

        $this->command?->info("Tenant [$id] ready at [$domain] using DB [$dbName].");
    }
}
