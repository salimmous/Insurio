<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class GestionEntreprises extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $clientId = null;
    public $nom = '';
    public $email = '';
    public $telephone = '';
    public $cin = ''; // ICE / RC
    public $adresse = '';
    public $solvabilite = 'solvable';
    public $incident = false;

    public $isModalOpen = false;

    protected $rules = [
        'nom' => 'required|string|max:255', // Raison Sociale
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:50',
        'cin' => 'nullable|string|max:50', // ICE / RC
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
                'prenom' => '', // Companies have no prenom
                'email' => $this->email,
                'telephone' => $this->telephone,
                'cin' => $this->cin,
                'adresse' => $this->adresse,
                'solvabilite' => $this->solvabilite,
                'incident' => $this->incident,
                'type' => 'entreprise',
            ]
        );

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
        $query = Client::where('type', 'entreprise');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('cin', 'like', '%' . $this->search . '%')
                  ->orWhere('telephone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.gestion-entreprises', [
            'entreprises' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
