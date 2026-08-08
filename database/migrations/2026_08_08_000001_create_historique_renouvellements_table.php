<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_renouvellements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrat_id')->index();
            $table->date('anc_date_effet')->nullable();
            $table->date('anc_date_echeance')->nullable();
            $table->date('nouv_date_effet')->nullable();
            $table->date('nouv_date_echeance')->nullable();
            $table->decimal('prime_totale', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('contrat_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_renouvellements');
    }
};
