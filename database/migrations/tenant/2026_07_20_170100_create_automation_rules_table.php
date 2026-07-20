<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event'); // e.g., 'contract.expiring', 'contract.created'
            $table->json('conditions')->nullable(); // e.g., {"days_before_expiry": 30, "insurance_type": "auto"}
            $table->json('actions')->nullable(); // e.g., [{"type": "whatsapp"}, {"type": "task"}, {"type": "email"}]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};
