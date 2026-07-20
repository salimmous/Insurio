<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class GestionClients extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $clientId = null;
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $cin = '';
    public $adresse = '';
    public $solvabilite = 'solvable';
    public $incident = false;

    public $isModalOpen = false;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
        'cin' => 'nullable|string|max:50',
        'adresse' => 'nullable|string|max:500',
        'solvabilite' => 'required|in:solvable,non-solvable',
        'incident' => 'required|boolean',
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
            $this->nom = $client->nom;
            $this->prenom = $client->prenom;
            $this->email = $client->email;
            $this->telephone = $client->telephone;
            $this->cin = $client->cin;
            $this->adresse = $client->adresse;
            $this->solvabilite = $client->solvabilite;
            $this->incident = (bool)$client->incident;
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
        $this->nom = '';
        $this->prenom = '';
        $this->email = '';
        $this->telephone = '';
        $this->cin = '';
        $this->adresse = '';
        $this->solvabilite = 'solvable';
        $this->incident = false;
    }

    public function save()
    {
        $this->validate();

        Client::updateOrCreate(
            ['id' => $this->clientId],
            [
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'cin' => $this->cin,
                'adresse' => $this->adresse,
                'solvabilite' => $this->solvabilite,
                'incident' => $this->incident,
                'type' => 'particulier',
            ]
        );

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => $this->clientId ? 'Client modifié avec succès.' : 'Client créé avec succès.']);
    }

    public function delete($id)
    {
        $client = Client::findOrFail($id);
        
        // Prevent delete if client has contracts
        if ($client->contrats()->exists()) {
            $this->dispatch('swal:error', ['message' => 'Impossible de supprimer ce client car il possède des contrats associés.']);
            return;
        }

        $client->delete();
        $this->dispatch('swal:success', ['message' => 'Client supprimé avec succès.']);
    }

    public function render()
    {
        $query = Client::where('type', 'particulier');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%')
                  ->orWhere('cin', 'like', '%' . $this->search . '%')
                  ->orWhere('telephone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.gestion-clients', [
            'clients' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
