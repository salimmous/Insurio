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

        $this->results = $clients->merge($contracts)->take(10)->values()->toArray();
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
