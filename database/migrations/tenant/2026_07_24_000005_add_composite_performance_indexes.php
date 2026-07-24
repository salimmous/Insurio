<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->index(['client_id', 'status'], 'idx_contracts_client_status');
                $table->index(['status', 'date_echeance'], 'idx_contracts_status_echeance');
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->index(['client_id', 'status'], 'idx_payments_client_status');
                $table->index(['payment_method', 'status'], 'idx_payments_method_status');
            });
        }

        if (Schema::hasTable('security_audit_logs')) {
            Schema::table('security_audit_logs', function (Blueprint $table) {
                $table->index(['user_id', 'event_type'], 'idx_audit_user_event');
                $table->index(['event_type', 'created_at'], 'idx_audit_event_created');
            });
        }

        if (Schema::hasTable('user_active_sessions')) {
            Schema::table('user_active_sessions', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'idx_sessions_user_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropIndex('idx_contracts_client_status');
                $table->dropIndex('idx_contracts_status_echeance');
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('idx_payments_client_status');
                $table->dropIndex('idx_payments_method_status');
            });
        }

        if (Schema::hasTable('security_audit_logs')) {
            Schema::table('security_audit_logs', function (Blueprint $table) {
                $table->dropIndex('idx_audit_user_event');
                $table->dropIndex('idx_audit_event_created');
            });
        }

        if (Schema::hasTable('user_active_sessions')) {
            Schema::table('user_active_sessions', function (Blueprint $table) {
                $table->dropIndex('idx_sessions_user_status');
            });
        }
    }
};
