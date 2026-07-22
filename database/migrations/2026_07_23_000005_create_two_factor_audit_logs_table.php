<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('two_factor_audit_logs')) {
            Schema::create('two_factor_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('event'); // 2FA Enabled, 2FA Disabled, Recovery Code Used, Failed Verification, Successful Verification, Device Trusted, Device Removed
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('device')->nullable();
                $table->string('country', 100)->default('Morocco');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('two_factor_settings')) {
            Schema::create('two_factor_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('force_2fa_all')->default(false);
                $table->boolean('force_2fa_admins')->default(false);
                $table->boolean('force_2fa_finance')->default(false);
                $table->boolean('force_2fa_managers')->default(false);
                $table->json('force_2fa_roles')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_settings');
        Schema::dropIfExists('two_factor_audit_logs');
    }
};
