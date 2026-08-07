<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Reglement;
use App\Models\FinancialLedger;
use App\Models\CashRegister;
use App\Models\Cheque;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReglementObserver
{
    public static bool $syncing = false;

    public function created(Reglement $reglement): void
    {
        if (self::$syncing) {
            return;
        }

        self::$syncing = true;
        try {
            $modeMap = [
                'especes' => 'cash',
                'virement' => 'transfer',
                'carte' => 'card',
                'cheque' => 'cheque',
            ];

            $contract = $reglement->contrat;
            $clientId = $contract ? $contract->client_id : null;
            $paymentMethod = $modeMap[strtolower($reglement->mode_reglement ?? 'especes')] ?? 'cash';
            $amount = (float)$reglement->montant;

            // 1. Sync to FinancialLedger (Payment Center / Grand Livre)
            $existingLedger = FinancialLedger::where('contract_id', $reglement->contrat_id)
                ->where('amount', $amount)
                ->whereDate('entry_date', $reglement->date_reglement ?? now())
                ->first();

            if (!$existingLedger) {
                $trxId = 'TRX-' . date('Y') . '-' . str_pad($reglement->id, 6, '0', STR_PAD_LEFT);
                $recId = 'REC-' . date('Ymd') . '-' . str_pad($reglement->id, 5, '0', STR_PAD_LEFT);

                FinancialLedger::create([
                    'uuid' => (string) Str::uuid(),
                    'transaction_id' => $trxId,
                    'entry_date' => $reglement->date_reglement ?? now(),
                    'category' => 'encaissement_prime',
                    'entry_type' => 'credit',
                    'amount' => $amount,
                    'currency' => 'DH',
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                    'receipt_number' => $recId,
                    'qr_code_hash' => md5($trxId . '|' . $amount),
                    'notes' => 'Règlement contrat #' . ($contract?->numero_contrat ?? $contract?->contract_number ?? $reglement->contrat_id),
                    'user_id' => auth()->id() ?? 1,
                    'client_id' => $clientId,
                    'contract_id' => $reglement->contrat_id,
                    'metadata' => [
                        'reglement_id' => $reglement->id,
                        'reference' => $reglement->reference_paiement,
                    ],
                ]);
            }

            // 2. If cheque, sync to Cheques table
            if ($paymentMethod === 'cheque') {
                $existingCheque = Cheque::where('contract_id', $reglement->contrat_id)
                    ->where('amount', $amount)
                    ->first();

                if (!$existingCheque) {
                    Cheque::create([
                        'uuid' => (string) Str::uuid(),
                        'cheque_number' => $reglement->reference_paiement ?: 'CHQ-' . str_pad($reglement->id, 5, '0', STR_PAD_LEFT),
                        'bank_name' => 'Banque Marocaine',
                        'issuer' => $contract?->souscripteur ?? 'Client',
                        'amount' => $amount,
                        'due_date' => $reglement->date_echeance_cheque ?: $reglement->date_reglement ?: now(),
                        'status' => 'received',
                        'client_id' => $clientId,
                        'contract_id' => $reglement->contrat_id,
                        'notes' => 'Chèque pour règlement du contrat #' . ($contract?->numero_contrat ?? $reglement->contrat_id),
                    ]);
                }
            }

            // 3. Recalculate CashRegister current & expected balance instantly for cash
            $caisse = CashRegister::first();
            if ($caisse) {
                $totalCashCredit = (float) FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'credit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $totalCashDebit = (float) FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'debit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $netCash = $totalCashCredit - $totalCashDebit;
                $caisse->update([
                    'current_balance' => $netCash,
                    'expected_balance' => $netCash,
                ]);
            }

            // 4. Sync to legacy Payment table if exists
            if ($clientId && class_exists(Payment::class)) {
                try {
                    $existsPayment = Payment::where([
                        'contract_id' => $reglement->contrat_id,
                        'amount' => $amount,
                    ])->exists();

                    if (!$existsPayment) {
                        Payment::create([
                            'client_id' => $clientId,
                            'contract_id' => $reglement->contrat_id,
                            'amount' => $amount,
                            'payment_method' => $paymentMethod,
                            'status' => 'paid',
                            'reference' => $reglement->reference_paiement,
                            'created_at' => $reglement->date_reglement,
                        ]);
                    }
                } catch (\Throwable $e) {
                }
            }
        } finally {
            self::$syncing = false;
        }
    }

    public function deleted(Reglement $reglement): void
    {
        if (self::$syncing) {
            return;
        }

        self::$syncing = true;
        try {
            $amount = (float)$reglement->montant;

            // Delete linked ledger
            FinancialLedger::where('contract_id', $reglement->contrat_id)
                ->where('amount', $amount)
                ->whereDate('entry_date', $reglement->date_reglement ?? now())
                ->delete();

            // Recalculate CashRegister
            $caisse = CashRegister::first();
            if ($caisse) {
                $totalCashCredit = (float) FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'credit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $totalCashDebit = (float) FinancialLedger::where('payment_method', 'cash')
                    ->where('entry_type', 'debit')
                    ->whereIn('status', ['completed', 'posted', 'approved'])
                    ->sum('amount');

                $netCash = $totalCashCredit - $totalCashDebit;
                $caisse->update([
                    'current_balance' => $netCash,
                    'expected_balance' => $netCash,
                ]);
            }
        } finally {
            self::$syncing = false;
        }
    }
}
