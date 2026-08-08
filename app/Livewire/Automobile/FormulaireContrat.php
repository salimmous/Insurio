<?php

namespace App\Livewire\Automobile;

use Livewire\Component;
use App\Models\ContratAuto;
use App\Models\Client;
use App\Models\Compagnie;
use App\Models\Apporteur;
use App\Models\AgenceBranch;
use Carbon\Carbon;

class FormulaireContrat extends Component
{
    // Mode: create or edit
    public $contratId = null;

    // Fields
    public $numero_contrat;
    public $terme = true; // true = Oui, false = Non
    public $compagnie_id;
    public $police;
    public $avenant;
    public $type_affaire = 'AN'; // AN, RN, RC, AV
    public $attestation;
    public $quittance;

    public $client_id;
    public $souscripteur;
    public $apporteur_id;
    public $nom_apporteur;
    public $branche_code;
    public $branche_libelle;
    public $branch_id;
    public $product_id;

    // Véhicule
    public $usage;
    public $code_classe;
    public $sous_classe = 'Definitive'; // Definitive, Provisoire
    public $marque;
    public $modele;
    public $annee;
    public $motorisation;
    public $matricule;
    public $puissance_fiscale;
    public $nb_places;
    public $carburant;
    public $nbr_mois = 12;
    public $valeur_vehicule = 0;
    public $date_mise_circulation;

    // Dates
    public $date_effet;
    public $date_echeance;
    public $date_production;
    public $date_resiliation;
    public $historiqueRenouvellementsList = [];

    // Primes
    public $prime_rc = 0;
    public $def_rec = 0;
    public $tierce = 0;
    public $collision = 0;
    public $vol = 0;
    public $incendie = 0;
    public $bris_glace = 0;
    public $individuel = 0;

    // Bloc Auto Calculations
    public $taxe_auto = 0;
    public $accessoire_auto_cie = 0;
    public $timbre = 0;
    public $commission_auto = 0;
    public $tps_auto = 0;
    public $prime_totale = 0;

    // Bloc PTA Calculations
    public $montant_pta = 0;
    public $montant_taxe_pta = 0;
    public $commission_pta = 0;
    public $tps_pta = 0;
    public $accessoires = 0; // other accessories

    protected $listeners = [
        'clientSelected' => 'handleClientSelected',
        'apporteurSelected' => 'handleApporteurSelected',
    ];

    public function updatedMarque(): void
    {
        $this->modele = null; // Reset modèle when marque changes
    }

    public function getMarquesDisponibles(): array
    {
        \App\Services\VehiculeCatalogService::seedIfEmpty();
        $dbMarques = \App\Models\VehiculeMarque::where('is_active', true)->orderBy('nom')->pluck('nom')->toArray();
        return !empty($dbMarques) ? $dbMarques : array_keys(config('vehicules_maroc', []));
    }

    public function getModelesDisponibles(): array
    {
        if (!$this->marque) return [];
        
        $marque = \App\Models\VehiculeMarque::where('nom', 'like', $this->marque)->first();
        if ($marque) {
            $dbModeles = \App\Models\VehiculeModele::where('marque_id', $marque->id)->where('is_active', true)->orderBy('nom')->pluck('nom')->toArray();
            if (!empty($dbModeles)) return $dbModeles;
        }

        $vehicules = config('vehicules_maroc', []);
        foreach ($vehicules as $brand => $data) {
            if (strcasecmp($brand, $this->marque) === 0) {
                return $data['modeles'] ?? [];
            }
        }
        return [];
    }

    public $searchApporteur = '';

