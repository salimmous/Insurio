<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employe;
use App\Models\Succursale;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class GestionEmployes extends Component
{
    public $employes = [];
    public $succursales = [];
    public $usersWithoutEmployee = [];

    // Form fields
    public $employeId;
    public $user_id;
    public $matricule_employe;
    public $nom;
    public $prenom;
    public $cin;
    public $telephone;
    public $email;
    public $succursale_id;
    public $poste; // Administrateur, Responsable succursale, Agent commercial, Comptable, Consultation
    public $taux_commission_defaut = 0.00;
    public $date_embauche;
    public $date_sortie;
    public $statut = 'actif';

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'user_id' => 'nullable|exists:users,id',
        'matricule_employe' => 'required|string|max:50|unique:employes,matricule_employe',
        'nom' => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'cin' => 'nullable|string|max:50',
        'telephone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:150',
        'succursale_id' => 'required|exists:succursales,id',
        'poste' => 'required|string|in:Administrateur,Responsable succursale,Agent commercial,Comptable,Consultation',
        'taux_commission_defaut' => 'required|numeric|min:0|max:100',
        'date_embauche' => 'nullable|date',
        'date_sortie' => 'nullable|date',
        'statut' => 'required|string|in:actif,inactif',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasRole('agency-admin')) {
            abort(403, 'Accès non autorisé.');
        }
        $this->loadData();
    }

    public function loadData()
    {
        $this->employes = Employe::with(['succursale', 'user'])->get();
        $this->succursales = Succursale::all();
        
        // Find users that are not yet linked to an employee (excluding current editing user_id)
        $linkedUserIds = Employe::whereNotNull('user_id')
            ->when($this->employeId, function($q) {
                $q->where('id', '!=', $this->employeId);
            })
            ->pluck('user_id')
            ->toArray();
            
        $this->usersWithoutEmployee = User::whereNotIn('id', $linkedUserIds)->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->matricule_employe = 'EMP-' . strtoupper(Str::random(5));
        $this->isEditing = false;
        $this->loadData();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $employe = Employe::findOrFail($id);
        $this->employeId = $employe->id;
        $this->user_id = $employe->user_id;
        $this->matricule_employe = $employe->matricule_employe;
        $this->nom = $employe->nom;
        $this->prenom = $employe->prenom;
        $this->cin = $employe->cin;
        $this->telephone = $employe->telephone;
        $this->email = $employe->email;
        $this->succursale_id = $employe->succursale_id;
        $this->poste = $employe->poste;
        $this->taux_commission_defaut = $employe->taux_commission_defaut;
        $this->date_embauche = $employe->date_embauche ? $employe->date_embauche->format('Y-m-d') : null;
        $this->date_sortie = $employe->date_sortie ? $employe->date_sortie->format('Y-m-d') : null;
        $this->statut = $employe->statut;

        $this->isEditing = true;
        $this->loadData();
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['matricule_employe'] = 'required|string|max:50|unique:employes,matricule_employe,' . $this->employeId;
        }

        $validated = $this->validate($rules);

        if ($this->isEditing) {
            $employe = Employe::findOrFail($this->employeId);
            $employe->update($validated);
        } else {
            $employe = Employe::create($validated);
        }

        // Sync Spatie role if user is linked
        if ($employe->user_id) {
            $user = User::find($employe->user_id);
            if ($user) {
                // Map employee post to Spatie role
                $roleMap = [
                    'Administrateur' => 'agency-admin',
                    'Responsable succursale' => 'responsable-succursale',
                    'Agent commercial' => 'agent-commercial',
                    'Comptable' => 'comptable',
                    'Consultation' => 'consultation',
                ];

                $roleName = $roleMap[$employe->poste] ?? 'consultation';
                
                // Clear existing roles and assign the new one
                $user->syncRoles([$roleName]);
            }
        }

        $this->showModal = false;
        $this->loadData();
        $this->dispatch('swal:success', ['message' => 'Employé enregistré avec succès.']);
    }

    public function resetForm()
    {
        $this->employeId = null;
        $this->user_id = null;
        $this->matricule_employe = '';
        $this->nom = '';
        $this->prenom = '';
        $this->cin = '';
        $this->telephone = '';
        $this->email = '';
        $this->succursale_id = null;
        $this->poste = '';
        $this->taux_commission_defaut = 0.00;
        $this->date_embauche = null;
        $this->date_sortie = null;
        $this->statut = 'actif';
    }

    public function render()
    {
        return view('livewire.admin.gestion-employes')
            ->layout('layouts.app');
    }
}
