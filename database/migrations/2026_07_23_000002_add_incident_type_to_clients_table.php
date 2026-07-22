<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients') && !Schema::hasColumn('clients', 'type_incident')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('type_incident')->nullable()->after('incident');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'type_incident')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('type_incident');
            });
        }
    }
};
