<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients') && !Schema::hasColumn('clients', 'reference')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('reference')->nullable()->after('uuid')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'reference')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('reference');
            });
        }
    }
};