    public function getApporteursSearchResultsProperty()
    {
        $query = trim($this->searchApporteur);

        if ($query === '') {
            $clients = Client::latest()->limit(6)->get();
            $apporteurs = Apporteur::latest()->limit(6)->get();
        } else {
            $clients = Client::where('last_name', 'like', '%' . $query . '%')
                ->orWhere('first_name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('phone', 'like', '%' . $query . '%')
                ->orWhere('cin', 'like', '%' . $query . '%')
                ->limit(8)
                ->get();

            $apporteurs = Apporteur::where('nom', 'like', '%' . $query . '%')
                ->orWhere('prenom', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('code_apporteur', 'like', '%' . $query . '%')
                ->limit(8)
                ->get();
        }

        $emails = $clients->pluck('email')->filter()->toArray();
        $phones = $clients->pluck('phone')->filter()->toArray();

        $appMap = !empty($emails) || !empty($phones)
            ? Apporteur::whereIn('email', $emails)->orWhereIn('telephone', $phones)->get()->keyBy(function ($item) {
                return $item->email ?: $item->telephone;
            })
            : collect();

        $results = collect();
        $defaultRate = (float) \App\Models\Setting::get('default_apporteur_commission_rate', 0.00);

        foreach ($clients as $client) {
            $key = $client->email ?: $client->telephone;
            $appRec = $appMap->get($key);

            $results->push((object) [
                'id' => $appRec ? $appRec->id : $client->id,
                'nom' => $client->nom,
                'prenom' => $client->prenom,
                'code' => $client->formatted_reference,
                'telephone' => $client->telephone,
                'source' => $appRec ? 'Apporteur' : 'Client',
                'taux_commission' => ($appRec && $appRec->taux_commission !== null) ? (float)$appRec->taux_commission : $defaultRate,
            ]);
        }

        foreach ($apporteurs as $app) {
            if (!$results->contains('id', $app->id)) {
                $results->push((object) [
                    'id' => $app->id,
                    'nom' => $app->nom,
                    'prenom' => $app->prenom,
                    'code' => $app->code_apporteur ?? ('APP-' . $app->id),
                    'telephone' => $app->telephone,
                    'source' => 'Apporteur',
                    'taux_commission' => $app->taux_commission !== null ? (float)$app->taux_commission : $defaultRate,
                ]);
            }
        }

        return $results;
    }    public $auto_synced_client = true;

    public function selectApporteurFromSearch($id = null, $nom = null, $prenom = null, $taux = 0.00)
    {
        if ($id || $nom) {
            $this->apporteur_id = is_numeric($id) ? (int)$id : null;
            $this->nom_apporteur = trim(($nom ?? '') . ' ' . ($prenom ?? ''));
            $this->searchApporteur = $this->nom_apporteur;

            if ($this->apporteur_id && (empty($this->client_id) || $this->auto_synced_client)) {
                $this->client_id = $this->apporteur_id;
                $this->souscripteur = $this->nom_apporteur;
                $this->auto_synced_client = true;
            }

            if ((float)$this->prime_rc > 0 && $taux) {
                $this->commission_auto = round((float)$this->prime_rc * ((float)$taux / 100), 2);
            }
            return;
        }

        $this->apporteur_id = null;
        $this->nom_apporteur = '';
        $this->searchApporteur = '';
        $this->commission_auto = 0;
    }

    public function mount($contratId = null)
    {
        if (url()->previous() && !str_contains(url()->previous(), 'modifier') && !str_contains(url()->previous(), 'creer')) {
            session(['contract_form_return_url' => url()->previous()]);
        }

        if ($contratId) {
            $this->contratId = $contratId;
            $contrat = ContratAuto::withoutGlobalScopes()->with(['client', 'apporteur', 'vehicule'])->find($contratId)
                ?? ContratAuto::with(['client', 'apporteur', 'vehicule'])->findOrFail($contratId);
            
            // Explicitly set form properties to prevent accessor/toArray mapping issues
            $this->numero_contrat = $contrat->numero_contrat ?? $contrat->contract_number;
            $this->terme = (bool)$contrat->terme;
            $this->compagnie_id = $contrat->compagnie_id ?? $contrat->insurance_company_id;
            $this->police = $contrat->police ?? $contrat->policy_number;
            $this->avenant = $contrat->avenant;
            $this->type_affaire = $contrat->type_affaire ?? 'AN';
            $this->attestation = $contrat->attestation;
            $this->quittance = $contrat->quittance;

            $this->client_id = $contrat->client_id;
            $this->apporteur_id = $contrat->apporteur_id;
            $this->branch_id = $contrat->branch_id ?? $contrat->succursale_id;
            $this->product_id = $contrat->product_id ?? $contrat->insurance_type_id;
            $this->branche_code = $contrat->branche_code;
            $this->branche_libelle = $contrat->branche_libelle;

            // Dates
            $dateEffet = $contrat->date_effet ?? $contrat->start_date;
            $dateEcheance = $contrat->date_echeance ?? $contrat->end_date;
            $dateProd = $contrat->date_production;

            $this->date_effet = $dateEffet ? \Carbon\Carbon::parse($dateEffet)->format('Y-m-d') : '';
            $this->date_echeance = $dateEcheance ? \Carbon\Carbon::parse($dateEcheance)->format('Y-m-d') : '';
            $this->date_production = $dateProd ? \Carbon\Carbon::parse($dateProd)->format('Y-m-d') : '';
            if ($contrat->date_mise_circulation) {
                $this->date_mise_circulation = \Carbon\Carbon::parse($contrat->date_mise_circulation)->format('Y-m-d');
            }
            if ($contrat->date_resiliation) {
                $this->date_resiliation = \Carbon\Carbon::parse($contrat->date_resiliation)->format('Y-m-d');
            }

            // Vehicule attributes (check direct contract, polymorphic detail, and linked vehicule)
            $detail = ($contrat->details_id && $contrat->details_type && class_exists($contrat->details_type)) ? $contrat->details_type::find($contrat->details_id) : null;
            $vehicule = $contrat->vehicule;

            $this->usage = $contrat->usage ?? ($detail->usage ?? ($vehicule->usage ?? ''));
            $this->code_classe = $contrat->code_classe ?? ($detail->code_classe ?? '');
            $this->sous_classe = $contrat->sous_classe ?? ($detail->sous_classe ?? 'Definitive');
            $this->marque = $contrat->marque ?? ($detail->marque ?? ($vehicule->marque ?? ''));
            $this->modele = $contrat->modele ?? ($detail->modele ?? ($vehicule->modele ?? ''));
            $this->annee = $contrat->annee ?? ($detail->annee ?? ($vehicule->annee ?? null));
            $this->motorisation = $contrat->motorisation ?? ($detail->motorisation ?? ($vehicule->motorisation ?? ''));
            $this->matricule = $contrat->matricule ?? ($detail->matricule ?? ($vehicule->matricule ?? ''));
            $this->puissance_fiscale = $contrat->puissance_fiscale ?? ($detail->puissance_fiscale ?? ($vehicule->puissance_fiscale ?? null));
            $this->nb_places = $contrat->nb_places ?? ($detail->nb_places ?? ($vehicule->nb_places ?? null));
            $this->carburant = $contrat->carburant ?? ($detail->carburant ?? ($vehicule->type_carburant ?? ''));
            $this->nbr_mois = (int)($contrat->nbr_mois ?? ($detail->nbr_mois ?? 12));
            $this->valeur_vehicule = (float)($contrat->valeur_vehicule ?? ($detail->valeur_vehicule ?? 0));

            // Financials
            $this->prime_rc = (float)$contrat->prime_rc;
            $this->def_rec = (float)$contrat->def_rec;
            $this->tierce = (float)$contrat->tierce;
            $this->collision = (float)$contrat->collision;
            $this->vol = (float)$contrat->vol;
            $this->incendie = (float)$contrat->incendie;
            $this->bris_glace = (float)$contrat->bris_glace;
            $this->individuel = (float)$contrat->individuel;
            $this->taxe_auto = (float)$contrat->taxe_auto;
            $this->accessoire_auto_cie = (float)$contrat->accessoire_auto_cie;
            $this->timbre = (float)$contrat->timbre;
            $this->commission_auto = (float)$contrat->commission_auto;
            $this->tps_auto = (float)$contrat->tps_auto;
            $this->montant_pta = (float)$contrat->montant_pta;
            $this->montant_taxe_pta = (float)$contrat->montant_taxe_pta;
            $this->commission_pta = (float)$contrat->commission_pta;
            $this->tps_pta = (float)$contrat->tps_pta;
            $this->accessoires = (float)$contrat->accessoires;
            $calculatedPrime = round($this->primeNette + $this->totalTaxe, 2);
            $this->prime_totale = $calculatedPrime > 0 ? $calculatedPrime : (float)($contrat->prime_totale ?? $contrat->premium_amount ?? 0);

            if ($contrat->client) {
                $this->souscripteur = trim($contrat->client->nom . ' ' . $contrat->client->prenom);
            }
            if ($contrat->apporteur) {
                $this->nom_apporteur = trim($contrat->apporteur->nom . ' ' . $contrat->apporteur->prenom);
                $this->searchApporteur = $this->nom_apporteur;
            }

            // Calculate exact nbr_mois from loaded date_effet and date_echeance if present
            if ($this->date_effet && $this->date_echeance) {
                $start = \Carbon\Carbon::parse($this->date_effet);
                $end = \Carbon\Carbon::parse($this->date_echeance);
                $diff = (int)round($start->diffInDays($end) / 30);
                if ($diff > 0) {
                    $this->nbr_mois = $diff;
                }
            }

            $this->historiqueRenouvellementsList = $contrat->historiqueRenouvellements()->get();
        } else {
            $this->date_effet = Carbon::now()->format('Y-m-d');
            $this->date_production = Carbon::now()->format('Y-m-d');
            $this->calculateDates();

            // Default to AUTO product
            $defaultProduct = \App\Models\Product::where('code', 'AUTO')->first();
            if ($defaultProduct) {
                $this->product_id = $defaultProduct->id;
                $this->branche_code = $defaultProduct->code;
                $this->branche_libelle = $defaultProduct->nom;
            }
        }
    }

    public function updatedProductId($value)
    {
        $product = \App\Models\Product::find($value);
        if ($product) {
            $this->branche_code = $product->code;
            $this->branche_libelle = $product->nom;
        }
    }

    public function handleClientSelected($clientId)
    {
        $this->client_id = $clientId;
        $client = Client::findOrFail($clientId);
        $this->souscripteur = $client->nom . ' ' . $client->prenom;
        $this->auto_synced_client = false; // Manual selection overrode auto sync
    }

    public function handleApporteurSelected($payload = null)
    {
        if (is_array($payload)) {
            $apporteurId = is_numeric($payload['id'] ?? null) ? (int)$payload['id'] : null;
            $nom = is_string($payload['nom'] ?? '') ? $payload['nom'] : '';
            $prenom = is_string($payload['prenom'] ?? '') ? $payload['prenom'] : '';
            $taux = is_numeric($payload['taux'] ?? null) ? (float)$payload['taux'] : (float)\App\Models\Setting::get('default_apporteur_commission_rate', 0.00);

            $this->apporteur_id = $apporteurId;
            $this->nom_apporteur = trim($nom . ' ' . $prenom);
            $this->searchApporteur = $this->nom_apporteur;

            if ((float)$this->prime_rc > 0 && $taux) {
                $this->commission_auto = round((float)$this->prime_rc * ((float)$taux / 100), 2);
            }
            return;
        }

        if (is_numeric($payload)) {
            $this->apporteur_id = (int)$payload;
            $apporteur = Apporteur::find((int)$payload);
            if ($apporteur) {
                $this->nom_apporteur = trim($apporteur->nom . ' ' . $apporteur->prenom);
                $this->searchApporteur = $this->nom_apporteur;

                if ($apporteur->taux_commission && (float)$this->prime_rc > 0) {
                    $this->commission_auto = round((float)$this->prime_rc * ((float)$apporteur->taux_commission / 100), 2);
                }
                return;
            }
        }

        $this->apporteur_id = null;
        $this->nom_apporteur = '';
        $this->searchApporteur = '';
        $this->commission_auto = 0;
    }

    public function updatedApporteurId($value)
    {
        $this->handleApporteurSelected($value);
    }

    public function updatedDateEffet()
    {
        $this->calculateDates();
    }

    public function updatedNbrMois()
    {
        $this->calculateDates();
    }

    public function calculateDates()
    {
        if (!empty($this->date_effet) && is_numeric($this->nbr_mois)) {
            $this->date_echeance = Carbon::parse($this->date_effet)
                ->addMonths((int)$this->nbr_mois)
                ->format('Y-m-d');
        }
    }

    // Computed properties for the blade view
    public function getPrimeNetteProperty()
    {
        $sum = (float)$this->prime_rc +
               (float)$this->def_rec +
               (float)$this->tierce +
               (float)$this->collision +
               (float)$this->vol +
               (float)$this->incendie +
               (float)$this->bris_glace +
               (float)$this->individuel;

        return $sum > 0 ? $sum : (float)($this->prime_nette ?? 0);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'prime_rc', 'def_rec', 'tierce', 'collision', 'vol', 'incendie', 'bris_glace', 'individuel',
            'taxe_auto', 'accessoire_auto_cie', 'timbre', 'montant_pta', 'montant_taxe_pta', 'accessoires'
        ])) {
            $this->prime_totale = round($this->primeNette + $this->totalTaxe, 2);
        }
    }

    public function getTotalTaxeProperty()
    {
        return (float)$this->taxe_auto +
               (float)$this->accessoire_auto_cie +
               (float)$this->timbre +
               (float)$this->montant_taxe_pta +
               (float)$this->accessoires;
    }

    public function getPrimeTotaleProperty()
    {
        return round($this->primeNette + $this->totalTaxe, 2);
    }

    public function getTotalCommissionProperty()
    {
        return (float)$this->commission_auto + (float)$this->commission_pta;
    }

    public function getTotalTpsProperty()
    {
        return (float)$this->tps_auto + (float)$this->tps_pta;
    }

    public function getMargePourcentageProperty()
    {
        if ($this->product_id) {
            $prod = \App\Models\Product::find($this->product_id);
            return $prod ? (float)$prod->marge_pourcentage : 0.00;
        }
        return 0.00;
    }

    public function getMargeBruteHtProperty()
    {
        return $this->primeNette * ($this->margePourcentage / 100);
    }

    public function getBeneficeNetProperty()
    {
        return $this->margeBruteHt - $this->totalCommission;
    }

    public function save()
    {
        $this->prime_nette = round($this->primeNette, 2);
        $this->prime_totale = round($this->primeNette + $this->totalTaxe, 2);

        if (empty($this->numero_contrat)) {
            $this->numero_contrat = 'REF-' . date('Y') . '-' . str_pad($this->contratId ?? rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        $rules = [
            'numero_contrat' => 'required',
            'compagnie_id' => 'required',
            'police' => 'required',
            'client_id' => 'required',
            'date_effet' => 'required|date',
            'date_echeance' => 'required|date',
        ];

        $this->validate($rules);

        // Find or create vehicle dynamically by matricule
        if (!empty($this->matricule)) {
            $vehicule = \App\Models\Vehicule::updateOrCreate(
                ['matricule' => $this->matricule],
                [
                    'marque' => $this->marque ?? 'Inconnu',
                    'modele' => $this->modele ?? 'Inconnu',
                    'puissance_fiscale' => $this->puissance_fiscale,
                    'type_carburant' => $this->carburant,
                    'date_mise_circulation' => $this->date_mise_circulation ? Carbon::parse($this->date_mise_circulation) : null,
                ]
            );
        } else {
            $vehicule = \App\Models\Vehicule::firstOrCreate(
                ['matricule' => 'SANS-MATRICULE'],
                ['marque' => $this->marque ?? 'Autre', 'modele' => $this->modele ?? 'Autre']
            );
        }

        $data = [
            'numero_contrat' => $this->numero_contrat,
            'terme' => $this->terme,
            'compagnie_id' => $this->compagnie_id,
            'vehicule_id' => $vehicule->id,
            'police' => $this->police,
            'avenant' => $this->avenant,
            'type_affaire' => $this->type_affaire,
            'attestation' => $this->attestation,
            'quittance' => $this->quittance,
            'client_id' => $this->client_id,
            'souscripteur' => $this->souscripteur,
            'apporteur_id' => $this->apporteur_id,
            'branche_code' => $this->branche_code,
            'branche_libelle' => $this->branche_libelle,
            'branch_id' => $this->branch_id,
            'product_id' => $this->product_id,
            'usage' => $this->usage,
            'code_classe' => $this->code_classe,
            'sous_classe' => $this->sous_classe,
            'marque' => $this->marque,
            'modele' => $this->modele,
            'annee' => $this->annee,
            'motorisation' => $this->motorisation,
            'matricule' => $this->matricule,
            'puissance_fiscale' => $this->puissance_fiscale,
            'nb_places' => $this->nb_places,
            'carburant' => $this->carburant,
            'nbr_mois' => $this->nbr_mois,
            'valeur_vehicule' => $this->valeur_vehicule,
            'date_mise_circulation' => $this->date_mise_circulation ? Carbon::parse($this->date_mise_circulation) : null,
            'date_effet' => Carbon::parse($this->date_effet),
            'date_echeance' => Carbon::parse($this->date_echeance),
            'date_production' => Carbon::parse($this->date_production),
            'date_resiliation' => $this->date_resiliation ? Carbon::parse($this->date_resiliation) : null,
            'prime_rc' => $this->prime_rc,
            'def_rec' => $this->def_rec,
            'tierce' => $this->tierce,
            'collision' => $this->collision,
            'vol' => $this->vol,
            'incendie' => $this->incendie,
            'bris_glace' => $this->bris_glace,
            'individuel' => $this->individuel,
            'prime_nette' => $this->prime_nette,
            'taxe_auto' => $this->taxe_auto,
            'accessoire_auto_cie' => $this->accessoire_auto_cie,
            'timbre' => $this->timbre,
            'commission_auto' => $this->commission_auto,
            'tps_auto' => $this->tps_auto,
            'montant_pta' => $this->montant_pta,
            'montant_taxe_pta' => $this->montant_taxe_pta,
            'commission_pta' => $this->commission_pta,
            'tps_pta' => $this->tps_pta,
            'accessoires' => $this->accessoires,
            'prime_totale' => $this->prime_totale,
        ];

        $returnUrl = session('contract_form_return_url', route('automobile.index'));
        session()->forget('contract_form_return_url');

        // Separate auto-detail fields from contract core fields
        $autoDetailData = [
            'vehicule_id' => $vehicule->id,
            'usage' => $this->usage,
            'code_classe' => $this->code_classe,
            'sous_classe' => $this->sous_classe,
            'marque' => $this->marque,
            'modele' => $this->modele,
            'annee' => $this->annee,
            'motorisation' => $this->motorisation,
            'matricule' => $this->matricule,
            'puissance_fiscale' => $this->puissance_fiscale,
            'nb_places' => $this->nb_places,
            'carburant' => $this->carburant,
            'nbr_mois' => $this->nbr_mois,
            'valeur_vehicule' => $this->valeur_vehicule,
            'date_mise_circulation' => $this->date_mise_circulation ? Carbon::parse($this->date_mise_circulation) : null,
        ];

        $coreData = [
            'numero_contrat' => $this->numero_contrat,
            'terme' => $this->terme,
            'compagnie_id' => $this->compagnie_id,
            'police' => $this->police,
            'avenant' => $this->avenant,
            'type_affaire' => $this->type_affaire,
            'attestation' => $this->attestation,
            'quittance' => $this->quittance,
            'client_id' => $this->client_id,
            'souscripteur' => $this->souscripteur,
            'apporteur_id' => $this->apporteur_id,
            'branche_code' => $this->branche_code,
            'branche_libelle' => $this->branche_libelle,
            'branch_id' => $this->branch_id,
            'product_id' => $this->product_id,
            'date_effet' => Carbon::parse($this->date_effet),
            'date_echeance' => Carbon::parse($this->date_echeance),
            'date_production' => $this->date_production ? Carbon::parse($this->date_production) : now(),
            'date_resiliation' => $this->date_resiliation ? Carbon::parse($this->date_resiliation) : null,
            'prime_rc' => $this->prime_rc,
            'def_rec' => $this->def_rec,
            'tierce' => $this->tierce,
            'collision' => $this->collision,
            'vol' => $this->vol,
            'incendie' => $this->incendie,
            'bris_glace' => $this->bris_glace,
            'individuel' => $this->individuel,
            'taxe_auto' => $this->taxe_auto,
            'accessoire_auto_cie' => $this->accessoire_auto_cie,
            'timbre' => $this->timbre,
            'commission_auto' => $this->commission_auto,
            'tps_auto' => $this->tps_auto,
            'montant_pta' => $this->montant_pta,
            'montant_taxe_pta' => $this->montant_taxe_pta,
            'commission_pta' => $this->commission_pta,
            'tps_pta' => $this->tps_pta,
            'accessoires' => $this->accessoires,
            'prime_totale' => $this->prime_totale,
        ];

        $validSuccursaleId = null;
        if (!empty($this->branch_id) && class_exists(\App\Models\Succursale::class)) {
            if (\App\Models\Succursale::where('id', $this->branch_id)->exists()) {
                $validSuccursaleId = (int)$this->branch_id;
            }
        }

        if ($this->contratId) {
            $contrat = ContratAuto::withoutGlobalScopes()->find($this->contratId)
                ?? ContratAuto::findOrFail($this->contratId);

            // Update AutoContractDetail directly if it exists, or create one
            if ($contrat->details_id && $contrat->details_type && class_exists($contrat->details_type)) {
                $detailClass = $contrat->details_type;
                $detail = $detailClass::find($contrat->details_id);
                if ($detail) {
                    $detail->update($autoDetailData);
                    $coreData['vehicule_id'] = $detail->vehicule_id ?? $vehicule->id;
                }
            } else {
                $detail = \App\Models\AutoContractDetail::create($autoDetailData);
                $coreData['details_id'] = $detail->id;
                $coreData['details_type'] = \App\Models\AutoContractDetail::class;
                $coreData['vehicule_id'] = $detail->vehicule_id ?? $vehicule->id;
            }

            // Combine coreData, autoDetailData, and new schema columns for 100% update coverage
            $fullUpdateData = array_merge($coreData, $autoDetailData, [
                'contract_number' => $this->numero_contrat,
                'insurance_company_id' => $this->compagnie_id,
                'policy_number' => $this->police,
                'start_date' => Carbon::parse($this->date_effet),
                'end_date' => Carbon::parse($this->date_echeance),
                'premium_amount' => $this->prime_totale,
                'insurance_type_id' => $this->product_id,
                'succursale_id' => $validSuccursaleId,
            ]);

            $contrat->update($fullUpdateData);

            // Also sync the linked Vehicule model if present
            if ($vehicule && $vehicule->id) {
                $vehicule->update([
                    'marque' => $this->marque ?: $vehicule->marque,
                    'modele' => $this->modele ?: $vehicule->modele,
                    'matricule' => $this->matricule ?: $vehicule->matricule,
                    'puissance_fiscale' => $this->puissance_fiscale ?: $vehicule->puissance_fiscale,
                    'type_carburant' => $this->carburant ?: $vehicule->type_carburant,
                ]);
            }

            session()->flash('message', 'Contrat N° ' . $contrat->numero_contrat . ' mis à jour avec succès.');
        } else {
            // For creation, pass all fields and let booted() handle separation
            $createData = array_merge($coreData, $autoDetailData);
            $contrat = ContratAuto::create($createData);
            session()->flash('message', 'Contrat N° ' . $contrat->numero_contrat . ' créé avec succès.');
        }

        return redirect()->to($returnUrl);
    }

    public function render()
    {
        $compagnies = Compagnie::all();
        $apporteurs = Apporteur::all();
        $branches = AgenceBranch::all();
        $products = \App\Models\Product::where('statut', 'actif')->get();

        return view('livewire.automobile.formulaire-contrat', compact('compagnies', 'apporteurs', 'branches', 'products'))
            ->layout('layouts.app');
    }
}
