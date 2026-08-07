<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('reglements')) {
            DB::table('reglements')->truncate();
        }
        if (Schema::hasTable('financial_ledgers')) {
            DB::table('financial_ledgers')->truncate();
        }
        if (Schema::hasTable('cheques')) {
            DB::table('cheques')->truncate();
        }
        if (Schema::hasTable('agency_expenses')) {
            DB::table('agency_expenses')->truncate();
        }
        if (Schema::hasTable('payments')) {
            DB::table('payments')->truncate();
        }
        if (Schema::hasTable('contrats_auto')) {
            DB::table('contrats_auto')->truncate();
        }
        if (Schema::hasTable('contracts')) {
            DB::table('contracts')->truncate();
        }
        if (Schema::hasTable('clients')) {
            DB::table('clients')->truncate();
        }
        if (Schema::hasTable('cash_registers')) {
            DB::table('cash_registers')->update([
                'current_balance' => 0.00,
                'expected_balance' => 0.00,
                'opening_balance' => 0.00,
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
    }
};
