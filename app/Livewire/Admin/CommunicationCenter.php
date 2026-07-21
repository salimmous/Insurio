<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Communication;

class CommunicationCenter extends Component
{
    public function render()
    {
        $communications = Communication::with(['client', 'user'])->latest()->get();
        return view('livewire.admin.communication-center', compact('communications'))
            ->layout('layouts.app');
    }
}
