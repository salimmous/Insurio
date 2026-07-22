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

    // Bloc PTA Calculations
    public $montant_pta = 0;
    public $montant_taxe_pta = 0;
    public $commission_pta = 0;
    public $tps_pta = 0;
    public $accessoires = 0; // other accessories

    protected $listeners = ['clientSelected' => 'handleClientSelected'];

    public function mount($contratId = null)
    {
        $this->date_effet = Carbon::now()->format('Y-m-d');
        $this->date_production = Carbon::now()->format('Y-m-d');
        $this->calculateDates();

        if ($contratId) {
            $this->contratId = $contratId;
            $contrat = ContratAuto::findOrFail($contratId);
            $this->fill($contrat->toArray());
            $this->product_id = $contrat->product_id;
            $this->date_effet = $contrat->date_effet ? \Carbon\Carbon::parse($contrat->date_effet)->format('Y-m-d') : '';
            $this->date_echeance = $contrat->date_echeance ? \Carbon\Carbon::parse($contrat->date_echeance)->format('Y-m-d') : '';
            $this->date_production = $contrat->date_production ? \Carbon\Carbon::parse($contrat->date_production)->format('Y-m-d') : '';
            if ($contrat->date_mise_circulation) {
                $this->date_mise_circulation = \Carbon\Carbon::parse($contrat->date_mise_circulation)->format('Y-m-d');
            }
            if ($contrat->date_resiliation) {
                $this->date_resiliation = \Carbon\Carbon::parse($contrat->date_resiliation)->format('Y-m-d');
            }
            if ($contrat->client) {
                $this->souscripteur = $contrat->client->nom . ' ' . $contrat->client->prenom;
            }
            if ($contrat->apporteur) {
                $this->nom_apporteur = $contrat->apporteur->nom . ' ' . $contrat->apporteur->prenom;
            }
        } else {
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
    }

    public function updatedApporteurId($value)
    {
        if ($value) {
            $apporteur = Apporteur::find($value);
            if ($apporteur) {
                $this->nom_apporteur = $apporteur->nom . ' ' . $apporteur->prenom;
            }
        } else {
            $this->nom_apporteur = '';
        }
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
        return (float)$this->prime_rc +
               (float)$this->def_rec +
               (float)$this->tierce +
               (float)$this->collision +
               (float)$this->vol +
               (float)$this->incendie +
               (float)$this->bris_glace +
               (float)$this->individuel;
    }

    public function getTotalTaxeProperty()
    {
        return (float)$this->taxe_auto + (float)$this->montant_taxe_pta;
    }

    public function getPrimeTotaleProperty()
    {
        return $this->prime_nette +
               (float)$this->taxe_auto +
               (float)$this->timbre +
               (float)$this->montant_pta +
               (float)$this->montant_taxe_pta +
               (float)$this->accessoires;
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
        $rules = [
            'numero_contrat' => 'required|unique:contracts,numero_contrat,' . $this->contratId,
            'compagnie_id' => 'required|exists:compagnies,id',
            'police' => 'required',
            'client_id' => 'required|exists:clients,id',
            'date_effet' => 'required|date',
            'date_echeance' => 'required|date|after:date_effet',
            'prime_rc' => 'required|numeric|min:0',
        ];

        $this->validate($rules);

        // Find or create vehicle dynamically by matricule
        if (!empty($this->matricule)) {
            $vehicule = \App\Models\Vehicule::firstOrCreate(
                ['matricule' => $this->matricule],
                [
                    'marque' => $this->marque ?? 'Inconnu',
                    'modele' => $this->marque ?? 'Inconnu',
                    'puissance_fiscale' => $this->puissance_fiscale,
                    'type_carburant' => $this->carburant,
                    'date_mise_circulation' => $this->date_mise_circulation ? Carbon::parse($this->date_mise_circulation) : null,
                ]
            );
        } else {
            $vehicule = \App\Models\Vehicule::firstOrCreate(
                ['matricule' => 'SANS-MATRICULE'],
                ['marque' => 'Autre', 'modele' => 'Autre']
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

        if ($this->contratId) {
            $contrat = ContratAuto::findOrFail($this->contratId);
            $contrat->update($data);
            session()->flash('message', 'Contrat mis à jour avec succès.');
        } else {
            ContratAuto::create($data);
            session()->flash('message', 'Contrat créé avec succès.');
        }

        return redirect()->route('automobile.index');
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
