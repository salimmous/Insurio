<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agency_expenses') || !Schema::hasTable('financial_ledgers')) {
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

        $expenses = DB::table('agency_expenses')->get();

        foreach ($expenses as $exp) {
            // Check if already in financial_ledgers by metadata or exact match
            $existing = DB::table('financial_ledgers')
                ->where('category', 'charge')
                ->where('amount', $exp->amount)
                ->whereDate('entry_date', $exp->date_charge)
                ->first();

            if (!$existing) {
                $trxId = 'CHG-' . date('Ymd', strtotime($exp->date_charge ?? 'now')) . '-' . str_pad($exp->id, 5, '0', STR_PAD_LEFT);
                $recId = 'REC-CHG-' . date('Ymd', strtotime($exp->date_charge ?? 'now')) . '-' . str_pad($exp->id, 5, '0', STR_PAD_LEFT);

                DB::table('financial_ledgers')->insert([
                    'uuid' => (string) Str::uuid(),
                    'transaction_id' => $trxId,
                    'entry_date' => $exp->date_charge ?? now(),
                    'category' => 'charge',
                    'entry_type' => 'debit',
                    'amount' => $exp->amount,
                    'currency' => 'DH',
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'receipt_number' => $recId,
                    'qr_code_hash' => md5($trxId . '|' . $exp->amount),
                    'notes' => 'Charge Agence: ' . $exp->title . ($exp->description ? ' - ' . $exp->description : ''),
                    'user_id' => 1,
                    'branch_id' => $exp->succursale_id ?: null,
                    'cash_register_id' => $caisseId,
                    'metadata' => json_encode([
                        'agency_expense_id' => $exp->id,
                        'category_type' => $exp->category,
                    ]),
                    'created_at' => $exp->created_at ?? now(),
                    'updated_at' => $exp->updated_at ?? now(),
                ]);
            }
        }

        // Recalculate Cash Register balances mathematically based on all cash transactions
        if (Schema::hasTable('cash_registers') && Schema::hasTable('financial_ledgers')) {
            $totalCashCredit = (float) DB::table('financial_ledgers')
                ->where('payment_method', 'cash')
                ->where('entry_type', 'credit')
                ->whereIn('status', ['completed', 'posted', 'approved'])
                ->sum('amount');

            $totalCashDebit = (float) DB::table('financial_ledgers')
                ->where('payment_method', 'cash')
                ->where('entry_type', 'debit')
                ->whereIn('status', ['completed', 'posted', 'approved'])
                ->sum('amount');

            $netCash = $totalCashCredit - $totalCashDebit;

            DB::table('cash_registers')->update([
                'current_balance' => $netCash,
                'expected_balance' => $netCash,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
    }
};
