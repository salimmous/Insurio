<?php

namespace App\Livewire\Apporteur;

use Livewire\Component;
use App\Models\Client;
use App\Models\Setting;

class VisionApporteurModal extends Component
{
    public $search = '';
    public $isOpen = false;

    protected $listeners = ['openVisionApporteur' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->search = '';
    }

    public function close()
    {
        $this->isOpen = false;
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

        // Fetch ONLY from Clients table as requested
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
