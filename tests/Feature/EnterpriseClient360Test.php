<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Task;
use App\Models\Communication;
use Livewire\Livewire;
use App\Livewire\Admin\ClientProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class EnterpriseClient360Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable();
                }
                if (!Schema::hasColumn('clients', 'satisfaction_score')) {
                    $table->unsignedTinyInteger('satisfaction_score')->default(5);
                }
                if (!Schema::hasColumn('clients', 'last_contact_at')) {
                    $table->timestamp('last_contact_at')->nullable();
                }
                if (!Schema::hasColumn('clients', 'next_contact_at')) {
                    $table->timestamp('next_contact_at')->nullable();
                }
                if (!Schema::hasColumn('clients', 'family_members')) {
                    $table->json('family_members')->nullable();
                }
                if (!Schema::hasColumn('clients', 'beneficiaries')) {
                    $table->json('beneficiaries')->nullable();
                }
            });
        }

        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id');
                $table->string('title');
                $table->date('due_date')->nullable();
                $table->string('priority')->default('normal');
                $table->string('type')->default('call');
                $table->string('status')->default('pending');
                $table->foreignId('assigned_to')->nullable();
                $table->foreignId('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('communications')) {
            Schema::create('communications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id');
                $table->string('type')->default('note');
                $table->text('message');
                $table->foreignId('user_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_client_360_profile_renders_successfully()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'first_name' => 'Ahmed',
            'last_name' => 'Alami',
            'client_type' => 'individual',
            'phone' => '0661234567',
            'city' => 'Casablanca',
        ]);

        Livewire::actingAs($user)
            ->test(ClientProfile::class, ['clientId' => $client->id])
            ->assertStatus(200)
            ->assertSee('Ahmed Alami')
            ->assertSee('Casablanca');
    }

    public function test_client_360_can_update_assigned_advisor_and_satisfaction()
    {
        $user = User::factory()->create();
        $advisor = User::factory()->create(['name' => 'Sarah Broker']);
        $client = Client::create([
            'first_name' => 'Karim',
            'last_name' => 'Bennani',
            'client_type' => 'individual',
        ]);

        Livewire::actingAs($user)
            ->test(ClientProfile::class, ['clientId' => $client->id])
            ->set('assignedAdvisorId', $advisor->id)
            ->call('updateAdvisor')
            ->call('setSatisfaction', 4);

        $client->refresh();

        $this->assertEquals($advisor->id, $client->assigned_to);
        $this->assertEquals(4, $client->satisfaction_score);
    }

    public function test_client_360_can_add_family_members_and_beneficiaries()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'first_name' => 'Youssef',
            'last_name' => 'Tazi',
            'client_type' => 'individual',
        ]);

        Livewire::actingAs($user)
            ->test(ClientProfile::class, ['clientId' => $client->id])
            ->set('familyMemberName', 'Meryem Tazi')
            ->set('familyMemberRelation', 'conjoint')
            ->call('addFamilyMember')
            ->set('beneficiaryName', 'Omar Tazi')
            ->set('beneficiaryRelation', 'enfant')
            ->set('beneficiaryPercentage', 100)
            ->call('addBeneficiary');

        $client->refresh();

        $this->assertCount(1, $client->family_members);
        $this->assertEquals('Meryem Tazi', $client->family_members[0]['name']);

        $this->assertCount(1, $client->beneficiaries);
        $this->assertEquals('Omar Tazi', $client->beneficiaries[0]['name']);
    }

    public function test_client_360_can_create_tasks_and_add_timeline_communications()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'first_name' => 'Fatima',
            'last_name' => 'Zohra',
            'client_type' => 'individual',
        ]);

        Livewire::actingAs($user)
            ->test(ClientProfile::class, ['clientId' => $client->id])
            ->set('taskTitle', 'Relance police automobile')
            ->set('taskDueDate', '2026-08-01')
            ->set('taskPriority', 'high')
            ->set('taskType', 'call')
            ->call('addTask')
            ->set('communicationType', 'whatsapp')
            ->set('communicationMessage', 'Message WhatsApp envoyé au client.')
            ->call('addCommunication');

        $this->assertDatabaseHas('tasks', [
            'client_id' => $client->id,
            'title' => 'Relance police automobile',
            'priority' => 'high',
        ]);

        $this->assertDatabaseHas('communications', [
            'client_id' => $client->id,
            'type' => 'whatsapp',
            'message' => 'Message WhatsApp envoyé au client.',
        ]);
    }
}
