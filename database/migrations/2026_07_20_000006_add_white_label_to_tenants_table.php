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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('couleur_primaire', 7)->nullable(); // Hex color e.g., #0EA5A0
            $table->string('couleur_secondaire', 7)->nullable(); // Hex color e.g., #0D9488
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'favicon_path', 'couleur_primaire', 'couleur_secondaire']);
        });
    }
};
