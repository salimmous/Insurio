<?php

namespace App\Livewire\Apporteur;

use Livewire\Component;
use App\Models\Apporteur;

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

    public function selectApporteur($apporteurId)
    {
        $this->dispatch('apporteurSelected', $apporteurId);
        $this->close();
    }

    public function clearApporteur()
    {
        $this->dispatch('apporteurSelected', null);
        $this->close();
    }

    public function render()
    {
        $apporteurs = [];
        if (!empty($this->search)) {
            $apporteurs = Apporteur::where('nom', 'like', '%' . $this->search . '%')
                ->orWhere('prenom', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('code_apporteur', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $apporteurs = Apporteur::latest()->limit(15)->get();
        }

        return view('livewire.apporteur.vision-apporteur-modal', compact('apporteurs'));
    }
}
