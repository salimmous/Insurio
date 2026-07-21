<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Document;

class DocumentVault extends Component
{
    public $search = '';
    public $selectedType = '';

    public function render()
    {
        $query = Document::query();

        if ($this->search) {
            $query->where('file_name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedType) {
            $query->where('type', $this->selectedType);
        }

        $documents = $query->with('client')->latest()->get();

        return view('livewire.admin.document-vault', compact('documents'))
            ->layout('layouts.app');
    }
}
