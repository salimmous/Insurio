<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'assigned_to')) {
                    $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('clients', 'satisfaction_score')) {
                    $table->unsignedTinyInteger('satisfaction_score')->default(5); // 1-5 rating
                }
                if (!Schema::hasColumn('clients', 'last_contact_at')) {
                    $table->timestamp('last_contact_at')->nullable();
                }
                if (!Schema::hasColumn('clients', 'next_contact_at')) {
                    $table->timestamp('next_contact_at')->nullable();
                }
                if (!Schema::hasColumn('clients', 'family_members')) {
                    $table->json('family_members')->nullable();
                }
                if (!Schema::hasColumn('clients', 'beneficiaries')) {
                    $table->json('beneficiaries')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'assigned_to')) {
                    $table->dropForeign(['assigned_to']);
                    $table->dropColumn('assigned_to');
                }
                if (Schema::hasColumn('clients', 'satisfaction_score')) {
                    $table->dropColumn('satisfaction_score');
                }
                if (Schema::hasColumn('clients', 'last_contact_at')) {
                    $table->dropColumn('last_contact_at');
                }
                if (Schema::hasColumn('clients', 'next_contact_at')) {
                    $table->dropColumn('next_contact_at');
                }
                if (Schema::hasColumn('clients', 'family_members')) {
                    $table->dropColumn('family_members');
                }
                if (Schema::hasColumn('clients', 'beneficiaries')) {
                    $table->dropColumn('beneficiaries');
                }
            });
        }
    }
};
