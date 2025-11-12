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
        Schema::connection('mysql')->create('credit_balances', function (Blueprint $table) {
            // tenant_id is string, not UUID — consistent with Tenant::id = "acme"
            $table->string('tenant_id')->primary();

            $table->bigInteger('balance')->default(0);
            $table->bigInteger('low_threshold')->default(100);

            // Optional top-up automation fields
            $table->boolean('auto_topup_enabled')->default(false);
            $table->bigInteger('topup_amount')->default(0)->nullable();
            $table->string('stripe_price_id')->nullable();

            $table->timestamps();

            $table->index('tenant_id'); // harmless with primary but keeps consistency
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('credit_balances');
    }
};
