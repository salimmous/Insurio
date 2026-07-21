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
        if (!Schema::hasColumn('products', 'marge_pourcentage')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('marge_pourcentage', 5, 2)->default(0.00)->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'marge_pourcentage')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('marge_pourcentage');
            });
        }
    }
};
