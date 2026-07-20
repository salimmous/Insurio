<?php

use App\Models\Setting;
use Livewire\Volt\Component;

new class extends Component
{
    public string $mail_host = '';
    public string $mail_port = '';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_encryption = '';
    public string $mail_from_address = '';
    public string $mail_from_name = '';

    public function mount(): void
    {
        $this->mail_host = Setting::get('mail_host', '');
        $this->mail_port = Setting::get('mail_port', '587');
        $this->mail_username = Setting::get('mail_username', '');
        $this->mail_password = Setting::get('mail_password', '');
        $this->mail_encryption = Setting::get('mail_encryption', 'tls');
        $this->mail_from_address = Setting::get('mail_from_address', '');
        $this->mail_from_name = Setting::get('mail_from_name', '');
    }

    public function updateMailSettings(): void
    {
        $validated = $this->validate([
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'numeric'],
            'mail_username' => ['nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['required', 'string'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string)($value ?? ''));
        }

        $this->dispatch('settings-updated');
    }
}; ?>

<section class="max-w-xl">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Configuration SMTP') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Configurez le serveur SMTP de votre agence pour l'envoi d'emails et de notifications.") }}
        </p>
    </header>

    <form wire:submit="updateMailSettings" class="mt-6 space-y-6">
        <div>
            <x-input-label for="mail_host" :value="__('Serveur SMTP (Host)')" />
            <x-text-input wire:model="mail_host" id="mail_host" name="mail_host" type="text" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('mail_host')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="mail_port" :value="__('Port')" />
                <x-text-input wire:model="mail_port" id="mail_port" name="mail_port" type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('mail_port')" />
            </div>

            <div>
                <x-input-label for="mail_encryption" :value="__('Chiffrement')" />
                <select wire:model="mail_encryption" id="mail_encryption" name="mail_encryption" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="none">{{ __('Aucun') }}</option>
                    <option value="tls">TLS</option>
                    <option value="ssl">SSL</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('mail_encryption')" />
            </div>
        </div>

        <div>
            <x-input-label for="mail_username" :value="__('Nom d\'utilisateur')" />
            <x-text-input wire:model="mail_username" id="mail_username" name="mail_username" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('mail_username')" />
        </div>

        <div>
            <x-input-label for="mail_password" :value="__('Mot de passe')" />
            <x-text-input wire:model="mail_password" id="mail_password" name="mail_password" type="password" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('mail_password')" />
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('Adresse Expéditeur') }}</h3>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="mail_from_address" :value="__('Email Expéditeur (From Address)')" />
                    <x-text-input wire:model="mail_from_address" id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" required />
                    <x-input-error class="mt-2" :messages="$errors->get('mail_from_address')" />
                </div>

                <div>
                    <x-input-label for="mail_from_name" :value="__('Nom Expéditeur (From Name)')" />
                    <x-text-input wire:model="mail_from_name" id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" required />
                    <x-input-error class="mt-2" :messages="$errors->get('mail_from_name')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            <x-action-message class="me-3" on="settings-updated">
                {{ __('Enregistré.') }}
            </x-action-message>
        </div>
    </form>
</section>
