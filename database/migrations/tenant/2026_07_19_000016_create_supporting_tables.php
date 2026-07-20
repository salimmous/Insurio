<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable'); // documentable_id and documentable_type index
            $table->string('nom');
            $table->string('chemin_fichier');
            $table->timestamps();
        });

        Schema::create('historique_contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrats_auto')->onDelete('cascade');
            $table->string('action'); // creation, modification, annulation, renouvellement
            $table->text('description')->nullable();
            $table->foreignId('fait_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('saisie_lot_temp', function (Blueprint $table) {
            $table->id();
            $table->json('data'); // raw uploaded row data
            $table->string('statut')->default('en_attente'); // en_attente, valide, erreur
            $table->text('logs')->nullable(); // errors list if validation failed
            $table->foreignId('fait_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saisie_lot_temp');
        Schema::dropIfExists('historique_contrats');
        Schema::dropIfExists('documents');
    }
};
