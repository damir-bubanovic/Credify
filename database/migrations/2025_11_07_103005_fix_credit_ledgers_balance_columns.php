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
        // MySQL syntax; adjust if Postgres
        DB::statement('ALTER TABLE credit_ledgers MODIFY balance_before BIGINT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE credit_ledgers MODIFY balance_after  BIGINT NOT NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
