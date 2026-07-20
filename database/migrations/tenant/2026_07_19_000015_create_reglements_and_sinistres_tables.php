<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrats_auto')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_reglement');
            $table->string('mode_reglement'); // especes, cheque, virement, carte
            $table->string('reference_paiement')->nullable();
            $table->timestamps();
        });

        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrats_auto')->onDelete('cascade');
            $table->date('date_sinistre');
            $table->date('date_declaration');
            $table->text('description')->nullable();
            $table->string('statut')->default('ouvert'); // ouvert, en_cours, regle, rejete
            $table->decimal('montant_estime', 10, 2)->default(0.00);
            $table->decimal('montant_paye', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sinistres');
        Schema::dropIfExists('reglements');
    }
};
