<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Dossiers Table
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('dossier_number')->unique();
            $table->string('type'); // claim, complaint, payment_issue, returned_cheque, etc.
            $table->string('status')->default('open');
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->string('urgency')->default('medium'); // low, medium, high, critical
            
            $table->foreignId('succursale_id')->constrained('succursales')->onDelete('cascade');
            $table->foreignId('assigned_employee_id')->nullable()->constrained('employes')->onDelete('set null');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->foreignId('compagnie_id')->nullable()->constrained('compagnies')->onDelete('set null');
            
            $table->date('creation_date');
            $table->date('closing_date')->nullable();
            $table->integer('progress')->default(0);
            $table->json('custom_fields')->nullable();
            $table->json('checklist')->nullable();
            $table->timestamps();
        });

        // 2. Dossier Accident Details (Claims / Sinistres specifics)
        Schema::create('dossier_accident_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->date('accident_date')->nullable();
            $table->time('accident_time')->nullable();
            $table->string('accident_city')->nullable();
            $table->text('accident_address')->nullable();
            $table->string('accident_gps')->nullable();
            $table->string('weather')->nullable();
            $table->string('road_condition')->nullable();
            $table->boolean('police_present')->default(false);
            $table->boolean('ambulance_present')->default(false);
            $table->text('witnesses')->nullable();
            $table->integer('number_of_vehicles')->default(1);
            $table->string('responsible_party')->nullable();
            $table->text('description')->nullable();
            $table->string('sketch_path')->nullable();
            
            // Accident descriptions
            $table->text('statement_client')->nullable();
            $table->text('notes_employee')->nullable();
            $table->text('notes_police')->nullable();
            $table->text('notes_expert')->nullable();
            $table->text('notes_insurance')->nullable();
            $table->text('notes_garage')->nullable();
            $table->timestamps();
        });

        // 3. Dossier Involved Parties
        Schema::create('dossier_involved_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->string('name');
            $table->string('role'); // client, driver, owner, victim, witness, police, insurance, expert, garage, lawyer
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Dossier Expert Details
        Schema::create('dossier_expert_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->string('expert_name');
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->date('visit_date')->nullable();
            $table->time('visit_time')->nullable();
            $table->text('report')->nullable();
            $table->decimal('estimated_damage', 15, 2)->default(0.00);
            $table->decimal('repair_cost', 15, 2)->default(0.00);
            $table->boolean('repairable')->default(true);
            $table->boolean('total_loss')->default(false);
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });

        // 5. Dossier Garage Details
        Schema::create('dossier_garage_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->string('garage_name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('appointment_at')->nullable();
            $table->date('repair_start_date')->nullable();
            $table->date('repair_end_date')->nullable();
            $table->decimal('estimate', 15, 2)->default(0.00);
            $table->decimal('invoice', 15, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->timestamps();
        });

        // 6. Dossier Cheque Details
        Schema::create('dossier_cheque_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->string('cheque_number');
            $table->string('bank');
            $table->date('issue_date')->nullable();
            $table->date('deposit_date')->nullable();
            $table->date('clearance_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->string('reason')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // 7. Dossier Followers (watcher users)
        Schema::create('dossier_followers', function (Blueprint $table) {
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->primary(['dossier_id', 'user_id']);
        });

        // 8. Dossier Follow Ups
        Schema::create('dossier_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->date('date');
            $table->foreignId('employee_id')->nullable()->constrained('employes')->onDelete('set null');
            $table->dateTime('reminder_at')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->text('notes')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_follow_ups');
        Schema::dropIfExists('dossier_followers');
        Schema::dropIfExists('dossier_cheque_details');
        Schema::dropIfExists('dossier_garage_details');
        Schema::dropIfExists('dossier_expert_details');
        Schema::dropIfExists('dossier_involved_parties');
        Schema::dropIfExists('dossier_accident_details');
        Schema::dropIfExists('dossiers');
    }
};
