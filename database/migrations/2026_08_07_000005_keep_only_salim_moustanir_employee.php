<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employes')) {
            DB::table('employes')
                ->where('email', '!=', 'salim.moustanir@gmail.com')
                ->where(function ($q) {
                    $q->where('nom', '!=', 'Moustanir')
                      ->orWhere('prenom', '!=', 'Salim');
                })
                ->delete();
        }
    }

    public function down(): void
    {
    }
};
