<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'dossier_id')) {
                $table->foreignId('dossier_id')->nullable()->after('contract_id')->constrained('dossiers')->onDelete('cascade');
            }
        });

        Schema::table('communications', function (Blueprint $table) {
            if (!Schema::hasColumn('communications', 'dossier_id')) {
                $table->foreignId('dossier_id')->nullable()->after('client_id')->constrained('dossiers')->onDelete('cascade');
            }
        });

        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'dossier_id')) {
                $table->foreignId('dossier_id')->nullable()->after('contract_id')->constrained('dossiers')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'dossier_id')) {
                $table->dropForeign(['dossier_id']);
                $table->dropColumn('dossier_id');
            }
        });

        Schema::table('communications', function (Blueprint $table) {
            if (Schema::hasColumn('communications', 'dossier_id')) {
                $table->dropForeign(['dossier_id']);
                $table->dropColumn('dossier_id');
            }
        });

        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'dossier_id')) {
                $table->dropForeign(['dossier_id']);
                $table->dropColumn('dossier_id');
            }
        });
    }
};
