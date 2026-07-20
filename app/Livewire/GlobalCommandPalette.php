<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\Contract;

class GlobalCommandPalette extends Component
{
    public $search = '';
    public $isOpen = false;

    public $clients = [];
    public $contracts = [];
    public $pages = [];

    protected $listeners = [
        'toggleCommandPalette' => 'toggle',
    ];

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->search = '';
            $this->clients = [];
            $this->contracts = [];
            $this->pages = [];
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->clients = [];
            $this->contracts = [];
            $this->pages = [];
            return;
        }

        // 1. Search Clients
        if (\Illuminate\Support\Facades\Schema::hasTable('clients')) {
            $this->clients = Client::where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%")
                ->limit(5)
                ->get()
                ->toArray();
        }

        // 2. Search Contracts
        if (\Illuminate\Support\Facades\Schema::hasTable('contracts')) {
            $this->contracts = Contract::where('contract_number', 'like', "%{$this->search}%")
                ->limit(5)
                ->get()
                ->toArray();
        }

        // 3. Search Static Pages
        $allPages = [
            ['name' => '📊 Tableau de Bord (CEO)', 'url' => route('dashboard')],
            ['name' => '🚘 Registre Automobile', 'url' => route('automobile.index')],
            ['name' => '🏢 Gestion des Succursales', 'url' => route('admin.succursales')],
            ['name' => '👥 Gestion des Employés', 'url' => route('admin.employes')],
            ['name' => '💰 Registre des Charges', 'url' => route('admin.charges')],
            ['name' => '📲 Automation & Relances', 'url' => route('admin.automation')],
            ['name' => '👤 Registre des Clients', 'url' => route('admin.clients')],
            ['name' => '📋 Gestion des Tâches', 'url' => route('admin.tasks')],
        ];

        $this->pages = array_filter($allPages, function ($page) {
            return str_contains(strtolower($page['name']), strtolower($this->search));
        });
    }

    public function render()
    {
        return view('livewire.global-command-palette');
    }
}
