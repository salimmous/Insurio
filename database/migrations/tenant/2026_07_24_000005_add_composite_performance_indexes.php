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
                $statusCol = Schema::hasColumn('contracts', 'statut') ? 'statut' : (Schema::hasColumn('contracts', 'status') ? 'status' : null);
                if ($statusCol && Schema::hasColumn('contracts', 'client_id')) {
                    $table->index(['client_id', $statusCol], 'idx_contracts_client_status');
                }
                if ($statusCol && Schema::hasColumn('contracts', 'date_echeance')) {
                    $table->index([$statusCol, 'date_echeance'], 'idx_contracts_status_echeance');
                }
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $statusCol = Schema::hasColumn('payments', 'status') ? 'status' : (Schema::hasColumn('payments', 'statut') ? 'statut' : null);
                if ($statusCol && Schema::hasColumn('payments', 'client_id')) {
                    $table->index(['client_id', $statusCol], 'idx_payments_client_status');
                }
                if ($statusCol && Schema::hasColumn('payments', 'payment_method')) {
                    $table->index(['payment_method', $statusCol], 'idx_payments_method_status');
                }
            });
        }

        if (Schema::hasTable('security_audit_logs')) {
            Schema::table('security_audit_logs', function (Blueprint $table) {
                if (Schema::hasColumn('security_audit_logs', 'user_id') && Schema::hasColumn('security_audit_logs', 'event_type')) {
                    $table->index(['user_id', 'event_type'], 'idx_audit_user_event');
                }
                if (Schema::hasColumn('security_audit_logs', 'event_type') && Schema::hasColumn('security_audit_logs', 'created_at')) {
                    $table->index(['event_type', 'created_at'], 'idx_audit_event_created');
                }
            });
        }

        if (Schema::hasTable('user_active_sessions')) {
            Schema::table('user_active_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('user_active_sessions', 'user_id') && Schema::hasColumn('user_active_sessions', 'status')) {
                    $table->index(['user_id', 'status'], 'idx_sessions_user_status');
                }
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
