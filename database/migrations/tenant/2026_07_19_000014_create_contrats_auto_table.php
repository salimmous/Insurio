<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_auto', function (Blueprint $table) {
            $table->id();
            $table->string('numero_contrat')->unique();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('vehicule_id')->constrained('vehicules')->onDelete('cascade');
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade');
            $table->foreignId('apporteur_id')->nullable()->constrained('apporteurs')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained('agence_branches')->onDelete('set null');
            
            // Identification
            $table->string('police');
            $table->string('avenant')->nullable();
            $table->string('type_affaire')->default('AN'); // AN, RN, RC, AV
            $table->string('attestation')->nullable();
            $table->string('quittance')->nullable();
            $table->string('souscripteur')->nullable();

            // Véhicule details
            $table->string('usage')->nullable();
            $table->string('code_classe')->nullable();
            $table->string('sous_classe')->default('Definitive'); // Definitive, Provisoire
            $table->string('marque')->nullable();
            $table->string('matricule')->nullable();
            $table->integer('puissance_fiscale')->nullable();
            $table->integer('nb_places')->nullable();
            $table->string('carburant')->nullable();
            $table->integer('nbr_mois')->default(12);
            $table->decimal('valeur_vehicule', 15, 2)->default(0.00);
            $table->date('date_mise_circulation')->nullable();

            // Dates
            $table->date('date_effet');
            $table->date('date_echeance');
            $table->date('date_production');

            // Primes Garanties
            $table->decimal('prime_rc', 10, 2)->default(0.00);
            $table->decimal('def_rec', 10, 2)->default(0.00);
            $table->decimal('tierce', 10, 2)->default(0.00);
            $table->decimal('collision', 10, 2)->default(0.00);
            $table->decimal('vol', 10, 2)->default(0.00);
            $table->decimal('incendie', 10, 2)->default(0.00);
            $table->decimal('bris_glace', 10, 2)->default(0.00);
            $table->decimal('individuel', 10, 2)->default(0.00);

            // Calculations - Bloc Auto
            $table->decimal('prime_nette', 10, 2)->default(0.00);
            $table->decimal('taxe_auto', 10, 2)->default(0.00);
            $table->decimal('accessoire_auto_cie', 10, 2)->default(0.00);
            $table->decimal('timbre', 10, 2)->default(0.00);
            $table->decimal('commission_auto', 10, 2)->default(0.00);
            $table->decimal('tps_auto', 10, 2)->default(0.00);

            // Calculations - Bloc PTA
            $table->decimal('montant_pta', 10, 2)->default(0.00);
            $table->decimal('montant_taxe_pta', 10, 2)->default(0.00);
            $table->decimal('commission_pta', 10, 2)->default(0.00);
            $table->decimal('tps_pta', 10, 2)->default(0.00);
            $table->decimal('accessoires', 10, 2)->default(0.00); // other accessories

            // Final totals
            $table->decimal('prime_totale', 10, 2)->default(0.00);
            $table->string('statut')->default('actif'); // actif, expire, annule

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_auto');
    }
};
