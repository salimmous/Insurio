<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compagnie;
use App\Models\Apporteur;
use App\Models\AgenceBranch;
use App\Models\Client;
use App\Models\Vehicule;
use App\Models\ContratAuto;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\User;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoBusinessDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Branches (AgenceBranch)
        $branch1 = AgenceBranch::firstOrCreate(['nom' => 'Siège Casablanca'], [
            'adresse' => 'Boulevard Zerktouni, Casablanca',
            'telephone' => '+212 5 22 12 34 56',
            'responsable' => 'Elissar',
            'statut' => 'active',
        ]);

        $branch2 = AgenceBranch::firstOrCreate(['nom' => 'Succursale Marrakech'], [
            'adresse' => 'Avenue Mohammed V, Marrakech',
            'telephone' => '+212 5 24 98 76 54',
            'responsable' => 'Karim Alami',
            'statut' => 'active',
        ]);

        // 2. Succursales
        $suc1 = Succursale::firstOrCreate(['code_succursale' => 'SUC-CASA'], [
            'nom' => 'Siège Casablanca',
            'adresse' => 'Boulevard Zerktouni, Casablanca',
            'ville' => 'Casablanca',
            'telephone' => '+212 5 22 12 34 56',
            'is_siege' => true,
            'is_active' => true,
        ]);

        $suc2 = Succursale::firstOrCreate(['code_succursale' => 'SUC-KECH'], [
            'nom' => 'Succursale Marrakech',
            'adresse' => 'Avenue Mohammed V, Marrakech',
            'ville' => 'Marrakech',
            'telephone' => '+212 5 24 98 76 54',
            'is_siege' => false,
            'is_active' => true,
        ]);

        // 3. Roles and User Accounts
        $adminRole = Role::firstOrCreate(['name' => 'agency-admin', 'guard_name' => 'web']);
        $responsableRole = Role::firstOrCreate(['name' => 'responsable-succursale', 'guard_name' => 'web']);
        $commercialRole = Role::firstOrCreate(['name' => 'agent-commercial', 'guard_name' => 'web']);

        // Find or create users
        $adminUser = User::first() ?? User::create([
            'name' => 'Super Admin',
            'email' => 'salim.moustanir@gmail.com',
            'password' => Hash::make('password'),
            'branch_id' => $branch1->id,
        ]);
        $adminUser->assignRole($adminRole);

        $respUser = User::updateOrCreate(['email' => 'responsable@insurio.com'], [
            'name' => 'Responsable Casa',
            'password' => Hash::make('password'),
            'branch_id' => $branch1->id,
        ]);
        $respUser->assignRole($responsableRole);

        $agentUser = User::updateOrCreate(['email' => 'agent@insurio.com'], [
            'name' => 'Agent Commercial Marrakech',
            'password' => Hash::make('password'),
            'branch_id' => $branch2->id,
        ]);
        $agentUser->assignRole($commercialRole);

        // 4. Employees (Employe)
        $empAdmin = Employe::firstOrCreate(['matricule_employe' => 'EMP-001'], [
            'user_id' => $adminUser->id,
            'nom' => 'Moustanir',
            'prenom' => 'Salim',
            'cin' => 'AB123456',
            'telephone' => '+212 6 00 00 00 01',
            'email' => $adminUser->email,
            'succursale_id' => $suc1->id,
            'poste' => 'Administrateur',
            'taux_commission_defaut' => 15.00,
            'date_embauche' => Carbon::now()->subYears(2),
            'statut' => 'actif',
        ]);

        $empResp = Employe::firstOrCreate(['matricule_employe' => 'EMP-002'], [
            'user_id' => $respUser->id,
            'nom' => 'El Alami',
            'prenom' => 'Karim',
            'cin' => 'CD789012',
            'telephone' => '+212 6 00 00 00 02',
            'email' => $respUser->email,
            'succursale_id' => $suc1->id,
            'poste' => 'Responsable succursale',
            'taux_commission_defaut' => 10.00,
            'date_embauche' => Carbon::now()->subYear(),
            'statut' => 'actif',
        ]);

        $empAgent = Employe::firstOrCreate(['matricule_employe' => 'EMP-003'], [
            'user_id' => $agentUser->id,
            'nom' => 'Benjelloun',
            'prenom' => 'Ali',
            'cin' => 'EF345678',
            'telephone' => '+212 6 00 00 00 03',
            'email' => $agentUser->email,
            'succursale_id' => $suc2->id,
            'poste' => 'Agent commercial',
            'taux_commission_defaut' => 8.50,
            'date_embauche' => Carbon::now()->subMonths(6),
            'statut' => 'actif',
        ]);

        // Link Responsables back to Succursales
        $suc1->update(['responsable_id' => $empResp->id]);
        $suc2->update(['responsable_id' => $empAgent->id]);

        // 5. Compagnies
        $axa = Compagnie::firstOrCreate(['code' => 'AXA'], [
            'nom' => 'AXA Assurance Maroc',
            'adresse' => 'Casablanca',
            'telephone' => '+212 5 22 88 88 88',
        ]);

        $rma = Compagnie::firstOrCreate(['code' => 'RMA'], [
            'nom' => 'RMA Watanya',
            'adresse' => 'Casablanca',
            'telephone' => '+212 5 22 99 99 99',
        ]);

        // 6. Apporteurs
        $apporteur = Apporteur::firstOrCreate(['code_apporteur' => 'APP-001'], [
            'nom' => 'Ali',
            'prenom' => 'Benjelloun',
            'email' => 'ali@apporteurs.ma',
            'telephone' => '+212 6 61 23 45 67',
            'taux_commission' => 12.50,
        ]);

        // 7. Clients
        $client1 = Client::firstOrCreate(['cin' => 'AB123456'], [
            'nom' => 'El Alami',
            'prenom' => 'Mohamed',
            'email' => 'mohamed.alami@gmail.com',
            'telephone' => '+212 6 62 11 22 33',
            'adresse' => 'Anfa, Casablanca',
            'type' => 'particulier',
            'solvabilite' => 'solvable',
            'incident' => false,
        ]);

        $client2 = Client::firstOrCreate(['cin' => 'CD789012'], [
            'nom' => 'Berrada',
            'prenom' => 'Fatima',
            'email' => 'fatima.berrada@gmail.com',
            'telephone' => '+212 6 63 44 55 66',
            'adresse' => 'Gueliz, Marrakech',
            'type' => 'particulier',
            'solvabilite' => 'solvable',
            'incident' => false,
        ]);

        // 8. Vehicules
        $vehicule1 = Vehicule::firstOrCreate(['matricule' => '12345-A-26'], [
            'marque' => 'Dacia',
            'modele' => 'Logan',
            'type_carburant' => 'diesel',
            'puissance_fiscale' => 6,
            'date_mise_circulation' => '2022-05-15',
        ]);

        $vehicule2 = Vehicule::firstOrCreate(['matricule' => '67890-B-26'], [
            'marque' => 'Renault',
            'modele' => 'Clio',
            'type_carburant' => 'essence',
            'puissance_fiscale' => 7,
            'date_mise_circulation' => '2023-11-20',
        ]);

        // 9. Contrats
        $contrat1 = ContratAuto::firstOrCreate(['numero_contrat' => 'REF-2026-0001'], [
            'client_id' => $client1->id,
            'vehicule_id' => $vehicule1->id,
            'compagnie_id' => $axa->id,
            'apporteur_id' => $apporteur->id,
            'branch_id' => $branch1->id,
            'succursale_id' => $suc1->id,
            'employe_id' => $empAgent->id,
            'police' => 'POL-8888',
            'date_effet' => Carbon::now()->format('Y-m-d'),
            'date_echeance' => Carbon::now()->addYear()->format('Y-m-d'),
            'date_production' => Carbon::now()->format('Y-m-d'),
            'prime_rc' => 2500.00,
            'def_rec' => 150.00,
            'tierce' => 0.00,
            'collision' => 0.00,
            'vol' => 0.00,
            'incendie' => 0.00,
            'bris_glace' => 200.00,
            'individuel' => 100.00,
            'prime_nette' => 2950.00,
            'taxe_auto' => 295.00,
            'accessoire_auto_cie' => 50.00,
            'timbre' => 20.00,
            'commission_auto' => 300.00,
            'tps_auto' => 30.00,
            'montant_pta' => 500.00,
            'montant_taxe_pta' => 50.00,
            'commission_pta' => 50.00,
            'tps_pta' => 5.00,
            'accessoires' => 10.00,
            'prime_totale' => 3825.00,
            'statut' => 'actif',
            'souscripteur' => 'Mohamed El Alami',
        ]);

        $contrat2 = ContratAuto::firstOrCreate(['numero_contrat' => 'REF-2026-0002'], [
            'client_id' => $client2->id,
            'vehicule_id' => $vehicule2->id,
            'compagnie_id' => $rma->id,
            'apporteur_id' => $apporteur->id,
            'branch_id' => $branch2->id,
            'succursale_id' => $suc2->id,
            'employe_id' => $empAgent->id,
            'police' => 'POL-9999',
            'date_effet' => Carbon::now()->format('Y-m-d'),
            'date_echeance' => Carbon::now()->addYear()->format('Y-m-d'),
            'date_production' => Carbon::now()->format('Y-m-d'),
            'prime_rc' => 3000.00,
            'def_rec' => 150.00,
            'tierce' => 0.00,
            'collision' => 0.00,
            'vol' => 0.00,
            'incendie' => 0.00,
            'bris_glace' => 250.00,
            'individuel' => 100.00,
            'prime_nette' => 3500.00,
            'taxe_auto' => 350.00,
            'accessoire_auto_cie' => 60.00,
            'timbre' => 25.00,
            'commission_auto' => 400.00,
            'tps_auto' => 40.00,
            'montant_pta' => 600.00,
            'montant_taxe_pta' => 60.00,
            'commission_pta' => 60.00,
            'tps_pta' => 6.00,
            'accessoires' => 15.00,
            'prime_totale' => 4616.00,
            'statut' => 'actif',
            'souscripteur' => 'Fatima Berrada',
        ]);

        // Calculate commissions
        app(CommissionService::class)->calculerCommission($contrat1);
        app(CommissionService::class)->calculerCommission($contrat2);
    }
}
