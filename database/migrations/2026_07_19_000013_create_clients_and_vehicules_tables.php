<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->string('cin')->nullable(); // Carte d'Identité Nationale
            $table->string('adresse')->nullable();
            $table->string('type')->default('particulier'); // particulier, professionnel
            $table->string('solvabilite')->default('solvable'); // solvable, non-solvable
            $table->boolean('incident')->default(false);
            $table->timestamps();
        });

        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique(); // e.g. 12345-A-26
            $table->string('marque');
            $table->string('modele');
            $table->string('type_carburant')->nullable(); // essence, diesel, hybride, electrique
            $table->integer('puissance_fiscale')->nullable(); // e.g. 8 CV
            $table->date('date_mise_circulation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicules');
        Schema::dropIfExists('clients');
    }
};
