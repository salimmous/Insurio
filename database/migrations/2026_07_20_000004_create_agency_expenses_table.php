<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agency_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // loyer, electricite, eau, salaire, autre
            $table->decimal('amount', 15, 2);
            $table->date('date_charge');
            $table->text('description')->nullable();
            $table->foreignId('succursale_id')->nullable()->constrained('succursales')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_expenses');
    }
};
