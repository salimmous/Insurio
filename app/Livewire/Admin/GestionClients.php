<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class GestionClients extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $clientId = null;
    public $reference = '';
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $whatsapp_number = '';
    public $cin = '';
    public $passport = '';
    public $date_of_birth = '';
    public $profession = '';
    public $address = '';
    public $city = '';
    public $notes = '';
    public $solvabilite = 'solvable';
    public $incident = false;
    public $entreprise_id = null;

    // Compatibility public properties for older tests
    public $nom = '';
    public $prenom = '';
    public $telephone = '';
    public $adresse = '';

    public $isModalOpen = false;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'whatsapp_number' => 'nullable|string|max:50',
        'cin' => 'nullable|string|max:50',
        'passport' => 'nullable|string|max:50',
        'date_of_birth' => 'nullable|date',
        'profession' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:255',
        'notes' => 'nullable|string|max:1000',
        'solvabilite' => 'required|in:solvable,non-solvable',
        'incident' => 'required|boolean',
        'entreprise_id' => 'nullable|integer|exists:clients,id',
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
            $this->reference = $client->formatted_reference;
            $this->first_name = $client->first_name;
            $this->last_name = $client->last_name;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->whatsapp_number = $client->whatsapp_number;
            $this->cin = $client->cin;
            $this->passport = $client->passport;
            $this->date_of_birth = $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->format('Y-m-d') : '';
            $this->profession = $client->profession;
            $this->address = $client->address;
            $this->city = $client->city;
            $this->notes = $client->notes;
            $this->solvabilite = $client->solvabilite;
            $this->incident = (bool)$client->incident;
            $this->entreprise_id = $client->entreprise_id;

            $this->nom = $this->last_name;
            $this->prenom = $this->first_name;
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
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
        $this->whatsapp_number = '';
        $this->cin = '';
        $this->passport = '';
        $this->date_of_birth = '';
        $this->profession = '';
        $this->address = '';
        $this->city = '';
        $this->notes = '';
        $this->solvabilite = 'solvable';
        $this->incident = false;
        $this->entreprise_id = null;

        $this->nom = '';
        $this->prenom = '';
        $this->telephone = '';
        $this->adresse = '';
    }

    public function save()
    {
        if ($this->nom) {
            $this->last_name = $this->nom;
        }
        if ($this->prenom) {
            $this->first_name = $this->prenom;
        }
        if ($this->telephone) {
            $this->phone = $this->telephone;
        }
        if ($this->adresse) {
            $this->address = $this->adresse;
        }

        $this->validate();

        $data = [
            'uuid' => $this->clientId ? Client::findOrFail($this->clientId)->uuid : (string) Str::uuid(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_number' => $this->whatsapp_number,
            'cin' => $this->cin,
            'passport' => $this->passport,
            'date_of_birth' => $this->date_of_birth ?: null,
            'profession' => $this->profession,
            'address' => $this->address,
            'city' => $this->city,
            'notes' => $this->notes,
            'solvabilite' => $this->solvabilite,
            'incident' => $this->incident,
            'entreprise_id' => $this->entreprise_id ?: null,
            'client_type' => 'individual',
        ];

        if (Schema::hasColumn('clients', 'reference')) {
            $data['reference'] = $this->reference ?: 'CL-' . str_pad(($this->clientId ?? ((Client::max('id') ?? 0) + 1)), 5, '0', STR_PAD_LEFT);
        }

        Client::updateOrCreate(
            ['id' => $this->clientId],
            $data
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
        $query = Client::where('client_type', 'individual');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('cin', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . str_replace(['CL-', 'REF-'], '', $this->search) . '%');

                if (Schema::hasColumn('clients', 'reference')) {
                    $q->orWhere('reference', 'like', '%' . $this->search . '%');
                }
            });
        }

        $entreprises = Client::where('client_type', 'company')->orderBy('last_name')->get();

        return view('livewire.admin.gestion-clients', [
            'clients' => $query->latest()->paginate(10),
            'entreprises' => $entreprises,
        ])->layout('layouts.app');
    }
}
