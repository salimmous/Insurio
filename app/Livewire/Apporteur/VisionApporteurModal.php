<?php

namespace App\Livewire\Apporteur;

use Livewire\Component;
use App\Models\Client;
use App\Models\Setting;

class VisionApporteurModal extends Component
{
    public $search = '';
    public $isOpen = false;
    public $showCreateForm = false;

    // Form fields for quick client creation
    public $new_type = 'particulier';
    public $new_nom = '';
    public $new_prenom = '';
    public $new_telephone = '';
    public $new_email = '';
    public $new_cin = '';

    protected $listeners = ['openVisionApporteur' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->search = '';
        $this->showCreateForm = false;
        $this->reset(['new_nom', 'new_prenom', 'new_telephone', 'new_email', 'new_cin']);
    }

    public function close()
    {
        $this->isOpen = false;
        $this->showCreateForm = false;
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if ($this->showCreateForm && !empty($this->search)) {
            $this->new_nom = $this->search;
        }
    }

    public function saveNewClient()
    {
        $this->validate([
            'new_nom' => 'required|string|max:255',
            'new_telephone' => 'nullable|string|max:50',
            'new_email' => 'nullable|email|max:255',
        ], [
            'new_nom.required' => 'Le nom du client est obligatoire.',
        ]);

        $nextId = (Client::max('id') ?? 0) + 1;
        $code = 'CL-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $client = Client::create([
            'nom' => trim($this->new_nom),
            'prenom' => trim($this->new_prenom),
            'first_name' => trim($this->new_prenom),
            'last_name' => trim($this->new_nom),
            'telephone' => trim($this->new_telephone),
            'phone' => trim($this->new_telephone),
            'email' => trim($this->new_email),
            'cin' => trim($this->new_cin),
            'type' => $this->new_type,
            'client_type' => $this->new_type === 'entreprise' ? 'company' : 'individual',
            'reference' => $code,
            'code_client' => $code,
        ]);

        // Auto select newly created client
        $this->selectApporteur($client->id, $client->nom, $client->prenom ?? '', 0, $client->id);

        $this->reset(['new_nom', 'new_prenom', 'new_telephone', 'new_email', 'new_cin', 'showCreateForm']);
    }

    public function selectApporteur($id, $nom = '', $prenom = '', $taux = 0.00, $clientId = null)
    {
        $this->dispatch('apporteurSelected', [
            'id' => is_numeric($id) ? (int)$id : null,
            'client_id' => is_numeric($clientId) ? (int)$clientId : (is_numeric($id) ? (int)$id : null),
            'nom' => $nom,
            'prenom' => $prenom,
            'taux' => (float)$taux,
        ]);
        $this->close();
    }

    public function clearApporteur()
    {
        $this->dispatch('apporteurSelected', null);
        $this->close();
    }

    public function render()
    {
        $query = trim($this->search);
        $defaultRate = (float) Setting::get('default_apporteur_commission_rate', 12.50);

        // Fetch ONLY from Clients table
        $clientsQuery = Client::query();
        if (!empty($query)) {
            $clientsQuery->where(function ($q) use ($query) {
                $q->where('nom', 'like', '%' . $query . '%')
                  ->orWhere('prenom', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('telephone', 'like', '%' . $query . '%')
                  ->orWhere('phone', 'like', '%' . $query . '%')
                  ->orWhere('code_client', 'like', '%' . $query . '%')
                  ->orWhere('reference', 'like', '%' . $query . '%');
            });
        }

        $clientsList = $clientsQuery->latest()->limit(30)->get();

        $apporteurs = $clientsList->map(function ($c) use ($defaultRate) {
            return (object) [
                'id' => $c->id,
                'client_id' => $c->id,
                'code' => $c->code_client ?? $c->reference ?? ('CL-' . str_pad($c->id, 5, '0', STR_PAD_LEFT)),
                'nom' => $c->nom,
                'prenom' => $c->prenom ?? '',
                'telephone' => $c->telephone ?? $c->phone ?? '-',
                'source' => 'Client',
                'taux_commission' => $defaultRate,
            ];
        });

        return view('livewire.apporteur.vision-apporteur-modal', ['apporteurs' => $apporteurs]);
    }
}
