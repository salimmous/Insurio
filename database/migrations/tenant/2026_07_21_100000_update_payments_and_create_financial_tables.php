<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing payments table
        Schema::table('payments', function (Blueprint $table) {
            // Add UUID and Payment Number
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('payment_number')->nullable()->unique()->after('uuid');

            // Policy, Company, Employee, Branch relations
            $table->string('policy_id')->nullable()->after('contract_id');
            $table->foreignId('company_id')->nullable()->after('policy_id')->constrained('compagnies')->onDelete('set null');
            $table->foreignId('employee_id')->nullable()->after('company_id')->constrained('employes')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->after('employee_id')->constrained('succursales')->onDelete('set null');

            // Financial details
            $table->string('currency', 10)->default('DH')->after('amount');
            $table->decimal('paid_amount', 12, 2)->default(0.00)->after('amount');
            $table->decimal('remaining_amount', 12, 2)->default(0.00)->after('paid_amount');
            $table->decimal('tax', 12, 2)->default(0.00)->after('remaining_amount');
            $table->decimal('discount', 12, 2)->default(0.00)->after('tax');

            // Rename status to payment_status if status exists, or add payment_status
            if (Schema::hasColumn('payments', 'status')) {
                $table->renameColumn('status', 'payment_status');
            } else {
                $table->string('payment_status')->default('draft');
            }

            // Timestamps
            $table->datetime('payment_date')->nullable();
            $table->date('due_date')->nullable();

            // References
            $table->string('reference_number')->nullable();
            $table->string('receipt_number')->nullable();

            // Cheque and Bank Details
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('cheque_number')->nullable();
            $table->date('cheque_issue_date')->nullable();
            $table->date('cheque_deposit_date')->nullable();
            $table->date('cheque_clearance_date')->nullable();

            // Audit & File logs
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        });

        // 2. Create payment_installments table
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->decimal('remaining_amount', 12, 2)->default(0.00);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('status')->default('pending'); // pending, paid, overdue, cancelled
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Create bank_reconciliations table
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->date('deposit_date');
            $table->string('reference');
            $table->boolean('matched')->default(false);
            $table->decimal('difference', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Create payment_follow_ups table
        Schema::create('payment_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('assigned_employee_id')->nullable()->constrained('employes')->onDelete('set null');
            $table->date('reminder_date');
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->text('notes')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // 5. Create payment_audit_logs table
        Schema::create('payment_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_audit_logs');
        Schema::dropIfExists('payment_follow_ups');
        Schema::dropIfExists('bank_reconciliations');
        Schema::dropIfExists('payment_installments');

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_status')) {
                $table->renameColumn('payment_status', 'status');
            }
            
            $table->dropForeign(['company_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
            
            $table->dropColumn([
                'uuid',
                'payment_number',
                'policy_id',
                'company_id',
                'employee_id',
                'branch_id',
                'currency',
                'paid_amount',
                'remaining_amount',
                'tax',
                'discount',
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
                'approved_by'
            ]);
        });
    }
};
