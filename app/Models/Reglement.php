<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reglement extends Model
{
    protected $table = 'reglements';

    protected $fillable = [
        'contrat_id',
        'montant',
        'date_reglement',
        'mode_reglement',
        'reference_paiement',
        'date_echeance_cheque',
    ];

    protected $casts = [
        'date_reglement' => 'date',
        'date_echeance_cheque' => 'date',
        'montant' => 'decimal:2',
    ];

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(ContratAuto::class, 'contrat_id');
    }

    protected static function booted()
    {
        static::saved(function ($reglement) {
            if ($reglement->contrat) {
                app(\App\Services\CommissionService::class)->triggerForAction('encaissement', $reglement->contrat);
            }
        });

        static::created(function ($reglement) {
            try {
                $contrat = $reglement->contrat;
                $paymentMethod = match(strtolower($reglement->mode_reglement ?? 'especes')) {
                    'especes' => 'cash',
                    'cheque' => 'cheque',
                    'virement' => 'transfer',
                    'carte' => 'card',
                    default => 'cash',
                };

                $chequeId = null;
                if (strtolower($reglement->mode_reglement ?? '') === 'cheque' && class_exists('\App\Models\Cheque')) {
                    $cheque = \App\Models\Cheque::create([
                        'cheque_number' => $reglement->reference_paiement ?: 'CHQ-' . str_pad($reglement->id, 5, '0', STR_PAD_LEFT),
                        'bank_name' => 'Banque Marocaine',
                        'issuer_name' => $contrat?->souscripteur ?: 'Client',
                        'amount' => $reglement->montant,
                        'due_date' => $reglement->date_echeance_cheque ?: ($reglement->date_reglement ?: now()),
                        'status' => 'received',
                        'client_id' => $contrat?->client_id,
                        'contract_id' => $contrat?->id,
                        'notes' => 'Chèque pour règlement du contrat #' . ($contrat?->numero_contrat ?? ''),
                    ]);
                    $chequeId = $cheque->id;
                }

                $cashRegisterId = null;
                if ($paymentMethod === 'cash' && class_exists('\App\Models\CashRegister')) {
                    $caisse = \App\Models\CashRegister::first();
                    if (!$caisse) {
                        $caisse = \App\Models\CashRegister::create([
                            'uuid' => (string) \Illuminate\Support\Str::uuid(),
                            'name' => 'Caisse Principale Agence',
                            'opening_balance' => 0,
                            'current_balance' => 0,
                            'expected_balance' => 0,
                        ]);
                    }
                    $caisse->increment('current_balance', $reglement->montant);
                    $caisse->increment('expected_balance', $reglement->montant);
                    $cashRegisterId = $caisse->id;
                }

                if (class_exists('\App\Models\FinancialLedger')) {
                    $ledger = \App\Models\FinancialLedger::create([
                        'entry_date' => $reglement->date_reglement ?? now(),
                        'category' => 'encaissement_prime',
                        'entry_type' => 'credit',
                        'amount' => $reglement->montant,
                        'currency' => 'DH',
                        'payment_method' => $paymentMethod,
                        'status' => 'completed',
                        'notes' => 'Règlement contrat #' . ($contrat?->numero_contrat ?? '') . ($reglement->reference_paiement ? ' (Réf: '.$reglement->reference_paiement.')' : ''),
                        'user_id' => auth()->id() ?? 1,
                        'client_id' => $contrat?->client_id,
                        'contract_id' => $contrat?->id,
                        'cheque_id' => $chequeId,
                        'cash_register_id' => $cashRegisterId,
                    ]);

                    if (class_exists('\App\Models\FinancialAuditLog')) {
                        \App\Models\FinancialAuditLog::create([
                            'ledger_id' => $ledger->id,
                            'user_id' => auth()->id() ?? 1,
                            'action' => 'created',
                            'new_values' => $ledger->toArray(),
                            'ip_address' => request()?->ip() ?: '127.0.0.1',
                            'user_agent' => request()?->userAgent() ?: 'System',
                            'reason' => 'Encaissement automatique via règlement du contrat #' . ($contrat?->numero_contrat ?? ''),
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Error syncing Reglement to FinancialLedger: ' . $e->getMessage());
            }
        });

        static::deleted(function ($reglement) {
            try {
                if (in_array(strtolower($reglement->mode_reglement ?? ''), ['especes', 'cash']) && class_exists('\App\Models\CashRegister')) {
                    $caisse = \App\Models\CashRegister::first();
                    if ($caisse) {
                        $caisse->decrement('current_balance', min($caisse->current_balance, $reglement->montant));
                        $caisse->decrement('expected_balance', min($caisse->expected_balance, $reglement->montant));
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Error deleting Reglement from FinancialLedger: ' . $e->getMessage());
            }
        });
    }
}
