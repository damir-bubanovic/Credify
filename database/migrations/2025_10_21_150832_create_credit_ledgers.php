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
        Schema::connection('mysql')->create('credit_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');

            // match tenant_id type to credit_balances (string)
            $table->string('tenant_id')->index();

            // +credits or -credits, always recorded
            $table->bigInteger('delta');
            $table->bigInteger('balance_after'); // required for accurate history

            // reason for the transaction
            $table->string('reason')->nullable()->index();

            // idempotency: prevent duplicate inserts
            $table->string('idempotency_key')->nullable()->unique();

            // user or system actor
            $table->string('caused_by_type')->nullable();
            $table->unsignedBigInteger('caused_by_id')->nullable();

            // additional data
            $table->json('meta')->nullable();

            $table->timestamps();

            // optional indexing for analytics
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('credit_ledgers');
    }
};
