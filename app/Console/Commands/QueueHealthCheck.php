<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QueueHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with: php artisan health:queue
     */
    protected $signature = 'health:queue';

    /**
     * The console command description.
     */
    protected $description = 'Queue health check (failed jobs count)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $failed = DB::table('failed_jobs')->count();

        if ($failed > 0) {
            $this->error("Queue health: $failed failed jobs");
            return self::FAILURE;
        }

        $this->info('Queue health: OK');
        return self::SUCCESS;
    }
}
