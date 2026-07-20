<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrats_auto', function (Blueprint $table) {
            $table->foreignId('succursale_id')->nullable()->after('branch_id')->constrained('succursales')->onDelete('set null');
            $table->foreignId('employe_id')->nullable()->after('succursale_id')->constrained('employes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('contrats_auto', function (Blueprint $table) {
            $table->dropForeign(['succursale_id']);
            $table->dropForeign(['employe_id']);
            $table->dropColumn(['succursale_id', 'employe_id']);
        });
    }
};
