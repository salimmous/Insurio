<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('security_audit_logs')) {
            Schema::create('security_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('event_type')->index();
                $table->string('status', 30)->default('success')->index();
                
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable()->index();
                $table->string('agency_name')->nullable()->index();
                $table->string('branch_name')->nullable()->index();
                $table->string('role_name')->nullable()->index();
                
                $table->string('ip_address', 45)->nullable()->index();
                $table->text('user_agent')->nullable();
                $table->string('browser', 100)->nullable();
                $table->string('os', 100)->nullable();
                $table->string('device', 100)->nullable();
                $table->string('country', 100)->nullable();
                $table->string('city', 100)->nullable();
                
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }
    }

    public function down(): void
    {
        // Immutability note: In production migration rollbacks are restricted.
        Schema::dropIfExists('security_audit_logs');
    }
};
