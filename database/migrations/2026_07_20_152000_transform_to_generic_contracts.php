<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create detail tables
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

        Schema::create('health_contract_details', function (Blueprint $table) {
            $table->id();
            $table->text('medical_conditions')->nullable();
            $table->decimal('coverage_limit', 12, 2)->default(0.00);
            $table->string('insurer_network')->nullable();
            $table->timestamps();
        });

        Schema::create('travel_contract_details', function (Blueprint $table) {
            $table->id();
            $table->string('destination')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('travel_purpose')->nullable();
            $table->timestamps();
        });

        // 2. Rename contrats_auto to contracts
        Schema::rename('contrats_auto', 'contracts');

        // 3. Update contracts schema
        Schema::table('contracts', function (Blueprint $table) {
            // Add polymorphic columns
            $table->string('details_type')->nullable()->after('id');
            $table->unsignedBigInteger('details_id')->nullable()->after('details_type');

            // Add alias or new column renames/additions
            $table->string('contract_number')->nullable()->after('details_id');
            $table->string('policy_number')->nullable()->after('contract_number');
            $table->date('start_date')->nullable()->after('policy_number');
            $table->date('end_date')->nullable()->after('start_date');
            $table->decimal('premium_amount', 12, 2)->default(0.00)->after('end_date');
            $table->decimal('commission_amount', 10, 2)->default(0.00)->after('premium_amount');
            $table->string('payment_status')->default('pending')->after('commission_amount');
            $table->unsignedBigInteger('insurance_company_id')->nullable()->after('payment_status');
            $table->unsignedBigInteger('insurance_type_id')->nullable()->after('insurance_company_id');
            
            // Drop foreign keys to allow columns to be dropped or modified
            $table->dropForeign(['vehicule_id']);
            $table->unsignedBigInteger('vehicule_id')->nullable()->change();
        });

        // 4. Data migration: Migrate existing data to auto_contract_details
        $contracts = DB::table('contracts')->get();
        foreach ($contracts as $c) {
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
                'vehicule_id' => $c->vehicule_id,
                'usage' => $c->usage,
                'code_classe' => $c->code_classe,
                'sous_classe' => $c->sous_classe,
                'marque' => $c->marque,
                'matricule' => $c->matricule,
                'puissance_fiscale' => $c->puissance_fiscale,
                'nb_places' => $c->nb_places,
                'carburant' => $c->carburant,
                'nbr_mois' => $c->nbr_mois,
                'valeur_vehicule' => $c->valeur_vehicule,
                'date_mise_circulation' => $c->date_mise_circulation,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
            ]);

            // Update generic contract fields
            DB::table('contracts')->where('id', $c->id)->update([
                'details_type' => 'App\\Models\\AutoContractDetail',
                'details_id' => $detailId,
                'contract_number' => $c->numero_contrat,
                'policy_number' => $c->police,
                'start_date' => $c->date_effet,
                'end_date' => $c->date_echeance,
                'premium_amount' => $c->prime_totale,
                'commission_amount' => $commAmount,
                'payment_status' => $payStatus,
                'insurance_company_id' => $c->compagnie_id,
                'insurance_type_id' => $c->product_id,
            ]);
        }

        // 5. Clean up old columns from contracts table
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'usage', 'code_classe', 'sous_classe', 'marque', 'matricule',
                'puissance_fiscale', 'nb_places', 'carburant', 'nbr_mois', 'valeur_vehicule',
                'date_mise_circulation'
            ]);
        });
    }

    public function down(): void
    {
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
            $table->foreign('vehicule_id')->references('id')->on('vehicules')->onDelete('cascade');
        });

        // Restore data from auto_contract_details
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

        // Clean generic columns
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'details_type', 'details_id', 'contract_number', 'policy_number',
                'start_date', 'end_date', 'premium_amount', 'commission_amount',
                'payment_status', 'insurance_company_id', 'insurance_type_id'
            ]);
        });

        Schema::rename('contracts', 'contrats_auto');

        Schema::dropIfExists('travel_contract_details');
        Schema::dropIfExists('health_contract_details');
        Schema::dropIfExists('auto_contract_details');
    }
};
