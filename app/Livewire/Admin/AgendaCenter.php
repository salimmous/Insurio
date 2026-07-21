<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Task;

class AgendaCenter extends Component
{
    public function render()
    {
        $tasks = Task::with('client')->latest()->get();
        return view('livewire.admin.agenda-center', compact('tasks'))
            ->layout('layouts.app');
    }
}
