<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auto_contract_details', function (Blueprint $table) {
            if (!Schema::hasColumn('auto_contract_details', 'modele')) {
                $table->string('modele')->nullable()->after('marque');
            }
            if (!Schema::hasColumn('auto_contract_details', 'annee')) {
                $table->smallInteger('annee')->nullable()->after('modele');
            }
            if (!Schema::hasColumn('auto_contract_details', 'motorisation')) {
                $table->string('motorisation')->nullable()->after('annee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('auto_contract_details', function (Blueprint $table) {
            $table->dropColumn(['modele', 'annee', 'motorisation']);
        });
    }
};
