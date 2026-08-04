<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contract;
use App\Models\Task;
use Carbon\Carbon;

class AgendaCenter extends Component
{
    public int $month;
    public int $year;
    public ?string $selectedDate = null;
    public bool $showModal = false;

    public function mount()
    {
        $this->month = (int)now()->format('n');
        $this->year = (int)now()->format('Y');
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = (int)$date->format('n');
        $this->year = (int)$date->format('Y');
    }

    public function previousMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = (int)$date->format('n');
        $this->year = (int)$date->format('Y');
    }

    public function goToToday(): void
    {
        $this->month = (int)now()->format('n');
        $this->year = (int)now()->format('Y');
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedDate = null;
    }

    public function render()
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        // Get contracts expiring in this month
        $expiringContracts = Contract::with(['client'])
            ->where(function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('end_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                  ->orWhereBetween('date_echeance', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
            })
            ->get()
            ->groupBy(function($contract) {
                $date = $contract->end_date ?? $contract->date_echeance;
                return Carbon::parse($date)->format('Y-m-d');
            });

        // Get tasks due in this month
        $tasks = Task::with('client')
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(function($task) {
                return Carbon::parse($task->due_date)->format('Y-m-d');
            });

        $selectedDateContracts = collect();
        $selectedDateTasks = collect();
        if ($this->selectedDate) {
            $selectedDateContracts = $expiringContracts->get($this->selectedDate, collect());
            $selectedDateTasks = $tasks->get($this->selectedDate, collect());
        }

        return view('livewire.admin.agenda-center', [
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
            'expiringContracts' => $expiringContracts,
            'tasks' => $tasks,
            'selectedDateContracts' => $selectedDateContracts,
            'selectedDateTasks' => $selectedDateTasks,
        ])->layout('layouts.app');
    }
}
