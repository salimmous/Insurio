<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'modele')) {
                $table->string('modele')->nullable()->after('marque');
            }
            if (!Schema::hasColumn('contracts', 'annee')) {
                $table->smallInteger('annee')->nullable()->after('modele');
            }
            if (!Schema::hasColumn('contracts', 'motorisation')) {
                $table->string('motorisation')->nullable()->after('annee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['modele', 'annee', 'motorisation']);
        });
    }
};
