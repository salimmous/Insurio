<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Task;
use App\Models\Client;
use App\Models\Contract;
use App\Models\User;

class TaskManager extends Component
{
    public $search = '';
    public $priorityFilter = '';
    
    // Modal & Form properties
    public $isModalOpen = false;
    public $taskId = null;
    public $title = '';
    public $description = '';
    public $client_id = '';
    public $contract_id = '';
    public $assigned_user_id = '';
    public $priority = 'medium';
    public $status = 'todo';
    public $due_date = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'client_id' => 'required|exists:clients,id',
        'contract_id' => 'nullable|exists:contracts,id',
        'assigned_user_id' => 'nullable|exists:users,id',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:todo,progress,completed',
        'due_date' => 'nullable|date',
    ];

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        $this->taskId = $id;

        if ($id) {
            $task = Task::findOrFail($id);
            $this->title = $task->title;
            $this->description = $task->description;
            $this->client_id = $task->client_id;
            $this->contract_id = $task->contract_id;
            $this->assigned_user_id = $task->assigned_user_id;
            $this->priority = $task->priority;
            $this->status = $task->status;
            $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : '';
        } else {
            $this->resetForm();
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->client_id = '';
        $this->contract_id = '';
        $this->assigned_user_id = '';
        $this->priority = 'medium';
        $this->status = 'todo';
        $this->due_date = '';
    }

    public function saveTask()
    {
        $this->validate();

        Task::updateOrCreate(
            ['id' => $this->taskId],
            [
                'title' => $this->title,
                'description' => $this->description,
                'client_id' => $this->client_id,
                'contract_id' => $this->contract_id ?: null,
                'assigned_user_id' => $this->assigned_user_id ?: null,
                'priority' => $this->priority,
                'status' => $this->status,
                'due_date' => $this->due_date ?: null,
            ]
        );

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => $this->taskId ? 'Tâche mise à jour avec succès.' : 'Tâche créée avec succès.']);
    }

    public function updateTaskStatus($id, $newStatus)
    {
        if (!in_array($newStatus, ['todo', 'progress', 'completed'])) {
            return;
        }

        $task = Task::findOrFail($id);
        $task->update(['status' => $newStatus]);
        
        $this->dispatch('swal:success', ['message' => 'Statut de la tâche mis à jour.']);
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        $this->dispatch('swal:success', ['message' => 'Tâche supprimée.']);
    }

    public function render()
    {
        $query = Task::with(['client', 'contract', 'assignee']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->priorityFilter)) {
            $query->where('priority', $this->priorityFilter);
        }

        $allTasks = $query->latest()->get();

        $todoTasks = $allTasks->where('status', 'todo');
        $progressTasks = $allTasks->where('status', 'progress');
        $completedTasks = $allTasks->where('status', 'completed');

        $clients = Client::orderBy('last_name')->get();
        $contracts = Contract::orderBy('contract_number')->get();
        $users = User::orderBy('name')->get();

        return view('livewire.admin.task-manager', compact('todoTasks', 'progressTasks', 'completedTasks', 'clients', 'contracts', 'users'))
            ->layout('layouts.app');
    }
}
