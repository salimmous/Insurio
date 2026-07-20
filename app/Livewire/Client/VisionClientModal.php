<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Client;

class VisionClientModal extends Component
{
    public $search = '';
    public $isOpen = false;

    protected $listeners = ['openVisionClient' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->search = '';
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function selectClient($clientId)
    {
        $this->dispatch('clientSelected', $clientId);
        $this->close();
    }

    public function render()
    {
        $clients = [];
        if (!empty($this->search)) {
            $clients = Client::where('nom', 'like', '%' . $this->search . '%')
                ->orWhere('prenom', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('cin', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $clients = Client::latest()->limit(10)->get();
        }

        return view('livewire.client.vision-client-modal', compact('clients'));
    }
}
