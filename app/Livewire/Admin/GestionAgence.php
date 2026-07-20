<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Spatie\Permission\Models\Role;

class GestionAgence extends Component
{
    use WithFileUploads;

    // White-Label Customization
    public $logo;
    public $favicon;
    public string $couleur_primaire = '';
    public string $couleur_secondaire = '';

    // Dynamic Access Control Configuration
    public array $enabled_pages = [];
    public array $enabled_roles = [];

    // General Settings
    public string $agency_name = '';
    public string $agency_phone = '';
    public string $agency_email = '';
    public string $agency_address = '';
    public string $commission_trigger = 'vente';
    public float $default_apporteur_rate = 10.0;
    public float $default_agent_rate = 8.5;

    // SMTP Settings
    public string $mail_host = '';
    public string $mail_port = '';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_encryption = 'tls';
    public string $mail_from_address = '';
    public string $mail_from_name = '';

    // Active tab: 'general', 'smtp', 'subscription', 'security'
    public string $activeTab = 'general';

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('agency-admin')) {
            abort(403, 'Accès réservé aux administrateurs de l\'agence.');
        }

        $this->agency_name = Setting::get('agency_name', tenant('name') ?? 'Insurio Assurance');
        $this->agency_phone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $this->agency_email = Setting::get('agency_email', 'contact@insurio.com');
        $this->agency_address = Setting::get('agency_address', 'Casablanca, Maroc');
        $this->commission_trigger = Setting::get('commission_trigger', 'vente');
        $this->default_apporteur_rate = (float)Setting::get('default_apporteur_rate', 12.50);
        $this->default_agent_rate = (float)Setting::get('default_agent_rate', 8.50);
        $this->couleur_primaire = tenant('couleur_primaire') ?? '#0EA5A0';
        $this->couleur_secondaire = tenant('couleur_secondaire') ?? '#0D9488';

        // Load Dynamic Access Settings
        $this->enabled_pages = json_decode(Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
        $this->enabled_roles = json_decode(Setting::get('enabled_roles', '[]'), true) ?: ['responsable-succursale', 'agent-commercial', 'comptable', 'consultation'];

        // Load SMTP Settings
        $this->mail_host = Setting::get('mail_host', '');
        $this->mail_port = Setting::get('mail_port', '587');
        $this->mail_username = Setting::get('mail_username', '');
        $this->mail_password = Setting::get('mail_password', '');
        $this->mail_encryption = Setting::get('mail_encryption', 'tls');
        $this->mail_from_address = Setting::get('mail_from_address', '');
        $this->mail_from_name = Setting::get('mail_from_name', '');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function saveGeneral()
    {
        $this->validate([
            'agency_name' => 'required|string|max:100',
            'agency_phone' => 'required|string|max:30',
            'agency_email' => 'required|email|max:100',
            'agency_address' => 'required|string|max:255',
            'commission_trigger' => 'required|in:vente,encaissement',
            'default_apporteur_rate' => 'required|numeric|min:0|max:100',
            'default_agent_rate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('agency_name', $this->agency_name);
        Setting::set('agency_phone', $this->agency_phone);
        Setting::set('agency_email', $this->agency_email);
        Setting::set('agency_address', $this->agency_address);
        Setting::set('commission_trigger', $this->commission_trigger);
        Setting::set('default_apporteur_rate', (string)$this->default_apporteur_rate);
        Setting::set('default_agent_rate', (string)$this->default_agent_rate);

        // Update tenant name in landlord database dynamically
        $tenant = tenant();
        if ($tenant) {
            $tenant->update(['name' => $this->agency_name]);
        }

        session()->flash('message', 'Paramètres généraux mis à jour avec succès.');
    }

    public function saveSMTP()
    {
        $this->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        Setting::set('mail_host', $this->mail_host);
        Setting::set('mail_port', $this->mail_port);
        Setting::set('mail_username', $this->mail_username ?? '');
        Setting::set('mail_password', $this->mail_password ?? '');
        Setting::set('mail_encryption', $this->mail_encryption);
        Setting::set('mail_from_address', $this->mail_from_address);
        Setting::set('mail_from_name', $this->mail_from_name);

        session()->flash('message', 'Configuration SMTP enregistrée.');
    }

    public function saveWhiteLabel()
    {
        $this->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|mimes:png,jpg,jpeg,ico|max:512',
            'couleur_primaire' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'couleur_secondaire' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $tenant = tenant();
        $updates = [];

        if ($this->logo) {
            $logoPath = $this->logo->store('white-label', 'public');
            $updates['logo_path'] = $logoPath;
        }

        if ($this->favicon) {
            $faviconPath = $this->favicon->store('white-label', 'public');
            $updates['favicon_path'] = $faviconPath;
        }

        $updates['couleur_primaire'] = $this->couleur_primaire ?: null;
        $updates['couleur_secondaire'] = $this->couleur_secondaire ?: null;

        $tenant->update($updates);

        session()->flash('message', 'Paramètres de personnalisation (White-Label) enregistrés.');
    }

    public function saveAccessControl()
    {
        Setting::set('enabled_pages', json_encode($this->enabled_pages));
        Setting::set('enabled_roles', json_encode($this->enabled_roles));

        session()->flash('message', 'Les droits d\'accès (Pages & Rôles) ont été enregistrés avec succès.');
    }

    public function render()
    {
        // Load roles
        $roles = Role::whereIn('name', [
            'agency-admin',
            'responsable-succursale',
            'agent-commercial',
            'comptable',
            'consultation'
        ])->get();

        // Get subscription metadata
        $subscription = [
            'plan' => Setting::get('subscription_plan', 'Premium Growth Suite'),
            'status' => Setting::get('subscription_status', 'Actif'),
            'price' => Setting::get('subscription_price', '1,200 DH / mois'),
            'expires_at' => Setting::get('subscription_expires_at', '2027-01-01'),
            'max_users' => Setting::get('subscription_max_users', '20 Utilisateurs'),
        ];

        return view('livewire.admin.gestion-agence', compact('roles', 'subscription'))
            ->layout('layouts.app');
    }
}
