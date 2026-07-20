<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use Illuminate\Support\Facades\Cache;

class InvalidateDashboardCache
{
    /**
     * Bust the dashboard KPI cache when a payment is received so the
     * next page load shows fresh totalImpayes and cashflow numbers.
     */
    public function handle(PaymentReceived $event): void
    {
        try {
            $tenantId = tenant('id');
            if (!$tenantId) {
                return;
            }

            // Bust all branch variants: global + per-succursale
            Cache::forget("dashboard_kpis_{$tenantId}_branch_all");

            $succursaleId = $event->contract->succursale_id;
            if ($succursaleId) {
                Cache::forget("dashboard_kpis_{$tenantId}_branch_{$succursaleId}");
            }
        } catch (\Throwable $e) {
            // Never let a cache error block payment processing
        }
    }
}
