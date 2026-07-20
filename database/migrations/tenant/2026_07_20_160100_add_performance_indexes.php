<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 3 - Performance: Add composite indexes on high-frequency query columns.
 * These indexes dramatically improve dashboard load, search, and filter operations.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── contracts ───────────────────────────────────────────────────────────
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                // Most common filter: active contracts by branch
                if (!$this->indexExists('contracts', 'contracts_statut_succursale_idx')) {
                    $table->index(['statut', 'succursale_id'], 'contracts_statut_succursale_idx');
                }
                // Dashboard expiry bucketing
                if (!$this->indexExists('contracts', 'contracts_statut_end_date_idx')) {
                    $table->index(['statut', 'end_date'], 'contracts_statut_end_date_idx');
                }
                // Start-date for monthly chart aggregation
                if (!$this->indexExists('contracts', 'contracts_start_date_idx')) {
                    $table->index(['start_date'], 'contracts_start_date_idx');
                }
                // Client lookup
                if (!$this->indexExists('contracts', 'contracts_client_id_idx')) {
                    $table->index(['client_id'], 'contracts_client_id_idx');
                }
                // Agent ranking
                if (!$this->indexExists('contracts', 'contracts_employe_id_idx')) {
                    $table->index(['employe_id'], 'contracts_employe_id_idx');
                }
            });
        }

        // ─── clients ─────────────────────────────────────────────────────────────
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                // Segment filter (individual vs company)
                if (!$this->indexExists('clients', 'clients_client_type_idx')) {
                    $table->index(['client_type'], 'clients_client_type_idx');
                }
            });
        }

        // ─── reglements ──────────────────────────────────────────────────────────
        if (Schema::hasTable('reglements')) {
            Schema::table('reglements', function (Blueprint $table) {
                // Sum by contrat_id is the hot path for solde calculation
                if (!$this->indexExists('reglements', 'reglements_contrat_id_idx')) {
                    $table->index(['contrat_id'], 'reglements_contrat_id_idx');
                }
            });
        }

        // ─── activity_logs ───────────────────────────────────────────────────────
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                if (!$this->indexExists('activity_logs', 'activity_logs_created_at_idx')) {
                    $table->index(['created_at'], 'activity_logs_created_at_idx');
                }
            });
        }

        // ─── renewals ────────────────────────────────────────────────────────────
        if (Schema::hasTable('renewals')) {
            Schema::table('renewals', function (Blueprint $table) {
                if (!$this->indexExists('renewals', 'renewals_contract_status_idx')) {
                    $table->index(['contract_id', 'status'], 'renewals_contract_status_idx');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                foreach (['contracts_statut_succursale_idx', 'contracts_statut_end_date_idx', 'contracts_start_date_idx', 'contracts_client_id_idx', 'contracts_employe_id_idx'] as $idx) {
                    try { $table->dropIndex($idx); } catch (\Exception $e) {}
                }
            });
        }

        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                try { $table->dropIndex('clients_client_type_idx'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('reglements')) {
            Schema::table('reglements', function (Blueprint $table) {
                try { $table->dropIndex('reglements_contrat_id_idx'); } catch (\Exception $e) {}
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        // SQLite doesn't support checking indexes the same way, just return false to try creating
        if (\DB::getDriverName() === 'sqlite') {
            return false;
        }

        $indexes = \DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return !empty($indexes);
    }
};
