<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use Illuminate\Support\Str;

class GestionEntreprises extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $clientId = null;
    public $reference = '';
    public $nom = ''; // Maps to last_name / company_name
    public $email = '';
    public $phone = '';
    public $cin = ''; // ICE / RC
    public $gerant = ''; // Gérant
    public $num_permis = ''; // N° Permis
    public $address = '';
    public $solvabilite = 'solvable';
    public $incident = false;

    // Compatibility fields
    public $telephone = '';
    public $adresse = '';

    public $isModalOpen = false;

    protected $rules = [
        'reference' => 'nullable|string|max:50',
        'nom' => 'required|string|max:255', // Raison Sociale
        'gerant' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'cin' => 'nullable|string|max:50', // ICE / RC
        'num_permis' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:500',
        'solvabilite' => 'nullable|string',
        'incident' => 'nullable|boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        $this->clientId = $id;

        if ($id) {
            $client = Client::findOrFail($id);
            $this->reference = $client->reference;
            $this->nom = $client->last_name;
            $this->gerant = $client->gerant;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->cin = $client->cin;
            $this->num_permis = $client->num_permis;
            $this->address = $client->address;
            $this->solvabilite = $client->solvabilite ?? 'solvable';
            $this->incident = (bool)$client->incident;
            
            $this->telephone = $this->phone;
            $this->adresse = $this->address;
        } else {
            $this->resetForm();
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->clientId = null;
        $this->reference = '';
        $this->nom = '';
        $this->gerant = '';
        $this->email = '';
        $this->phone = '';
        $this->cin = '';
        $this->num_permis = '';
        $this->address = '';
        $this->solvabilite = 'solvable';
        $this->incident = false;

        $this->telephone = '';
        $this->adresse = '';
    }

    public function save()
    {
        if ($this->telephone) {
            $this->phone = $this->telephone;
        }
        if ($this->adresse) {
            $this->address = $this->adresse;
        }

        $this->validate();

        $client = Client::updateOrCreate(
            ['id' => $this->clientId],
            [
                'uuid' => $this->clientId ? Client::findOrFail($this->clientId)->uuid : (string) Str::uuid(),
                'reference' => !empty($this->reference) ? $this->reference : null,
                'last_name' => $this->nom,
                'first_name' => '', // Companies have no first_name
                'company_name' => $this->nom,
                'gerant' => $this->gerant,
                'email' => $this->email,
                'phone' => $this->phone,
                'cin' => $this->cin,
                'num_permis' => $this->num_permis,
                'address' => $this->address,
                'solvabilite' => $this->solvabilite ?? 'solvable',
                'incident' => $this->incident ?? false,
                'client_type' => 'company',
            ]
        );

        // Ensure reference is generated if not provided
        if (empty($client->reference)) {
            $client->update(['reference' => 'ENT-' . str_pad($client->id, 5, '0', STR_PAD_LEFT)]);
        }

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => $this->clientId ? 'Entreprise modifiée avec succès.' : 'Entreprise créée avec succès.']);
    }

    public function delete($id)
    {
        $client = Client::findOrFail($id);
        
        // Prevent delete if client has contracts
        if ($client->contrats()->exists()) {
            $this->dispatch('swal:error', ['message' => 'Impossible de supprimer cette entreprise car elle possède des contrats associés.']);
            return;
        }

        $client->delete();
        $this->dispatch('swal:success', ['message' => 'Entreprise supprimée avec succès.']);
    }

    public function render()
    {
        $query = Client::where('client_type', 'company');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('reference', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('cin', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.gestion-entreprises', [
            'entreprises' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
