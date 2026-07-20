<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dossier;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Compagnie;
use App\Models\Succursale;
use App\Models\Employe;
use App\Models\User;
use App\Services\DossierAutomationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GestionDossiers extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $filterPriority = '';
    public $filterUrgency = '';
    public $filterSuccursale = '';
    public $filterEmployee = '';

    // Create Modal Form Fields
    public $showCreateModal = false;
    public $type = 'claim';
    public $client_id = '';
    public $contract_id = '';
    public $compagnie_id = '';
    public $succursale_id = '';
    public $assigned_employee_id = '';
    public $priority = 'medium';
    public $urgency = 'medium';
    public $notes = '';

    // Form lists
    public $clientsList = [];
    public $contractsList = [];
    public $compagniesList = [];
    public $succursalesList = [];
    public $employeesList = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPriority' => ['except' => ''],
        'filterUrgency' => ['except' => ''],
        'filterSuccursale' => ['except' => ''],
        'filterEmployee' => ['except' => ''],
    ];

    public function mount()
    {
        if (!auth()->check()) {
            abort(403);
        }

        $this->clientsList = Client::orderBy('last_name')->get();
        $this->compagniesList = Compagnie::all();
        $this->succursalesList = Succursale::all();
        
        // Auto-select succursale for non-admin users
        $user = auth()->user();
        if (!$user->hasRole('agency-admin')) {
            $emp = Employe::withoutGlobalScopes()->where('user_id', $user->id)->first();
            if ($emp) {
                $this->succursale_id = $emp->succursale_id;
                $this->filterSuccursale = $emp->succursale_id;
            }
        } else {
            $siege = Succursale::where('is_siege', true)->first();
            if ($siege) {
                $this->succursale_id = $siege->id;
            }
        }

        $this->updatedSuccursaleId();
    }

    public function updatedSuccursaleId()
    {
        if ($this->succursale_id) {
            $this->employeesList = Employe::where('succursale_id', $this->succursale_id)->get();
        } else {
            $this->employeesList = [];
        }
    }

    public function updatedClientId()
    {
        if ($this->client_id) {
            $this->contractsList = Contract::where('client_id', $this->client_id)->get();
        } else {
            $this->contractsList = [];
        }
    }

    public function openCreateModal()
    {
        $this->resetErrorBag();
        $this->client_id = '';
        $this->contract_id = '';
        $this->compagnie_id = '';
        $this->notes = '';
        $this->showCreateModal = true;
    }

    public function createDossier()
    {
        $this->validate([
            'type' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'compagnie_id' => 'nullable|exists:compagnies,id',
            'succursale_id' => 'required|exists:succursales,id',
            'assigned_employee_id' => 'nullable|exists:employes,id',
            'priority' => 'required|string',
            'urgency' => 'required|string',
        ]);

        $dossier = Dossier::create([
            'type' => $this->type,
            'status' => 'open',
            'priority' => $this->priority,
            'urgency' => $this->urgency,
            'succursale_id' => $this->succursale_id,
            'assigned_employee_id' => $this->assigned_employee_id ?: null,
            'client_id' => $this->client_id,
            'contract_id' => $this->contract_id ?: null,
            'compagnie_id' => $this->compagnie_id ?: null,
            'creation_date' => Carbon::now()->toDateString(),
            'progress' => 10,
            'checklist' => $this->getDefaultChecklist($this->type),
        ]);

        // Trigger automation
        DossierAutomationService::handleDossierCreation($dossier);

        session()->flash('success', "Le dossier {$dossier->dossier_number} a été créé avec succès.");
        $this->showCreateModal = false;
        
        return redirect()->route('admin.dossiers.workspace', $dossier->id);
    }

    private function getDefaultChecklist($type): array
    {
        $base = [
            ['name' => 'Ouverture du dossier', 'completed' => true],
            ['name' => 'Vérification des pièces', 'completed' => false],
            ['name' => 'Validation Compagnie d\'Assurance', 'completed' => false],
            ['name' => 'Règlement / Paiement', 'completed' => false],
            ['name' => 'Clôture du dossier', 'completed' => false],
        ];

        if ($type === 'claim') {
            return [
                ['name' => 'Déclaration de sinistre', 'completed' => true],
                ['name' => 'Constat amiable uploadé', 'completed' => false],
                ['name' => 'Photos du sinistre ajoutées', 'completed' => false],
                ['name' => 'Rapport de police (si applicable)', 'completed' => false],
                ['name' => 'Rapport de l\'expert', 'completed' => false],
                ['name' => 'Devis du garage partenaire', 'completed' => false],
                ['name' => 'Accord de prise en charge Compagnie', 'completed' => false],
                ['name' => 'Règlement / Réparation effectuée', 'completed' => false],
                ['name' => 'Clôture & archivage', 'completed' => false],
            ];
        }

        return $base;
    }

    public function getDossierStatsProperty(): array
    {
        $all = Dossier::query();
        
        $openCount = (clone $all)->whereNotIn('status', ['resolved', 'closed', 'cancelled'])->count();
        $urgentCount = (clone $all)->whereIn('priority', ['high', 'critical'])
            ->whereNotIn('status', ['resolved', 'closed', 'cancelled'])
            ->count();
        
        $waitingClient = (clone $all)->where('status', 'waiting_client')->count();
        $waitingCompany = (clone $all)->where('status', 'waiting_company')->count();
        $waitingExpert = (clone $all)->where('status', 'waiting_expert')->count();
        
        // Average Resolution Time (closed dossiers)
        $closedDossiers = (clone $all)->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('closing_date')
            ->select(['creation_date', 'closing_date'])
            ->get();
            
        $totalDays = 0;
        $count = $closedDossiers->count();
        foreach ($closedDossiers as $cd) {
            $totalDays += Carbon::parse($cd->creation_date)->diffInDays(Carbon::parse($cd->closing_date));
        }
        $avgDays = $count > 0 ? round($totalDays / $count) : 0;

        return [
            'open' => $openCount,
            'urgent' => $urgentCount,
            'waiting_client' => $waitingClient,
            'waiting_company' => $waitingCompany,
            'waiting_expert' => $waitingExpert,
            'avg_resolution' => $avgDays ?: 4, // Default fallback if no closed cases
        ];
    }

    public function render()
    {
        $query = Dossier::with(['client', 'assignedEmployee', 'succursale', 'contract']);

        // Search Filters
        if ($this->search) {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('dossier_number', 'like', $s)
                  ->orWhere('type', 'like', $s)
                  ->orWhereHas('client', function ($cq) use ($s) {
                      $cq->where('last_name', 'like', $s)
                         ->orWhere('first_name', 'like', $s)
                         ->orWhere('company_name', 'like', $s)
                         ->orWhere('phone', 'like', $s)
                         ->orWhere('email', 'like', $s);
                  })
                  ->orWhereHas('contract', function ($coq) use ($s) {
                      $coq->where('policy_number', 'like', $s)
                          ->orWhere('contract_number', 'like', $s);
                  });
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }
        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }
        if ($this->filterUrgency) {
            $query->where('urgency', $this->filterUrgency);
        }
        if ($this->filterSuccursale) {
            $query->where('succursale_id', $this->filterSuccursale);
        }
        if ($this->filterEmployee) {
            $query->where('assigned_employee_id', $this->filterEmployee);
        }

        $dossiers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.gestion-dossiers', [
            'dossiers' => $dossiers,
            'stats' => $this->dossierStats,
        ])->layout('layouts.app');
    }
}
