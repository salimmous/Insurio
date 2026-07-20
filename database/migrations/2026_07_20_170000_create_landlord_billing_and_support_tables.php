<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('status')->default('active'); // active, trialing, past_due, canceled
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('platform_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // paid, pending, failed, void
            $table->timestamp('due_at');
            $table->timestamp('paid_at')->nullable();
            $table->string('billing_reason')->default('subscription_cycle');
            $table->timestamps();
        });

        Schema::create('platform_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('succeeded'); // succeeded, failed, pending
            $table->string('payment_method')->default('stripe'); // stripe, bank_transfer, cash, check
            $table->string('transaction_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->string('creator_name');
            $table->string('creator_email');
            $table->string('subject');
            $table->string('status')->default('open'); // open, in_progress, resolved, closed
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->json('messages')->nullable();
            $table->timestamps();
        });

        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active_globally')->default(false);
            $table->json('rules')->nullable();
            $table->timestamps();
        });

        Schema::create('platform_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->json('payload')->nullable();
            $table->string('sent_to');
            $table->string('status')->default('delivered'); // delivered, failed
            $table->integer('response_code')->nullable();
            $table->timestamps();
        });

        Schema::create('system_backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('disk')->default('local');
            $table->unsignedBigInteger('size');
            $table->string('status')->default('success'); // success, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('platform_webhooks');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('platform_payments');
        Schema::dropIfExists('platform_invoices');
        Schema::dropIfExists('platform_subscriptions');
    }
};
