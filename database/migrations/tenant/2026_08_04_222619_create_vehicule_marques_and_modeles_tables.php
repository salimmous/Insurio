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
        if (!Schema::hasTable('vehicule_marques')) {
            Schema::create('vehicule_marques', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('type')->default('voiture'); // voiture, moto, autocar
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('vehicule_modeles')) {
            Schema::create('vehicule_modeles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('marque_id')->constrained('vehicule_marques')->onDelete('cascade');
                $table->string('nom');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicule_modeles');
        Schema::dropIfExists('vehicule_marques');
    }
};
