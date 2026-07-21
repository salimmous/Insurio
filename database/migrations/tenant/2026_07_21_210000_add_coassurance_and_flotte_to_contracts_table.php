<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('contracts', 'co_assurance_part')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->decimal('co_assurance_part', 5, 2)->default(100.00)->nullable()->after('insurance_company_id');
                $table->string('flotte_nom')->nullable()->after('co_assurance_part');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('contracts', 'co_assurance_part')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn(['co_assurance_part', 'flotte_nom']);
            });
        }
    }
};
