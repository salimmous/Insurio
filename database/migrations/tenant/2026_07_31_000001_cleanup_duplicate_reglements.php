<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reglements')) {
            return;
        }

        // Find and delete duplicate reglements for the same contract, amount, and date,
        // keeping the row with a reference_paiement or the lowest ID.
        $duplicates = DB::table('reglements as r1')
            ->join('reglements as r2', function ($join) {
                $join->on('r1.contrat_id', '=', 'r2.contrat_id')
                     ->on('r1.montant', '=', 'r2.montant')
                     ->whereRaw('DATE(r1.date_reglement) = DATE(r2.date_reglement)')
                     ->whereRaw('r1.id > r2.id');
            })
            ->select('r1.id')
            ->pluck('id')
            ->toArray();

        if (!empty($duplicates)) {
            DB::table('reglements')->whereIn('id', $duplicates)->delete();
        }
    }

    public function down(): void
    {
        // No reverse action needed for cleanup
    }
};
