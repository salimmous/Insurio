<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->json('features')->nullable();
            $table->json('limits')->nullable(); // e.g. ['users_limit' => 5, 'contracts_limit' => 100]
            $table->timestamps();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('id');
            // We won't add foreign constraint in multi-db if SQLite/mysql configs differ, but standard field is safe.
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });

        Schema::dropIfExists('plans');
    }
};
