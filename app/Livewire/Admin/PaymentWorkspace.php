<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\PaymentInstallment;
use App\Models\PaymentFollowUp;
use App\Models\Communication;
use App\Services\PaymentAutomationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentWorkspace extends Component
{
    use WithFileUploads;

    public $paymentId;
    public $activeTab = 'overview';

    // Follow up Form
    public $followup_date = '';
    public $followup_priority = 'medium';
    public $followup_notes = '';

    // Rejection details
    public $rejection_reason = '';
    public $showRejectionModal = false;

    // File uploads
    public $uploadedAttachment;
    public $attachmentLabel = '';

    // Notes
    public $newNote = '';

    public function mount($id)
    {
        $this->paymentId = $id;
        $this->followup_date = now()->addDays(2)->format('Y-m-d');
    }

    public function getPaymentProperty()
    {
        return Payment::with([
            'client',
            'contract.product',
            'company',
            'employee',
            'branch',
            'installments',
            'reconciliations',
            'auditLogs.user',
            'followUps.assignedEmployee'
        ])->findOrFail($this->paymentId);
    }

    public function approvePayment()
    {
        $payment = $this->payment;
        $payment->payment_status = 'paid';
        $payment->paid_amount = $payment->amount;
        $payment->payment_date = now();
        $payment->approved_by = auth()->id();
        $payment->save();

        app(PaymentAutomationService::class)->handlePaymentPaid($payment);

        $this->dispatch('swal:success', ['message' => 'Paiement approuvé et validé avec succès.']);
    }

    public function recordChequeReturned()
    {
        $this->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $payment = $this->payment;
        app(PaymentAutomationService::class)->handleReturnedCheque($payment, $this->rejection_reason);

        $this->showRejectionModal = false;
        $this->rejection_reason = '';
        $this->dispatch('swal:success', ['message' => 'Chèque marqué comme rejeté. Le dossier d\'incident de paiement et la tâche de recouvrement ont été générés.']);
    }

    public function addFollowup()
    {
        $this->validate([
            'followup_date' => 'required|date',
            'followup_priority' => 'required|in:low,medium,high,critical',
            'followup_notes' => 'required|string|max:2000',
        ]);

        PaymentFollowUp::create([
            'payment_id' => $this->paymentId,
            'assigned_employee_id' => $this->payment->employee_id,
            'reminder_date' => $this->followup_date,
            'priority' => $this->followup_priority,
            'notes' => $this->followup_notes,
            'completed' => false,
        ]);

        $this->followup_notes = '';
        $this->dispatch('swal:success', ['message' => 'Rappel de recouvrement ajouté avec succès.']);
    }

    public function toggleFollowupCompleted($followupId)
    {
        $f = PaymentFollowUp::findOrFail($followupId);
        $f->completed = !$f->completed;
        $f->save();

        $this->dispatch('swal:success', ['message' => 'Statut du rappel mis à jour.']);
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:2000',
        ]);

        $payment = $this->payment;
        $payment->notes = trim(($payment->notes ?? '') . "\n" . '[' . now()->format('d/m/Y H:i') . '] ' . $this->newNote);
        $payment->save();

        $this->newNote = '';
        $this->dispatch('swal:success', ['message' => 'Note ajoutée au règlement.']);
    }

    public function uploadAttachment()
    {
        $this->validate([
            'uploadedAttachment' => 'required|file|max:10240', // max 10MB
            'attachmentLabel' => 'required|string|max:255',
        ]);

        $tenantId = tenant('id') ?? 'default';
        $directory = "{$tenantId}/payments/{$this->paymentId}";
        $path = $this->uploadedAttachment->storeAs($directory, Str::random(40) . '.' . $this->uploadedAttachment->getClientOriginalExtension(), 'local');

        $payment = $this->payment;
        $attachments = $payment->attachments ?: [];
        $attachments[] = [
            'label' => $this->attachmentLabel,
            'path' => $path,
            'file_name' => $this->uploadedAttachment->getClientOriginalName(),
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $payment->attachments = $attachments;
        $payment->save();

        $this->uploadedAttachment = null;
        $this->attachmentLabel = '';
        $this->dispatch('swal:success', ['message' => 'Justificatif téléversé avec succès.']);
    }

    public function simulateSendWhatsApp()
    {
        $payment = $this->payment;
        Communication::create([
            'client_id' => $payment->client_id,
            'type' => 'whatsapp',
            'message' => "Message WhatsApp manuel envoyé : Cher client, voici le lien de votre reçu de paiement pour la facture {$payment->payment_number}.",
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('swal:success', ['message' => 'Reçu partagé via WhatsApp avec succès.']);
    }

    public function simulateSendEmail()
    {
        $payment = $this->payment;
        Communication::create([
            'client_id' => $payment->client_id,
            'type' => 'email',
            'message' => "Email manuel envoyé : Reçu de règlement concernant la prime d'assurance {$payment->payment_number}.",
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('swal:success', ['message' => 'Reçu partagé par Email avec succès.']);
    }

    public function render()
    {
        return view('livewire.admin.payment-workspace', [
            'payment' => $this->payment,
        ])->layout('layouts.app');
    }
}
