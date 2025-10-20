<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class RecreateTenantCommand extends Command
{
    protected $signature = 'tenant:recreate {id} {domain}';
    protected $description = 'Drop and recreate a tenant DB, run tenant migrations+seed, attach domain, seed credits';

    public function handle(): int
    {
        $id = $this->argument('id');
        $domain = $this->argument('domain');

        // Delete existing tenant + DB if present
        if ($t = Tenant::find($id)) {
            $dbName = $t->database()->getName();
            Domain::where('tenant_id', $t->id)->delete();
            $t->delete(); // triggers DeleteDatabase job if configured
            // hard-drop in case job didn’t run
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
            $this->info("Removed existing tenant [$id] and DB [$dbName].");
        }

        // Create fresh tenant -> CreateDatabase, MigrateDatabase, SeedDatabase run via pipeline
        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->firstOrCreate(['domain' => $domain]);

        // Seed initial credits in central context
        tenancy()->central(fn () =>
            app(\App\Services\CreditService::class)->add($tenant, 100, 'initial credits')
        );

        $this->info("Tenant [$id] ready at [$domain].");
        return self::SUCCESS;
    }
}
