<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_active_sessions')) {
            Schema::create('user_active_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 255)->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable()->index();
                $table->string('agency_name')->nullable();
                $table->string('branch_name')->nullable();
                $table->string('role_name')->nullable();
                
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('browser', 100)->nullable();
                $table->string('os', 100)->nullable();
                $table->string('device', 100)->nullable();
                $table->string('country', 100)->default('Maroc');
                $table->string('city', 100)->default('Casablanca');
                
                $table->string('status', 30)->default('active')->index();
                $table->timestamp('login_at')->useCurrent();
                $table->timestamp('last_activity_at')->useCurrent()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_active_sessions');
    }
};
