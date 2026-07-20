<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrats_auto', function (Blueprint $table) {
            $table->boolean('terme')->default(true)->after('numero_contrat');
            $table->string('branche_code')->nullable()->after('apporteur_id');
            $table->string('branche_libelle')->nullable()->after('branche_code');
            $table->date('date_resiliation')->nullable()->after('date_production');
        });
    }

    public function down(): void
    {
        Schema::table('contrats_auto', function (Blueprint $table) {
            $table->dropColumn(['terme', 'branche_code', 'branche_libelle', 'date_resiliation']);
        });
    }
};
