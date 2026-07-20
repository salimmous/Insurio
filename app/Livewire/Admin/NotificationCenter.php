<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contract;

class NotificationCenter extends Component
{
    public bool $isOpen = false;

    public function getNotificationsProperty(): array
    {
        $notifications = [];

        try {
            // Contracts expiring in 7 days
            $expiring7 = Contract::where('status', 'active')
                ->whereBetween('end_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->with('client')
                ->limit(5)
                ->get();

            foreach ($expiring7 as $contract) {
                $daysLeft = now()->diffInDays($contract->end_date, false);
                $notifications[] = [
                    'type'    => 'warning',
                    'icon'    => 'clock',
                    'title'   => 'Contrat expire bientôt',
                    'message' => ($contract->client?->nom_complet ?? '—') . ' — dans ' . max(0, $daysLeft) . ' jour(s)',
                    'url'     => route('automobile.edit', $contract->id),
                    'time'    => $contract->end_date->format('d/m/Y'),
                ];
            }

            // Recent contracts (last 24h)
            $recent = Contract::where('created_at', '>=', now()->subHours(24))
                ->with('client')
                ->latest()
                ->limit(3)
                ->get();

            foreach ($recent as $contract) {
                $notifications[] = [
                    'type'    => 'success',
                    'icon'    => 'check',
                    'title'   => 'Nouveau contrat',
                    'message' => 'Contrat #' . ($contract->contract_number ?? $contract->numero_contrat) . ' créé',
                    'url'     => route('automobile.edit', $contract->id),
                    'time'    => $contract->created_at->diffForHumans(),
                ];
            }
        } catch (\Throwable $e) {
            // Non-critical — return empty set
        }

        return $notifications;
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.admin.notification-center', [
            'notifications' => $this->notifications,
        ]);
    }
}
