<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employes')) {
            DB::table('employes')->update(['poste' => 'Administrateur']);
        }
    }

    public function down(): void
    {
    }
};
