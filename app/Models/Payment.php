<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Reglement;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'uuid',
        'payment_number',
        'client_id',
        'contract_id',
        'policy_id',
        'company_id',
        'employee_id',
        'branch_id',
        'amount',
        'currency',
        'paid_amount',
        'remaining_amount',
        'tax',
        'discount',
        'payment_method',
        'payment_status',
        'payment_date',
        'due_date',
        'reference_number',
        'receipt_number',
        'bank_name',
        'bank_account',
        'cheque_number',
        'cheque_issue_date',
        'cheque_deposit_date',
        'cheque_clearance_date',
        'notes',
        'attachments',
        'created_by',
        'approved_by',
        'status',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'payment_date' => 'datetime',
        'due_date' => 'date',
        'cheque_issue_date' => 'date',
        'cheque_deposit_date' => 'date',
        'cheque_clearance_date' => 'date',
        'attachments' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) Str::uuid();
            }
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }
            if (empty($payment->created_by) && auth()->check()) {
                $payment->created_by = auth()->id();
            }
            
            // Calculate remaining amount
            $payment->remaining_amount = max(0, $payment->amount - $payment->paid_amount - $payment->discount);
        });

        static::updating(function ($payment) {
            // Recalculate remaining amount on changes
            $payment->remaining_amount = max(0, $payment->amount - $payment->paid_amount - $payment->discount);
            
            // Log audit changes
            self::logAudit($payment, 'update');
        });

        static::created(function ($payment) {
            self::logAudit($payment, 'create');
            self::syncLegacyReglement($payment);
        });

        static::updated(function ($payment) {
            self::syncLegacyReglement($payment);
        });
    }

    private static function generatePaymentNumber(): string
    {
        $year = date('Y');
        $lastPayment = self::where('payment_number', 'like', "PM-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastPayment) {
            $parts = explode('-', $lastPayment->payment_number);
            $sequence = ((int) end($parts)) + 1;
        }

        return sprintf('PM-%s-%06d', $year, $sequence);
    }

    private static function logAudit(Payment $payment, string $action)
    {
        if (!Schema::hasTable('payment_audit_logs')) {
            return;
        }

        $oldValues = $action === 'update' ? array_intersect_key($payment->getOriginal(), $payment->getDirty()) : null;
        $newValues = $action === 'update' ? $payment->getDirty() : $payment->toArray();

        PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }

    private static function syncLegacyReglement(Payment $payment)
    {
        if ($payment->payment_status === 'paid' && $payment->contract_id) {
            $modeMap = [
                'cash' => 'especes',
                'bank_transfer' => 'virement',
                'card' => 'carte',
                'cheque' => 'cheque',
            ];
            $mode = $modeMap[$payment->payment_method] ?? ($payment->payment_method ?: 'especes');
            $montant = $payment->paid_amount > 0 ? $payment->paid_amount : $payment->amount;
            $date = $payment->payment_date ?: now();
            $ref = $payment->reference ?: ($payment->reference_number ?: $payment->payment_number);

            $existing = Reglement::where('contrat_id', $payment->contract_id)
                ->where(function ($query) use ($payment, $montant, $date, $ref) {
                    $query->where('reference_paiement', $ref)
                          ->orWhere('reference_paiement', $payment->payment_number)
                          ->orWhere(function ($q) use ($montant, $date) {
                              $q->where('montant', $montant)
                                ->whereDate('date_reglement', $date instanceof \Carbon\Carbon ? $date->toDateString() : substr((string)$date, 0, 10));
                          });
                })->first();

            if (!$existing) {
                Reglement::create([
                    'contrat_id' => $payment->contract_id,
                    'montant' => $montant,
                    'date_reglement' => $date,
                    'mode_reglement' => $mode,
                    'reference_paiement' => $ref,
                ]);
            } else {
                $existing->update([
                    'montant' => $montant,
                    'date_reglement' => $date,
                    'mode_reglement' => $mode,
                    'reference_paiement' => $ref,
                ]);
            }
        }
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Succursale::class, 'branch_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class, 'payment_id');
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(BankReconciliation::class, 'payment_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(PaymentFollowUp::class, 'payment_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(PaymentAuditLog::class, 'payment_id');
    }

    // Compatibility getters and relations
    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function getDateReglementAttribute()
    {
        return $this->payment_date ?: $this->created_at;
    }

    public function getModeReglementAttribute()
    {
        return $this->payment_method;
    }

    public function getReferencePaiementAttribute()
    {
        return $this->payment_number;
    }

    public function getMontantAttribute()
    {
        return $this->amount;
    }

    public function getStatusAttribute()
    {
        return $this->payment_status;
    }

    public function setStatusAttribute($value)
    {
        $this->payment_status = $value;
    }

    public function getReferenceAttribute()
    {
        return $this->reference_number;
    }

    public function setReferenceAttribute($value)
    {
        $this->reference_number = $value;
    }
}
