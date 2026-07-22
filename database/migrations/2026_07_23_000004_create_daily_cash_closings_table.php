<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('daily_cash_closings')) {
            Schema::create('daily_cash_closings', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->date('closing_date')->index();
                $table->unsignedBigInteger('cash_register_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                
                $table->decimal('opening_balance', 15, 2)->default(0.00);
                $table->decimal('total_cash_in', 15, 2)->default(0.00);
                $table->decimal('total_cash_out', 15, 2)->default(0.00);
                $table->decimal('total_transfers_in', 15, 2)->default(0.00);
                $table->decimal('total_card_in', 15, 2)->default(0.00);
                $table->decimal('total_cheques_received', 15, 2)->default(0.00);
                $table->decimal('total_cheques_deposited', 15, 2)->default(0.00);
                $table->decimal('total_bank_deposits', 15, 2)->default(0.00);
                $table->decimal('total_bank_withdrawals', 15, 2)->default(0.00);
                $table->decimal('total_expenses', 15, 2)->default(0.00);
                $table->decimal('total_commissions', 15, 2)->default(0.00);
                $table->decimal('total_refunds', 15, 2)->default(0.00);
                $table->decimal('expected_closing_balance', 15, 2)->default(0.00);
                $table->decimal('physical_closing_balance', 15, 2)->default(0.00);
                $table->decimal('variance_amount', 15, 2)->default(0.00);
                $table->decimal('net_cash', 15, 2)->default(0.00);
                $table->decimal('net_profit', 15, 2)->default(0.00);
                
                $table->enum('status', ['open', 'closed', 'archived'])->default('open')->index();
                $table->unsignedBigInteger('closed_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->string('manager_signature')->nullable();
                $table->string('employee_signature')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_cash_closings');
    }
};
