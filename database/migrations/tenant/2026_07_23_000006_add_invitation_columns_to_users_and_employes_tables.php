<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'status')) {
                    $table->string('status')->default('active')->after('branch_id');
                }
                if (!Schema::hasColumn('users', 'invitation_token')) {
                    $table->string('invitation_token')->nullable()->after('status');
                }
                if (!Schema::hasColumn('users', 'invitation_expires_at')) {
                    $table->timestamp('invitation_expires_at')->nullable()->after('invitation_token');
                }
                if (!Schema::hasColumn('users', 'invitation_sent_at')) {
                    $table->timestamp('invitation_sent_at')->nullable()->after('invitation_expires_at');
                }
            });
        }

        if (Schema::hasTable('employes')) {
            Schema::table('employes', function (Blueprint $table) {
                if (!Schema::hasColumn('employes', 'statut')) {
                    $table->string('statut')->default('active')->after('date_sortie');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['status', 'invitation_token', 'invitation_expires_at', 'invitation_sent_at']);
            });
        }

        if (Schema::hasTable('employes')) {
            Schema::table('employes', function (Blueprint $table) {
                $table->dropColumn(['statut']);
            });
        }
    }
};
