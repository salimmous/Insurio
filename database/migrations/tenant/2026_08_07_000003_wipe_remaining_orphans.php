<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'reglements',
            'financial_ledgers',
            'cheques',
            'agency_expenses',
            'payments',
            'commissions_employes',
            'dossiers',
            'sinistres',
            'contrats_auto',
            'contracts',
            'clients',
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                DB::table($t)->truncate();
            }
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
