<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tenants')) {
            throw new \RuntimeException('Table "tenants" not found');
        }

        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'status')) {
                $table->string('status')->default('active')->index();
            }
            if (! Schema::hasColumn('tenants', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tenants')) return;

        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'status')) {
                // Drop by column array works with Laravel’s inferred name
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('tenants', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};

