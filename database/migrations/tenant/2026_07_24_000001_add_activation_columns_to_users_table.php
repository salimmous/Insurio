<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'first_login')) {
                    $table->boolean('first_login')->default(false)->after('password');
                }
                if (!Schema::hasColumn('users', 'activation_token')) {
                    $table->string('activation_token')->nullable()->after('first_login');
                }
                if (!Schema::hasColumn('users', 'activation_token_expires_at')) {
                    $table->timestamp('activation_token_expires_at')->nullable()->after('activation_token');
                }
                if (!Schema::hasColumn('users', 'password_changed_at')) {
                    $table->timestamp('password_changed_at')->nullable()->after('activation_token_expires_at');
                }
                if (!Schema::hasColumn('users', 'activated_at')) {
                    $table->timestamp('activated_at')->nullable()->after('password_changed_at');
                }
                if (!Schema::hasColumn('users', 'two_factor_secret')) {
                    $table->text('two_factor_secret')->nullable();
                }
                if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                    $table->timestamp('two_factor_confirmed_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                    $table->text('two_factor_recovery_codes')->nullable();
                }
                if (!Schema::hasColumn('users', 'failed_attempts')) {
                    $table->integer('failed_attempts')->default(0);
                }
                if (!Schema::hasColumn('users', 'locked_until')) {
                    $table->timestamp('locked_until')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $cols = array_filter([
                    'first_login',
                    'activation_token',
                    'activation_token_expires_at',
                    'password_changed_at',
                    'activated_at',
                    'two_factor_secret',
                    'two_factor_confirmed_at',
                    'two_factor_recovery_codes',
                ], fn($c) => Schema::hasColumn('users', $c));
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
