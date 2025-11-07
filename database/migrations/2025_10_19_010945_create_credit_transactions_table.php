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
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // matches tenants.id
            $table->enum('type', ['earn','spend','adjust']);
            $table->integer('amount'); // positive integers
            $table->string('reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index('tenant_id');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->integer('credit_balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
