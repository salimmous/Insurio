<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reglements')) {
            Schema::table('reglements', function (Blueprint $table) {
                if (!Schema::hasColumn('reglements', 'date_echeance_cheque')) {
                    $table->date('date_echeance_cheque')->nullable()->after('reference_paiement');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reglements')) {
            Schema::table('reglements', function (Blueprint $table) {
                if (Schema::hasColumn('reglements', 'date_echeance_cheque')) {
                    $table->dropColumn('date_echeance_cheque');
                }
            });
        }
    }
};
