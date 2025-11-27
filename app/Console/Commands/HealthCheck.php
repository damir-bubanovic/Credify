<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class HealthCheck extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Basic application health check (central DB + one tenant DB)';

    public function handle(): int
    {
        try {
            // Central DB
            DB::connection()->getPdo();

            // One tenant DB if exists
            $tenant = Tenant::first();

            if ($tenant) {
                tenancy()->initialize($tenant);
                DB::connection('tenant')->getPdo();
            }

            $this->info('OK');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('ERROR: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
