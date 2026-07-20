<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('clients')) {
            return;
        }

        // 1. Update clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->renameColumn('prenom', 'first_name');
            $table->renameColumn('nom', 'last_name');
            $table->string('company_name')->nullable()->after('prenom'); // using temporary positions
            $table->renameColumn('type', 'client_type');
            $table->renameColumn('telephone', 'phone');
            $table->string('whatsapp_number')->nullable()->after('telephone');
            $table->string('passport')->nullable()->after('cin');
            $table->date('date_of_birth')->nullable()->after('passport');
            $table->string('profession')->nullable()->after('date_of_birth');
            $table->renameColumn('adresse', 'address');
            $table->string('city')->nullable()->after('adresse');
            $table->text('notes')->nullable()->after('city');
            $table->unsignedBigInteger('succursale_id')->nullable()->after('notes');
            $table->unsignedBigInteger('created_by')->nullable()->after('succursale_id');
        });

        // Data migration for client_type and UUIDs
        $clients = DB::table('clients')->get();
        foreach ($clients as $client) {
            $newType = $client->client_type === 'entreprise' ? 'company' : 'individual';
            $companyName = $client->client_type === 'entreprise' ? $client->last_name : null;

            DB::table('clients')->where('id', $client->id)->update([
                'uuid' => (string) Str::uuid(),
                'client_type' => $newType,
                'company_name' => $companyName,
            ]);
        }

        // 2. Extend documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('id');
            $table->unsignedBigInteger('contract_id')->nullable()->after('client_id');
            $table->string('type')->default('other')->after('contract_id');
            $table->string('file_path')->nullable()->after('type');
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('file_name');
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('mime_type');
        });

        // Data migration for existing documents if any
        $docs = DB::table('documents')->get();
        foreach ($docs as $doc) {
            $clientId = null;
            $contractId = null;
            if ($doc->documentable_type === 'App\\Models\\Client') {
                $clientId = $doc->documentable_id;
            } elseif ($doc->documentable_type === 'App\\Models\\ContratAuto' || $doc->documentable_type === 'App\\Models\\Contract') {
                $contractId = $doc->documentable_id;
                // Try to find the client for this contract
                $contract = DB::table('contracts')->where('id', $contractId)->first();
                if ($contract) {
                    $clientId = $contract->client_id;
                }
            }

            DB::table('documents')->where('id', $doc->id)->update([
                'client_id' => $clientId,
                'contract_id' => $contractId,
                'file_path' => $doc->chemin_fichier,
                'file_name' => $doc->nom,
            ]);
        }

        // 3. Create tasks table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('status')->default('todo'); // todo, progress, completed
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // 4. Create communications table
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('type'); // whatsapp, email, call, note
            $table->text('message');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 5. Create renewals table
        Schema::create('renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->date('reminder_date')->nullable();
            $table->integer('days_before_expiry')->default(30);
            $table->string('status')->default('pending'); // pending, contacted, renewed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('clients')) {
            return;
        }

        Schema::dropIfExists('renewals');
        Schema::dropIfExists('communications');
        Schema::dropIfExists('tasks');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'client_id', 'contract_id', 'type', 'file_path', 'file_name',
                'mime_type', 'uploaded_by'
            ]);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('first_name', 'prenom');
            $table->renameColumn('last_name', 'nom');
            $table->renameColumn('client_type', 'type');
            $table->renameColumn('phone', 'telephone');
            $table->renameColumn('address', 'adresse');
            $table->dropColumn([
                'uuid', 'company_name', 'whatsapp_number', 'passport',
                'date_of_birth', 'profession', 'city', 'notes',
                'succursale_id', 'created_by'
            ]);
        });
    }
};
