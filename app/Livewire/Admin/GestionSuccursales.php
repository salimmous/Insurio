<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Succursale;
use App\Models\Employe;
use Illuminate\Support\Str;

class GestionSuccursales extends Component
{
    public $succursales = [];
    public $employes = [];
    
    // Form fields
    public $succursaleId;
    public $code_succursale;
    public $nom;
    public $adresse;
    public $ville;
    public $telephone;
    public $responsable_id;
    public $is_siege = false;
    public $is_active = true;

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'code_succursale' => 'required|string|max:50|unique:succursales,code_succursale',
        'nom' => 'required|string|max:255',
        'adresse' => 'nullable|string|max:255',
        'ville' => 'nullable|string|max:100',
        'telephone' => 'nullable|string|max:50',
        'responsable_id' => 'nullable|exists:employes,id',
        'is_siege' => 'boolean',
        'is_active' => 'boolean',
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
        $this->succursales = Succursale::with('responsable')
            ->withCount('employes')
            ->withCount('contrats')
            ->get();
            
        // Load employes list for responsable dropdown (bypassing global scopes if necessary)
        $this->employes = Employe::all();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        // Auto-generate code
        $this->code_succursale = 'SUC-' . strtoupper(Str::random(5));
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $succursale = Succursale::findOrFail($id);
        $this->succursaleId = $succursale->id;
        $this->code_succursale = $succursale->code_succursale;
        $this->nom = $succursale->nom;
        $this->adresse = $succursale->adresse;
        $this->ville = $succursale->ville;
        $this->telephone = $succursale->telephone;
        $this->responsable_id = $succursale->responsable_id;
        $this->is_siege = $succursale->is_siege;
        $this->is_active = $succursale->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['code_succursale'] = 'required|string|max:50|unique:succursales,code_succursale,' . $this->succursaleId;
        }

        $validated = $this->validate($rules);

        // If is_siege is set to true, reset other sieges
        if ($this->is_siege) {
            Succursale::where('is_siege', true)->update(['is_siege' => false]);
        }

        if ($this->isEditing) {
            $succursale = Succursale::findOrFail($this->succursaleId);
            $succursale->update($validated);
        } else {
            Succursale::create($validated);
        }

        $this->showModal = false;
        $this->loadData();
        $this->dispatch('swal:success', ['message' => 'Succursale enregistrée avec succès.']);
    }

    public function toggleStatus($id)
    {
        $succursale = Succursale::findOrFail($id);
        $succursale->is_active = !$succursale->is_active;
        $succursale->save();
        
        $this->loadData();
    }

    public function resetForm()
    {
        $this->succursaleId = null;
        $this->code_succursale = '';
        $this->nom = '';
        $this->adresse = '';
        $this->ville = '';
        $this->telephone = '';
        $this->responsable_id = null;
        $this->is_siege = false;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.gestion-succursales')
            ->layout('layouts.app');
    }
}
