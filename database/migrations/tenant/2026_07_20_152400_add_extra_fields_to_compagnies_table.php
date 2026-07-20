<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compagnies', function (Blueprint $table) {
            if (!Schema::hasColumn('compagnies', 'contact')) {
                $table->string('contact')->nullable()->after('telephone');
            }
            if (!Schema::hasColumn('compagnies', 'email')) {
                $table->string('email')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('compagnies', 'active')) {
                $table->boolean('active')->default(true)->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('compagnies', function (Blueprint $table) {
            $table->dropColumn(['contact', 'email', 'active']);
        });
    }
};
