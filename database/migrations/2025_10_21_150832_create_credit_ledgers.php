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
        Schema::create('credit_ledgers', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->bigInteger('delta'); // + add, - deduct
            $table->bigInteger('balance_after');
            $table->string('reason')->index();
            $table->string('idempotency_key')->nullable()->unique();
            $table->morphs('caused_by'); // model that caused change
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_ledgers');
    }
};
