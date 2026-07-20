<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compagnies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique()->nullable();
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // e.g. Automobile, Sante, Accident
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('apporteurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->string('code_apporteur')->unique();
            $table->decimal('taux_commission', 5, 2)->default(0.00); // percentage e.g. 10.50%
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apporteurs');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('compagnies');
    }
};
