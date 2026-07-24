<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('cash_registers')) {
            Schema::table('cash_registers', function (Blueprint $table) {
                if (!Schema::hasColumn('cash_registers', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }

        if (Schema::hasTable('cash_registers')) {
            Schema::table('cash_registers', function (Blueprint $table) {
                if (Schema::hasColumn('cash_registers', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
