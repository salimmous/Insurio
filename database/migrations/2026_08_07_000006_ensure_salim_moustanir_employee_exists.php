<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            if (Schema::hasTable('employes') && Schema::hasTable('users')) {
                $user = DB::table('users')->where('id', 1)->orWhere('email', 'salim.moustanir@gmail.com')->first();
                if (!$user) {
                    return;
                }

                $exists = DB::table('employes')
                    ->where('email', 'salim.moustanir@gmail.com')
                    ->orWhere('user_id', $user->id)
                    ->first();

                $succursale = Schema::hasTable('succursales') ? DB::table('succursales')->first() : null;
                $succursaleId = $succursale ? $succursale->id : 1;

                if (!$exists) {
                    DB::table('employes')->insert([
                        'user_id' => $user->id,
                        'matricule_employe' => 'EMP-001',
                        'nom' => 'Moustanir',
                        'prenom' => 'Salim',
                        'email' => 'salim.moustanir@gmail.com',
                        'telephone' => '+212 6 00 00 00 01',
                        'cin' => 'AB123456',
                        'succursale_id' => $succursaleId,
                        'poste' => 'Administrateur',
                        'taux_commission_defaut' => 15.00,
                        'statut' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('employes')
                        ->where('id', $exists->id)
                        ->update([
                            'user_id' => $user->id,
                            'nom' => 'Moustanir',
                            'prenom' => 'Salim',
                            'email' => 'salim.moustanir@gmail.com',
                            'poste' => 'Administrateur',
                            'statut' => 'active',
                        ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore FK or table structure mismatch gracefully
        }
    }

    public function down(): void
    {
    }
};
