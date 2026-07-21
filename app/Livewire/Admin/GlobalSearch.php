<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Client;
use App\Models\Contract;

class GlobalSearch extends Component
{
    public string $query = '';
    public array  $results = [];
    public bool   $isOpen = false;

    protected $listeners = ['globalSearch' => 'search'];

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->isOpen  = false;
            return;
        }

        $q = '%' . $this->query . '%';

        // Search clients
        $clients = Client::where(function ($query) use ($q) {
                $query->where('first_name', 'like', $q)
                      ->orWhere('last_name', 'like', $q)
                      ->orWhere('cin', 'like', $q)
                      ->orWhere('phone', 'like', $q)
                      ->orWhere('email', 'like', $q);
            })
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'type'    => 'client',
                'icon'    => 'user',
                'label'   => $c->nom_complet,
                'sub'     => $c->phone ?? $c->email ?? $c->cin ?? '—',
                'url'     => route('admin.clients.profile', $c->id),
            ]);

        // Search contracts
        $contracts = Contract::where(function ($query) use ($q) {
                $query->where('contract_number', 'like', $q)
                      ->orWhere('numero_contrat', 'like', $q)
                      ->orWhere('policy_number', 'like', $q)
                      ->orWhere('police', 'like', $q);
            })
            ->with('client')
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'type'  => 'contract',
                'icon'  => 'document',
                'label' => 'Contrat #' . ($c->contract_number ?? $c->numero_contrat),
                'sub'   => $c->client ? $c->client->nom_complet : '—',
                'url'   => route('automobile.edit', $c->id),
            ]);

        // Search payments
        $payments = \App\Models\Payment::where(function ($query) use ($q) {
                $query->where('payment_number', 'like', $q)
                      ->orWhere('cheque_number', 'like', $q);
            })
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'type'  => 'payment',
                'icon'  => 'currency-dollar',
                'label' => 'Règlement #' . $p->payment_number,
                'sub'   => number_format($p->amount, 2) . ' DH',
                'url'   => route('admin.payments.workspace', $p->id),
            ]);

        // Search dossiers (claims)
        $dossiers = \App\Models\Dossier::where(function ($query) use ($q) {
                $query->where('dossier_number', 'like', $q);
            })
            ->limit(5)
            ->get()
            ->map(fn($d) => [
                'type'  => 'dossier',
                'icon'  => 'folder',
                'label' => 'Dossier #' . $d->dossier_number,
                'sub'   => strtoupper($d->type) . ' - ' . $d->status,
                'url'   => route('admin.dossiers.workspace', $d->id),
            ]);

        // Search vehicles
        $vehicules = \App\Models\Vehicule::where(function ($query) use ($q) {
                $query->where('matricule', 'like', $q)
                      ->orWhere('marque', 'like', $q)
                      ->orWhere('modele', 'like', $q);
            })
            ->limit(5)
            ->get()
            ->map(fn($v) => [
                'type'  => 'vehicule',
                'icon'  => 'truck',
                'label' => 'Véhicule ' . $v->matricule,
                'sub'   => $v->marque . ' ' . $v->modele,
                'url'   => route('automobile.index'),
            ]);

        $this->results = $clients->concat($contracts)->concat($payments)->concat($dossiers)->concat($vehicules)->take(12)->values()->toArray();
        $this->isOpen  = count($this->results) > 0;
    }

    public function close(): void
    {
        $this->isOpen  = false;
        $this->query   = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.admin.global-search');
    }
}
