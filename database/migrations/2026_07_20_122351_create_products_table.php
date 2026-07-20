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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('statut')->default('actif'); // actif, inactif
            $table->timestamps();
        });

        // Seed default products
        $defaults = [
            ['code' => 'AUTO', 'nom' => 'Automobile', 'description' => 'Assurance Automobile obligatoire et garanties annexes (Tierce, Vol, Incendie, etc.)'],
            ['code' => 'MOTO', 'nom' => 'Moto', 'description' => 'Assurance pour deux-roues motorisés'],
            ['code' => 'HAB', 'nom' => 'Habitation', 'description' => 'Multirisque Habitation (MRH) pour propriétaires et locataires'],
            ['code' => 'SANTE', 'nom' => 'Santé', 'description' => 'Assurance Maladie, Hospitalisation et Complémentaire Santé'],
            ['code' => 'AT', 'nom' => 'Accidents du Travail', 'description' => 'Assurance obligatoire pour les employés d\'une entreprise'],
            ['code' => 'RC', 'nom' => 'R.C. Générale', 'description' => 'Responsabilité Civile Professionnelle ou Décennale'],
            ['code' => 'ASSIST', 'nom' => 'Assistance', 'description' => 'Assistance voyage et rapatriement sanitaire'],
        ];

        foreach ($defaults as $product) {
            \Illuminate\Support\Facades\DB::table('products')->insert(array_merge($product, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
