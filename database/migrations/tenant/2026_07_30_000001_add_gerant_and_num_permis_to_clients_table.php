<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'gerant')) {
                    $table->string('gerant')->nullable()->after('company_name');
                }
                if (!Schema::hasColumn('clients', 'num_permis')) {
                    $table->string('num_permis')->nullable()->after('cin');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'gerant')) {
                    $table->dropColumn('gerant');
                }
                if (Schema::hasColumn('clients', 'num_permis')) {
                    $table->dropColumn('num_permis');
                }
            });
        }
    }
};
