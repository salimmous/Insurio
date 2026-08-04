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
        Schema::table('vehicule_marques', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicule_marques', 'logo')) {
                $table->string('logo')->nullable()->after('type');
            }
        });

        Schema::table('vehicule_modeles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicule_modeles', 'annee_debut')) {
                $table->smallInteger('annee_debut')->nullable()->after('nom');
            }
            if (!Schema::hasColumn('vehicule_modeles', 'annee_fin')) {
                $table->smallInteger('annee_fin')->nullable()->after('annee_debut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicule_marques', function (Blueprint $table) {
            $table->dropColumn(['logo']);
        });

        Schema::table('vehicule_modeles', function (Blueprint $table) {
            $table->dropColumn(['annee_debut', 'annee_fin']);
        });
    }
};
