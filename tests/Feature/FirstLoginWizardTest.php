<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class FirstLoginWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unactivated_user_is_redirected_to_activation_wizard()
    {
        $user = User::factory()->create([
            'first_login' => true,
            'activated_at' => null,
            'password' => Hash::make('TempPassword123!'),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('activation.wizard'));
    }

    public function test_user_can_access_activation_wizard()
    {
        $user = User::factory()->create([
            'first_login' => true,
            'activated_at' => null,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('activation.wizard'));

        $response->assertStatus(200);
    }

    public function test_wizard_enforces_password_complexity_rules()
    {
        $user = User::factory()->create([
            'first_login' => true,
            'activated_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Auth\FirstLoginWizard::class)
            ->set('currentStep', 2)
            ->set('new_password', 'simple')
            ->set('new_password_confirmation', 'simple')
            ->call('saveNewPassword')
            ->assertHasErrors(['new_password']);
    }

    public function test_wizard_completes_password_2fa_and_activation_flow()
    {
        $user = User::factory()->create([
            'first_login' => true,
            'activated_at' => null,
        ]);

        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        $validTotp = $google2fa->getCurrentOtp($secretKey);

        $component = Livewire::actingAs($user)
            ->test(\App\Livewire\Auth\FirstLoginWizard::class)
            ->set('currentStep', 2)
            ->set('new_password', 'SecurePass123!@#')
            ->set('new_password_confirmation', 'SecurePass123!@#')
            ->call('saveNewPassword')
            ->assertSet('currentStep', 3);

        $component->set('secretKey', $secretKey)
            ->set('currentStep', 4)
            ->set('totpCode', $validTotp)
            ->call('verifyTotpCode')
            ->assertSet('currentStep', 5);

        $component->set('savedRecoveryCodes', true)
            ->call('completeRecoveryStep')
            ->assertSet('currentStep', 6);

        $component->call('finishWizard')
            ->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertFalse($user->first_login);
        $this->assertNotNull($user->activated_at);
        $this->assertNotNull($user->two_factor_confirmed_at);
    }
}
