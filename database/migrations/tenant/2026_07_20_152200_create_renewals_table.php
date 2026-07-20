<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('renewals')) {
            Schema::table('renewals', function (Blueprint $table) {
                if (!Schema::hasColumn('renewals', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable()->after('contract_id');
                }
            });
            return;
        }

        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('reminder_date')->nullable();
            $table->string('status')->default('pending'); // pending, contacted, renewed, lost
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop only if we created it
    }
};
