<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create succursales first (without foreign key to employes yet)
        Schema::create('succursales', function (Blueprint $table) {
            $table->id();
            $table->string('code_succursale')->unique();
            $table->string('nom');
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('telephone')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->boolean('is_siege')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create employes
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('matricule_employe')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('succursale_id')->constrained('succursales')->onDelete('cascade');
            $table->string('poste'); // Administrateur, Responsable succursale, Agent commercial, Gestionnaire, Comptable
            $table->decimal('taux_commission_defaut', 5, 2)->default(0.00); // in %
            $table->date('date_embauche')->nullable();
            $table->date('date_sortie')->nullable();
            $table->string('statut')->default('actif'); // actif, inactif
            $table->timestamps();
        });

        // 3. Add foreign key to succursales for responsable_id
        Schema::table('succursales', function (Blueprint $table) {
            $table->foreign('responsable_id')->references('id')->on('employes')->onDelete('set null');
        });

        // 4. Create commissions_employes
        Schema::create('commissions_employes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats_auto')->onDelete('cascade');
            $table->decimal('base_calcul', 12, 2)->default(0.00);
            $table->decimal('taux_applique', 5, 2)->default(0.00);
            $table->decimal('montant_commission', 12, 2)->default(0.00);
            $table->string('periode'); // e.g. "2026-07"
            $table->string('statut')->default('calculee'); // calculee, validee, payee, annulee
            $table->dateTime('date_validation')->nullable();
            $table->dateTime('date_paiement')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('employes')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('succursales', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);
        });
        Schema::dropIfExists('commissions_employes');
        Schema::dropIfExists('employes');
        Schema::dropIfExists('succursales');
    }
};
