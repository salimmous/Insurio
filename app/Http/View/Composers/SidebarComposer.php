<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Payment;
use App\Models\Dossier;
use App\Models\Task;
use App\Models\Contract;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Safety check: ensure tenancy is initialized
        if (!function_exists('tenant') || !tenant('id') || !tenancy()->initialized) {
            $view->with([
                'pendingPaymentsCount' => 0,
                'urgentClaimsCount' => 0,
                'pendingTasksCount' => 0,
                'notificationsCount' => 0,
            ]);
            return;
        }

        try {
            // 1. Payments in pending, draft, or waiting_validation status
            $pendingPaymentsCount = Payment::whereIn('payment_status', ['pending', 'draft', 'waiting_validation'])->count();

            // 2. Dossiers of type claim with high/critical priority and open status
            $urgentClaimsCount = Dossier::where('type', 'claim')
                ->whereIn('priority', ['high', 'critical'])
                ->where('status', 'open')
                ->count();

            // 3. Tasks in pending status
            $pendingTasksCount = Task::where('status', 'pending')->count();

            // 4. Notifications count calculated dynamically (expiring contracts + recent creation)
            $notificationsCount = 0;
            
            $expiringCount = Contract::where('status', 'active')
                ->whereBetween('end_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->count();
            
            $recentCount = Contract::where('created_at', '>=', now()->subHours(24))->count();
            
            $notificationsCount = $expiringCount + $recentCount;

            $view->with([
                'pendingPaymentsCount' => $pendingPaymentsCount,
                'urgentClaimsCount' => $urgentClaimsCount,
                'pendingTasksCount' => $pendingTasksCount,
                'notificationsCount' => $notificationsCount,
            ]);
        } catch (\Throwable $e) {
            // Fallback for migrations or testing environment failures
            $view->with([
                'pendingPaymentsCount' => 0,
                'urgentClaimsCount' => 0,
                'pendingTasksCount' => 0,
                'notificationsCount' => 0,
            ]);
        }
    }
}
