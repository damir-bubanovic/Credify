<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\NullOutput;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class RecreateTenantCommand extends Command
{
    protected $signature = 'tenant:recreate {id} {domain}';
    protected $description = 'Recreate tenant, DB, domain, run tenant migrations, and seed starting credits';

    public function handle(): int
    {
        $id = (string) $this->argument('id');
        $domain = (string) $this->argument('domain');

        // drop tenant row + DB or leftover DB
        if ($existing = Tenant::find($id)) {
            $old = $existing->database()->getName();
            Domain::where('tenant_id', $existing->id)->delete();
            $existing->delete();
            $this->dropDbIfSafe($old);
        } else {
            $tmp = new Tenant(['id' => $id]);
            $this->dropDbIfSafe($tmp->database()->getName());
        }

        // fresh tenant + domain
        $tenant = Tenant::create(['id' => $id]);
        $tenant->domains()->firstOrCreate(['domain' => $domain]);

        // ensure tenant DB exists
        $dbName = $tenant->database()->getName();
        DB::statement('CREATE DATABASE IF NOT EXISTS `'.str_replace('`','``',$dbName).'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        // run TENANT migrations silently
        tenancy()->initialize($tenant);
        Artisan::call('migrate', [
            '--path'  => 'database/migrations/tenant',
            '--force' => true,
        ], new NullOutput());
        tenancy()->end();

        // seed starting credits (central)
        $starting = (int) config('credits.starting_balance', 100);
        tenancy()->central(function () use ($tenant, $starting) {
            app(\App\Services\CreditService::class)->add($tenant, $starting, 'initial.credits');
        });

        return self::SUCCESS;
    }

    private function dropDbIfSafe(?string $db): void
    {
        if (!$db) return;
        if (in_array($db, ['mysql','information_schema','performance_schema','sys'], true)) return;
        DB::statement('DROP DATABASE IF EXISTS `'.str_replace('`','``',$db).'`');
    }
}

