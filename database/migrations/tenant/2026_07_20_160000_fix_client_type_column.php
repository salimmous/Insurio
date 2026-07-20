<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Defensive migration: ensures `client_type` column exists on the `clients` table.
 * Handles production databases that may still have the old `type` column name from
 * the original schema before the CRM migration was applied.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('clients')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            // Case 1: old `type` column exists but `client_type` does NOT → rename
            if (Schema::hasColumn('clients', 'type') && !Schema::hasColumn('clients', 'client_type')) {
                $table->renameColumn('type', 'client_type');
            }

            // Case 2: neither column exists → add `client_type` fresh
            if (!Schema::hasColumn('clients', 'type') && !Schema::hasColumn('clients', 'client_type')) {
                $table->string('client_type')->default('individual')->after('incident');
            }
        });

        // Normalise existing values: 'particulier' → 'individual', 'entreprise' → 'company'
        if (Schema::hasColumn('clients', 'client_type')) {
            DB::table('clients')
                ->where('client_type', 'particulier')
                ->update(['client_type' => 'individual']);

            DB::table('clients')
                ->where('client_type', 'professionnel')
                ->update(['client_type' => 'individual']);

            DB::table('clients')
                ->where('client_type', 'entreprise')
                ->update(['client_type' => 'company']);
        }
    }

    public function down(): void
    {
        // Non-destructive: do not reverse the rename in production
    }
};
