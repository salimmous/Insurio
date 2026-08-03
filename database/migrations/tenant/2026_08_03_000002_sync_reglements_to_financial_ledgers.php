<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reglements') || !Schema::hasTable('financial_ledgers')) {
            return;
        }

        // Ensure default cash register exists
        $caisseId = null;
        if (Schema::hasTable('cash_registers')) {
            $caisse = DB::table('cash_registers')->first();
            if (!$caisse) {
                $caisseId = DB::table('cash_registers')->insertGetId([
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Caisse Principale Agence',
                    'opening_balance' => 0.00,
                    'current_balance' => 0.00,
                    'expected_balance' => 0.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $caisseId = $caisse->id;
            }
        }

        $reglements = DB::table('reglements')->get();
        $totalCash = 0;

        foreach ($reglements as $reg) {
            $contrat = DB::table('contrats_auto')->where('id', $reg->contrat_id)->first();
            $numContrat = $contrat ? $contrat->numero_contrat : '';
            $clientId = $contrat ? $contrat->client_id : null;

            $method = match(strtolower($reg->mode_reglement ?? 'especes')) {
                'especes' => 'cash',
                'cheque' => 'cheque',
                'virement' => 'bank_transfer',
                'carte' => 'card',
                default => 'cash',
            };

            // Check if already in ledger
            $exists = DB::table('financial_ledgers')
                ->where('amount', $reg->montant)
                ->where('contract_id', $reg->contrat_id)
                ->whereDate('entry_date', $reg->date_reglement)
                ->exists();

            if ($exists) {
                continue;
            }

            $chequeId = null;
            if (strtolower($reg->mode_reglement ?? '') === 'cheque' && Schema::hasTable('cheques')) {
                $chequeId = DB::table('cheques')->insertGetId([
                    'cheque_number' => $reg->reference_paiement ?: 'CHQ-' . str_pad($reg->id, 5, '0', STR_PAD_LEFT),
                    'bank_name' => 'Banque Marocaine',
                    'issuer_name' => $contrat?->souscripteur ?? 'Client',
                    'amount' => $reg->montant,
                    'due_date' => $reg->date_echeance_cheque ?: $reg->date_reglement,
                    'status' => 'received',
                    'client_id' => $clientId,
                    'contract_id' => $reg->contrat_id,
                    'notes' => 'Chèque pour règlement du contrat #' . $numContrat,
                    'created_at' => $reg->created_at ?? now(),
                    'updated_at' => $reg->updated_at ?? now(),
                ]);
            }

            if ($method === 'cash') {
                $totalCash += floatval($reg->montant);
            }

            $trxId = 'TRX-' . date('Y') . '-' . str_pad($reg->id, 6, '0', STR_PAD_LEFT);
            $recId = 'REC-' . date('Ymd') . '-' . str_pad($reg->id, 5, '0', STR_PAD_LEFT);

            DB::table('financial_ledgers')->insert([
                'uuid' => (string) Str::uuid(),
                'transaction_id' => $trxId,
                'entry_date' => $reg->date_reglement ?? now(),
                'category' => 'encaissement_prime',
                'entry_type' => 'credit',
                'amount' => $reg->montant,
                'currency' => 'DH',
                'payment_method' => $method,
                'status' => 'validated',
                'receipt_number' => $recId,
                'qr_code_hash' => md5($trxId . '|' . $reg->montant),
                'notes' => 'Règlement contrat #' . $numContrat . ($reg->reference_paiement ? ' (Réf: ' . $reg->reference_paiement . ')' : ''),
                'user_id' => 1,
                'client_id' => $clientId,
                'contract_id' => $reg->contrat_id,
                'cheque_id' => $chequeId,
                'cash_register_id' => $method === 'cash' ? $caisseId : null,
                'created_at' => $reg->created_at ?? now(),
                'updated_at' => $reg->updated_at ?? now(),
            ]);
        }

        if ($totalCash > 0 && $caisseId && Schema::hasTable('cash_registers')) {
            DB::table('cash_registers')->where('id', $caisseId)->update([
                'current_balance' => DB::raw("current_balance + {$totalCash}"),
                'expected_balance' => DB::raw("expected_balance + {$totalCash}"),
            ]);
        }
    }

    public function down(): void
    {
    }
};
