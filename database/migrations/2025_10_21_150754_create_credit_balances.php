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
        Schema::create('credit_balances', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('low_threshold')->default(100);
            $table->boolean('auto_topup_enabled')->default(false);
            $table->bigInteger('topup_amount')->default(0);
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
            $table->unique('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_balances');
    }
};
