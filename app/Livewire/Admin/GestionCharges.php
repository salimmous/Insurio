<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AgencyExpense;
use App\Models\Succursale;

class GestionCharges extends Component
{
    public $expenses = [];
    public $succursales = [];

    // Form fields
    public $expenseId;
    public $title;
    public $category = 'loyer';
    public $amount;
    public $date_charge;
    public $description;
    public $succursale_id;

    // Filters
    public $filterCategory = '';
    public $filterSuccursale = '';

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'category' => 'required|string|in:loyer,electricite,eau,salaire,autre',
        'amount' => 'required|numeric|min:0',
        'date_charge' => 'required|date',
        'description' => 'nullable|string',
        'succursale_id' => 'nullable|exists:succursales,id',
    ];

    public function mount()
    {
        if (!auth()->user() || (!auth()->user()->hasRole('agency-admin') && !auth()->user()->hasRole('comptable'))) {
            abort(403, 'Accès non autorisé.');
        }

        $this->date_charge = now()->format('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $query = AgencyExpense::with('succursale')->latest('date_charge');

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterSuccursale) {
            $query->where('succursale_id', $this->filterSuccursale);
        }

        $this->expenses = $query->get();
        $this->succursales = Succursale::all();
    }

    public function updatedFilterCategory()
    {
        $this->loadData();
    }

    public function updatedFilterSuccursale()
    {
        $this->loadData();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $expense = AgencyExpense::findOrFail($id);
        $this->expenseId = $expense->id;
        $this->title = $expense->title;
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->date_charge = $expense->date_charge->format('Y-m-d');
        $this->description = $expense->description;
        $this->succursale_id = $expense->succursale_id;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $expense = AgencyExpense::findOrFail($this->expenseId);
            $expense->update($validated);
            session()->flash('message', 'La charge a été modifiée avec succès.');
        } else {
            AgencyExpense::create($validated);
            session()->flash('message', 'La charge a été ajoutée avec succès.');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->loadData();
    }

    public function delete($id)
    {
        $expense = AgencyExpense::findOrFail($id);
        $expense->delete();
        session()->flash('message', 'La charge a été supprimée avec succès.');
        $this->loadData();
    }

    private function resetForm()
    {
        $this->expenseId = null;
        $this->title = '';
        $this->category = 'loyer';
        $this->amount = '';
        $this->date_charge = now()->format('Y-m-d');
        $this->description = '';
        $this->succursale_id = null;
    }

    public function render()
    {
        return view('livewire.admin.gestion-charges')
            ->layout('layouts.app');
    }
}
