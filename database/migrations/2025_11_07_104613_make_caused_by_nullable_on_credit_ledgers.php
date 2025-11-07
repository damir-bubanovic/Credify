<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL syntax; this project uses MySQL via Sail.
        DB::statement('ALTER TABLE credit_ledgers MODIFY caused_by_type varchar(255) NULL');
        DB::statement('ALTER TABLE credit_ledgers MODIFY caused_by_id bigint unsigned NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL if needed.
        DB::statement('ALTER TABLE credit_ledgers MODIFY caused_by_type varchar(255) NOT NULL');
        DB::statement('ALTER TABLE credit_ledgers MODIFY caused_by_id bigint unsigned NOT NULL');
    }
};
