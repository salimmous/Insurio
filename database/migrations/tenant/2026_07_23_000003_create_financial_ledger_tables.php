<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bank Accounts
        if (!Schema::hasTable('bank_accounts')) {
            Schema::create('bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('bank_name'); // e.g. Attijariwafa Bank, BCP, BMCE BOA, CIH, SGMB, CDM
                $table->string('agency')->nullable();
                $table->string('iban')->nullable();
                $table->string('rib')->nullable();
                $table->string('account_number')->nullable();
                $table->string('swift')->nullable();
                $table->decimal('opening_balance', 15, 2)->default(0.00);
                $table->decimal('current_balance', 15, 2)->default(0.00);
                $table->string('currency')->default('DH');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Cash Registers (Caisses d'Agence)
        if (!Schema::hasTable('cash_registers')) {
            Schema::create('cash_registers', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('name'); // e.g. Caisse Principale, Caisse Comptoir 1
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->decimal('opening_balance', 15, 2)->default(0.00);
                $table->decimal('current_balance', 15, 2)->default(0.00);
                $table->decimal('expected_balance', 15, 2)->default(0.00);
                $table->decimal('physical_balance', 15, 2)->default(0.00);
                $table->decimal('variance_amount', 15, 2)->default(0.00); // Écart de caisse
                $table->boolean('is_open')->default(true);
                $table->timestamps();
            });
        }

        // 3. Moroccan Cheques Tracking
        if (!Schema::hasTable('cheques')) {
            Schema::create('cheques', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('cheque_number')->index();
                $table->string('bank_name');
                $table->string('agency')->nullable();
                $table->string('issuer');
                $table->string('beneficiary')->nullable();
                $table->date('issue_date')->nullable();
                $table->date('due_date')->index();
                $table->date('deposit_date')->nullable();
                $table->date('collection_date')->nullable();
                $table->decimal('amount', 15, 2);
                $table->string('currency')->default('DH');
                $table->enum('status', [
                    'created', 'received', 'pending', 'deposited', 'under_collection',
                    'collected', 'validated', 'returned', 'rejected', 'cancelled', 'archived'
                ])->default('received')->index();
                $table->string('reference')->nullable();
                $table->string('front_image')->nullable();
                $table->string('back_image')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('contract_id')->nullable();
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->timestamps();
            });
        }

        // 4. Financial General Ledger (Grand Livre Immutable Log)
        if (!Schema::hasTable('financial_ledgers')) {
            Schema::create('financial_ledgers', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('transaction_id')->unique()->index(); // e.g. TRX-2026-00001
                $table->timestamp('entry_date')->useCurrent()->index();
                $table->string('category'); // encaissement_prime, reglement_sinistre, commission, charge, virement, depot_caisse, etc.
                $table->enum('entry_type', ['credit', 'debit'])->index(); // credit = in, debit = out
                $table->decimal('amount', 15, 2);
                $table->string('currency')->default('DH');
                $table->enum('payment_method', [
                    'cash', 'cheque', 'transfer', 'deposit', 'card', 'tpe', 'online', 'mobile',
                    'internal_transfer', 'refund', 'commission', 'expense', 'salary'
                ])->default('cash');
                $table->enum('status', ['pending', 'approved', 'completed', 'rejected', 'cancelled'])->default('completed')->index();
                $table->string('receipt_number')->nullable()->unique(); // Serial receipt number
                $table->string('qr_code_hash')->nullable();
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                
                // Tracing foreign links
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->unsignedBigInteger('last_modified_by')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('contract_id')->nullable();
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->unsignedBigInteger('cheque_id')->nullable();
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->unsignedBigInteger('cash_register_id')->nullable();
                
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 5. Inalterable Financial Audit Logs
        if (!Schema::hasTable('financial_audit_logs')) {
            Schema::create('financial_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->unsignedBigInteger('ledger_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action'); // created, modified, approved, rejected, voided
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        // 6. Payment Approval Workflow Queue
        if (!Schema::hasTable('payment_approvals')) {
            Schema::create('payment_approvals', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->unsignedBigInteger('ledger_id')->nullable();
                $table->unsignedBigInteger('requested_by')->nullable();
                $table->unsignedBigInteger('approved_by_manager')->nullable();
                $table->unsignedBigInteger('approved_by_finance')->nullable();
                $table->decimal('amount', 15, 2);
                $table->enum('status', ['pending_manager', 'pending_finance', 'approved', 'rejected'])->default('pending_manager');
                $table->text('manager_notes')->nullable();
                $table->text('finance_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_approvals');
        Schema::dropIfExists('financial_audit_logs');
        Schema::dropIfExists('financial_ledgers');
        Schema::dropIfExists('cheques');
        Schema::dropIfExists('cash_registers');
        Schema::dropIfExists('bank_accounts');
    }
};
