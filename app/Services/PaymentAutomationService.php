<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\Communication;
use App\Models\Task;
use App\Models\Dossier;
use App\Models\Contract;
use App\Models\Employe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentAutomationService
{
    /**
     * Handle payment creation automation.
     */
    public function handlePaymentCreated(Payment $payment): void
    {
        // 1. Generate receipt number if not set and status is pending/paid
        if (empty($payment->receipt_number) && in_array($payment->payment_status, ['pending', 'paid'])) {
            $payment->receipt_number = 'REC-' . date('Y') . '-' . sprintf('%06d', $payment->id);
            $payment->saveQuietly();
        }

        // 2. Log timeline activity
        $this->logActivity(
            $payment,
            'note',
            "Création de la fiche de règlement {$payment->payment_number} pour un montant de " . number_format($payment->amount, 2) . " DH (Mode: {$payment->payment_method})."
        );
    }

    /**
     * Handle payment paid state transition automation.
     */
    public function handlePaymentPaid(Payment $payment): void
    {
        // 1. Ensure receipt number is generated
        if (empty($payment->receipt_number)) {
            $payment->receipt_number = 'REC-' . date('Y') . '-' . sprintf('%06d', $payment->id);
            $payment->saveQuietly();
        }

        // 2. Update contract payment balance & status
        $contract = $payment->contract;
        if ($contract) {
            $totalPremium = (float) $contract->prime_totale;
            // Sum all payments in paid status for this contract
            $totalPaid = Payment::where('contract_id', $contract->id)
                ->where('payment_status', 'paid')
                ->sum('paid_amount');

            if ($totalPaid >= $totalPremium) {
                $contract->payment_status = 'paid';
                $contract->statut = 'actif'; // Activate contract if fully paid
            } elseif ($totalPaid > 0) {
                $contract->payment_status = 'partially_paid';
            } else {
                $contract->payment_status = 'unpaid';
            }
            $contract->saveQuietly();
        }

        // 3. Log timeline activity
        $this->logActivity(
            $payment,
            'note',
            "Règlement enregistré avec succès. Reçu N°: {$payment->receipt_number}. Montant payé: " . number_format($payment->paid_amount, 2) . " DH."
        );

        // 4. Simulate WhatsApp notification
        Communication::create([
            'client_id' => $payment->client_id,
            'type' => 'whatsapp',
            'message' => "Cher client, nous accusons réception de votre règlement de " . number_format($payment->paid_amount, 2) . " DH concernant votre contrat {$payment->contract->contract_number}. Reçu N°: {$payment->receipt_number}.",
            'user_id' => auth()->id() ?? $payment->created_by,
        ]);

        // 5. Simulate Email notification
        $productName = $payment->contract && $payment->contract->product ? $payment->contract->product->nom : '';
        Communication::create([
            'client_id' => $payment->client_id,
            'type' => 'email',
            'message' => "Sujet: Confirmation de Règlement - Reçu N° {$payment->receipt_number}\n\nBonjour,\nNous vous confirmons le paiement de " . number_format($payment->paid_amount, 2) . " DH pour votre assurance {$productName}.\nMerci pour votre confiance.",
            'user_id' => auth()->id() ?? $payment->created_by,
        ]);
    }

    /**
     * Handle Cheque rejection workflow.
     */
    public function handleReturnedCheque(Payment $payment, string $reason, ?string $bankLetterPath = null): void
    {
        $payment->payment_status = 'returned';
        $payment->saveQuietly();

        // 1. Create a follow-up collection task
        $employeeId = $payment->employee_id ?: ($payment->contract->employe_id ?? null);
        $task = Task::create([
            'client_id' => $payment->client_id,
            'contract_id' => $payment->contract_id,
            'title' => "Incident de Paiement - Chèque N° {$payment->cheque_number} rejeté",
            'description' => "Le chèque N° {$payment->cheque_number} d'un montant de " . number_format($payment->amount, 2) . " DH a été retourné par la banque pour le motif : {$reason}. Contacter le client immédiatement pour régularisation.",
            'due_date' => now()->addDays(2),
            'status' => 'pending',
            'priority' => 'high',
            'assigned_to' => $employeeId,
            'created_by' => auth()->id() ?? $payment->created_by,
        ]);

        // 2. Create a Dossier in the Case Management module (Dossier Type: payment_issue)
        $dossier = Dossier::create([
            'client_id' => $payment->client_id,
            'contract_id' => $payment->contract_id,
            'type' => 'payment_issue',
            'status' => 'open',
            'priority' => 'high',
            'urgency' => 'high',
            'succursale_id' => $payment->branch_id ?: ($payment->contract->succursale_id ?? ($payment->client->succursale_id ?? null)),
            'creation_date' => now()->toDateString(),
            'description' => "Dossier ouvert automatiquement suite au rejet du chèque N° {$payment->cheque_number} d'un montant de " . number_format($payment->amount, 2) . " DH. Motif: {$reason}.",
            'progress' => 10,
            'checklist' => [
                ['name' => 'Notifier le conseiller', 'completed' => true],
                ['name' => 'Appeler le client pour régularisation', 'completed' => false],
                ['name' => 'Récupérer la lettre de rejet de la banque', 'completed' => !empty($bankLetterPath)],
                ['name' => 'Encaisser le nouveau règlement', 'completed' => false],
            ],
            'assigned_employee_id' => $employeeId,
        ]);

        // Connect task to dossier
        $task->dossier_id = $dossier->id;
        $task->saveQuietly();

        // 3. Create balancing Debit entry in FinancialLedger to correct cash balance
        if (class_exists(\App\Models\FinancialLedger::class)) {
            \App\Models\FinancialLedger::create([
                'transaction_number' => 'REV-' . date('Y') . '-' . sprintf('%06d', $payment->id),
                'entry_date' => now(),
                'entry_type' => 'debit',
                'category' => 'rejet_cheque',
                'amount' => $payment->amount,
                'payment_method' => 'cheque',
                'reference_number' => $payment->cheque_number,
                'description' => "Annulation & Revers du chèque N° {$payment->cheque_number} rejeté pour motif : {$reason}",
                'client_name' => $payment->client ? $payment->client->name : null,
                'user_id' => auth()->id() ?? $payment->created_by,
                'status' => 'posted',
            ]);
        }

        // 4. Log timeline activity
        $this->logActivity(
            $payment,
            'note',
            "ALERTE: Rejet de chèque enregistré. Chèque N°: {$payment->cheque_number}. Raison: {$reason}. Dossier Incident {$dossier->dossier_number} créé. Revers comptable effectué."
        );

        // 4. Notify Manager (simulate timeline warning)
        Communication::create([
            'client_id' => $payment->client_id,
            'type' => 'note',
            'message' => "ALERTE MANAGER: Incident de paiement majeur pour le client {$payment->client->nom_complet}. Rejet chèque de " . number_format($payment->amount, 2) . " DH. Dossier {$dossier->dossier_number} assigné au conseiller.",
            'user_id' => auth()->id() ?? $payment->created_by,
        ]);
    }

    private function logActivity(Payment $payment, string $type, string $message): void
    {
        Communication::create([
            'client_id' => $payment->client_id,
            'dossier_id' => null,
            'type' => $type,
            'message' => $message,
            'user_id' => auth()->id() ?? $payment->created_by,
        ]);
    }
}
