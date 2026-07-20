<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contrats_auto') && !Schema::hasTable('contracts')) {
            return;
        }

        // 1. Create detail tables
        if (!Schema::hasTable('auto_contract_details')) {
            Schema::create('auto_contract_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vehicule_id')->nullable();
                $table->string('usage')->nullable();
                $table->string('code_classe')->nullable();
                $table->string('sous_classe')->default('Definitive');
                $table->string('marque')->nullable();
                $table->string('matricule')->nullable();
                $table->integer('puissance_fiscale')->nullable();
                $table->integer('nb_places')->nullable();
                $table->string('carburant')->nullable();
                $table->integer('nbr_mois')->default(12);
                $table->decimal('valeur_vehicule', 15, 2)->default(0.00);
                $table->date('date_mise_circulation')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('health_contract_details')) {
            Schema::create('health_contract_details', function (Blueprint $table) {
                $table->id();
                $table->text('medical_conditions')->nullable();
                $table->decimal('coverage_limit', 12, 2)->default(0.00);
                $table->string('insurer_network')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('travel_contract_details')) {
            Schema::create('travel_contract_details', function (Blueprint $table) {
                $table->id();
                $table->string('destination')->nullable();
                $table->string('passport_number')->nullable();
                $table->string('travel_purpose')->nullable();
                $table->timestamps();
            });
        }

        // 2. Rename contrats_auto to contracts if it exists
        if (Schema::hasTable('contrats_auto')) {
            Schema::rename('contrats_auto', 'contracts');
        }

        // Check if the foreign key contracts_vehicule_id_foreign exists
        $hasForeignKey = false;
        if (DB::getDriverName() !== 'sqlite') {
            try {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = SCHEMA() 
                      AND TABLE_NAME = 'contracts' 
                      AND COLUMN_NAME = 'vehicule_id' 
                      AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                $hasForeignKey = !empty($foreignKeys);
            } catch (\Exception $e) {
                $hasForeignKey = false;
            }
        } else {
            $hasForeignKey = true;
        }

        // 3. Update contracts schema
        Schema::table('contracts', function (Blueprint $table) use ($hasForeignKey) {
            // Add polymorphic columns if not exist
            if (!Schema::hasColumn('contracts', 'details_type')) {
                $table->string('details_type')->nullable()->after('id');
            }
            if (!Schema::hasColumn('contracts', 'details_id')) {
                $table->unsignedBigInteger('details_id')->nullable()->after('details_type');
            }

            // Add alias or new column renames/additions
            if (!Schema::hasColumn('contracts', 'contract_number')) {
                $table->string('contract_number')->nullable()->after('details_id');
            }
            if (!Schema::hasColumn('contracts', 'policy_number')) {
                $table->string('policy_number')->nullable()->after('contract_number');
            }
            if (!Schema::hasColumn('contracts', 'start_date')) {
                $table->date('start_date')->nullable()->after('policy_number');
            }
            if (!Schema::hasColumn('contracts', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('contracts', 'premium_amount')) {
                $table->decimal('premium_amount', 12, 2)->default(0.00)->after('end_date');
            }
            if (!Schema::hasColumn('contracts', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->default(0.00)->after('premium_amount');
            }
            if (!Schema::hasColumn('contracts', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('commission_amount');
            }
            if (!Schema::hasColumn('contracts', 'insurance_company_id')) {
                $table->unsignedBigInteger('insurance_company_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('contracts', 'insurance_type_id')) {
                $table->unsignedBigInteger('insurance_type_id')->nullable()->after('insurance_company_id');
            }
            
            // Drop foreign keys to allow columns to be dropped or modified
            if ($hasForeignKey) {
                try {
                    $table->dropForeign(['vehicule_id']);
                } catch (\Exception $e) {
                    // Ignore
                }
            }
            $table->unsignedBigInteger('vehicule_id')->nullable()->change();
        });

        // 4. Data migration: Migrate existing data to auto_contract_details
        $contracts = DB::table('contracts')->get();
        foreach ($contracts as $c) {
            // Skip if details already migrated
            if ($c->details_id && $c->details_type) {
                continue;
            }

            // Calculate total commissions
            $commAmount = (float)($c->commission_auto ?? 0.00) + (float)($c->commission_pta ?? 0.00);

            // Determine payment status based on reglements sum
            $regSum = DB::table('reglements')->where('contrat_id', $c->id)->sum('montant');
            $payStatus = 'pending';
            if ($regSum >= $c->prime_totale && $c->prime_totale > 0) {
                $payStatus = 'paid';
            } elseif ($regSum > 0) {
                $payStatus = 'partial';
            }

            // Insert into auto_contract_details
            $detailId = DB::table('auto_contract_details')->insertGetId([
                'vehicule_id' => $c->vehicule_id ?? null,
                'usage' => $c->usage ?? null,
                'code_classe' => $c->code_classe ?? null,
                'sous_classe' => $c->sous_classe ?? 'Definitive',
                'marque' => $c->marque ?? null,
                'matricule' => $c->matricule ?? null,
                'puissance_fiscale' => $c->puissance_fiscale ?? null,
                'nb_places' => $c->nb_places ?? null,
                'carburant' => $c->carburant ?? null,
                'nbr_mois' => $c->nbr_mois ?? 12,
                'valeur_vehicule' => $c->valeur_vehicule ?? 0.00,
                'date_mise_circulation' => $c->date_mise_circulation ?? null,
                'created_at' => $c->created_at ?? now(),
                'updated_at' => $c->updated_at ?? now(),
            ]);

            // Update generic contract fields
            DB::table('contracts')->where('id', $c->id)->update([
                'details_type' => 'App\\Models\\AutoContractDetail',
                'details_id' => $detailId,
                'contract_number' => $c->numero_contrat ?? null,
                'policy_number' => $c->police ?? null,
                'start_date' => $c->date_effet ?? null,
                'end_date' => $c->date_echeance ?? null,
                'premium_amount' => $c->prime_totale ?? 0.00,
                'commission_amount' => $commAmount,
                'payment_status' => $payStatus,
                'insurance_company_id' => $c->compagnie_id ?? null,
                'insurance_type_id' => $c->product_id ?? null,
            ]);
        }

        // 5. Clean up old columns from contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $colsToDrop = [];
            $allCols = ['usage', 'code_classe', 'sous_classe', 'marque', 'matricule', 'puissance_fiscale', 'nb_places', 'carburant', 'nbr_mois', 'valeur_vehicule', 'date_mise_circulation'];
            foreach ($allCols as $col) {
                if (Schema::hasColumn('contracts', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('contracts')) {
            return;
        }

        // Add back vehicle columns to contracts table if rolled back
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicule_id')->nullable();
            $table->string('usage')->nullable();
            $table->string('code_classe')->nullable();
            $table->string('sous_classe')->default('Definitive');
            $table->string('marque')->nullable();
            $table->string('matricule')->nullable();
            $table->integer('puissance_fiscale')->nullable();
            $table->integer('nb_places')->nullable();
            $table->string('carburant')->nullable();
            $table->integer('nbr_mois')->default(12);
            $table->decimal('valeur_vehicule', 15, 2)->default(0.00);
            $table->date('date_mise_circulation')->nullable();
            try {
                $table->foreign('vehicule_id')->references('id')->on('vehicules')->onDelete('cascade');
            } catch (\Exception $e) {}
        });

        // Restore data from auto_contract_details
        if (Schema::hasTable('auto_contract_details')) {
            $details = DB::table('auto_contract_details')->get();
            foreach ($details as $d) {
                DB::table('contracts')
                    ->where('details_type', 'App\\Models\\AutoContractDetail')
                    ->where('details_id', $d->id)
                    ->update([
                        'vehicule_id' => $d->vehicule_id,
                        'usage' => $d->usage,
                        'code_classe' => $d->code_classe,
                        'sous_classe' => $d->sous_classe,
                        'marque' => $d->marque,
                        'matricule' => $d->matricule,
                        'puissance_fiscale' => $d->puissance_fiscale,
                        'nb_places' => $d->nb_places,
                        'carburant' => $d->carburant,
                        'nbr_mois' => $d->nbr_mois,
                        'valeur_vehicule' => $d->valeur_vehicule,
                        'date_mise_circulation' => $d->date_mise_circulation,
                    ]);
            }
        }

        // Clean generic columns
        Schema::table('contracts', function (Blueprint $table) {
            $colsToDrop = [];
            $allCols = ['details_type', 'details_id', 'contract_number', 'policy_number', 'start_date', 'end_date', 'premium_amount', 'commission_amount', 'payment_status', 'insurance_company_id', 'insurance_type_id'];
            foreach ($allCols as $col) {
                if (Schema::hasColumn('contracts', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });

        Schema::rename('contracts', 'contrats_auto');

        Schema::dropIfExists('travel_contract_details');
        Schema::dropIfExists('health_contract_details');
        Schema::dropIfExists('auto_contract_details');
    }
};
