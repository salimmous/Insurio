<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\PaymentFollowUp;
use App\Models\Communication;
use Carbon\Carbon;

class CheckOverduePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan payments and flag overdue records, creating follow-up actions and logs.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting overdue payments scan...');

        $overduePayments = Payment::whereIn('payment_status', ['draft', 'pending'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->get();

        $count = 0;
        foreach ($overduePayments as $payment) {
            $payment->payment_status = 'overdue';
            $payment->save();

            // 1. Create a follow-up collection reminder
            PaymentFollowUp::create([
                'payment_id' => $payment->id,
                'assigned_employee_id' => $payment->employee_id ?: ($payment->contract->employe_id ?? null),
                'reminder_date' => Carbon::today()->addDays(1),
                'priority' => 'high',
                'notes' => "Relance automatique générée pour le paiement en retard de " . number_format($payment->amount, 2) . " DH.",
                'completed' => false,
            ]);

            // 2. Create timeline logs
            Communication::create([
                'client_id' => $payment->client_id,
                'type' => 'note',
                'message' => "ALERTE SYSTÈME: Échéance dépassée pour le règlement {$payment->payment_number}. Statut marqué comme impayé en retard (OVERDUE).",
                'user_id' => null, // System event
            ]);

            $this->info("Payment {$payment->payment_number} flagged as OVERDUE.");
            $count++;
        }

        $this->info("Scan completed. {$count} payments marked as overdue.");

        return Command::SUCCESS;
    }
}
