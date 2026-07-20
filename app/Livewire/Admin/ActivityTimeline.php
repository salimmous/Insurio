<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityTimeline extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'actionFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('model_type', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->actionFilter)) {
            $query->where('action', $this->actionFilter);
        }

        $logs = $query->paginate(15);

        return view('livewire.admin.activity-timeline', [
            'logs' => $logs
        ])->layout('layouts.app');
    }
}
