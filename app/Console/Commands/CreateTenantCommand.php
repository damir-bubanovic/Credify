<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenant:create {id} {domain}';
    protected $description = 'Create a tenant with its domain and seed credits';

    public function handle(): int
    {
        $id = $this->argument('id');
        $domain = $this->argument('domain');

        // delete existing if it exists
        Tenant::where('id', $id)->delete();

        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->create(['domain' => $domain]);

        $this->info("Tenant {$id} created with domain {$domain}");

        // seed initial credits
        tenancy()->central(fn () =>
            app(\App\Services\CreditService::class)->add($tenant, 100, 'initial credits')
        );

        $this->info('Initial credits: 100');

        return self::SUCCESS;
    }
}
