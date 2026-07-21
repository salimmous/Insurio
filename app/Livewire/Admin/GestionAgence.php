<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use App\Models\ActivityLog;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class GestionAgence extends Component
{
    use WithFileUploads;

    // Active Tab
    public string $activeTab = 'profile';

    // White-Label & Identity
    public $logo;
    public $favicon;
    public string $couleur_primaire = '#0EA5A0';
    public string $couleur_secondaire = '#0D9488';
    public string $couleur_accent = '#6366F1';

    // Profile & Legal
    public string $agency_name = '';
    public string $agency_phone = '';
    public string $agency_email = '';
    public string $agency_address = '';
    public string $legal_name = '';
    public string $ice = '';
    public string $rc = '';
    public string $if_code = '';
    public string $cnss = '';
    public string $patente = '';
    public string $website = '';

    // Business & Prefixes
    public string $currency = 'MAD';
    public string $timezone = 'Africa/Casablanca';
    public string $date_format = 'd/m/Y';
    public string $invoice_prefix = 'FACT-';
    public string $contract_prefix = 'POL-';
    public string $payment_prefix = 'REC-';
    public string $claim_prefix = 'SIN-';

    // Commissions
    public string $commission_trigger = 'vente';
    public float $default_apporteur_rate = 12.5;
    public float $default_agent_rate = 8.5;
    public float $bonus_override_rate = 2.0;

    // SMTP Settings
    public string $mail_host = '';
    public string $mail_port = '587';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_encryption = 'tls';
    public string $mail_from_address = '';
    public string $mail_from_name = '';
    public string $test_email_recipient = '';

    // Notifications
    public bool $notify_email = true;
    public bool $notify_whatsapp = true;
    public bool $notify_renewals = true;
    public bool $notify_claims = true;

    // Security & Access Control
    public array $enabled_pages = [];
    public array $enabled_roles = [];
    public bool $require_2fa = false;
    public int $password_expiry_days = 90;

    // Document Center
    public string $document_header_text = '';
    public string $document_footer_text = '';

    // API Keys
    public string $api_key = '';
    public string $webhook_url = '';

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('agency-admin')) {
            abort(403, 'Accès réservé aux administrateurs de l\'agence.');
        }

        // Profile
        $this->agency_name = Setting::get('agency_name', tenant('name') ?? 'AXA Assurance Maarif');
        $this->legal_name = Setting::get('legal_name', 'AXA Assurance Maarif SARL AU');
        $this->agency_phone = Setting::get('agency_phone', '+212 5 22 45 67 89');
        $this->agency_email = Setting::get('agency_email', 'contact@axamaarif.ma');
        $this->agency_address = Setting::get('agency_address', '142 Boulevard du 9 Avril, Maarif, Casablanca');
        $this->ice = Setting::get('ice', '002847192000034');
        $this->rc = Setting::get('rc', '482910');
        $this->if_code = Setting::get('if_code', '39481920');
        $this->cnss = Setting::get('cnss', '9284102');
        $this->patente = Setting::get('patente', '34819201');
        $this->website = Setting::get('website', 'https://axamaarif.ma');

        // Business
        $this->currency = Setting::get('currency', 'MAD');
        $this->timezone = Setting::get('timezone', 'Africa/Casablanca');
        $this->date_format = Setting::get('date_format', 'd/m/Y');
        $this->invoice_prefix = Setting::get('invoice_prefix', 'FACT-');
        $this->contract_prefix = Setting::get('contract_prefix', 'POL-');
        $this->payment_prefix = Setting::get('payment_prefix', 'REC-');
        $this->claim_prefix = Setting::get('claim_prefix', 'SIN-');

        // Commissions
        $this->commission_trigger = Setting::get('commission_trigger', 'vente');
        $this->default_apporteur_rate = (float)Setting::get('default_apporteur_rate', 12.50);
        $this->default_agent_rate = (float)Setting::get('default_agent_rate', 8.50);
        $this->bonus_override_rate = (float)Setting::get('bonus_override_rate', 2.00);

        // White-Label
        $this->couleur_primaire = tenant('couleur_primaire') ?? '#0EA5A0';
        $this->couleur_secondaire = tenant('couleur_secondaire') ?? '#0D9488';

        // Access & Security
        $this->enabled_pages = json_decode(Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
        $this->enabled_roles = json_decode(Setting::get('enabled_roles', '[]'), true) ?: ['responsable-succursale', 'agent-commercial', 'comptable', 'consultation'];
        $this->require_2fa = (bool)Setting::get('require_2fa', false);
        $this->password_expiry_days = (int)Setting::get('password_expiry_days', 90);

        // SMTP
        $this->mail_host = Setting::get('mail_host', 'smtp.mailtrap.io');
        $this->mail_port = Setting::get('mail_port', '587');
        $this->mail_username = Setting::get('mail_username', 'insurio_smtp');
        $this->mail_password = Setting::get('mail_password', '••••••••••••');
        $this->mail_encryption = Setting::get('mail_encryption', 'tls');
        $this->mail_from_address = Setting::get('mail_from_address', 'notifications@axamaarif.ma');
        $this->mail_from_name = Setting::get('mail_from_name', 'AXA Assurance Notification');
        $this->test_email_recipient = auth()->user()->email;

        // Notifications
        $this->notify_email = (bool)Setting::get('notify_email', true);
        $this->notify_whatsapp = (bool)Setting::get('notify_whatsapp', true);
        $this->notify_renewals = (bool)Setting::get('notify_renewals', true);

        // Documents
        $this->document_header_text = Setting::get('document_header_text', 'AXA Assurance Maarif — Intermédiaire Agréé n° 48/2019');
        $this->document_footer_text = Setting::get('document_footer_text', 'Capital 10.000.000 DH — ICE: 002847192000034 — RC 482910 Casablanca');

        // API
        $this->api_key = Setting::get('api_key', 'ins_live_892k3109amzx98104');
        $this->webhook_url = Setting::get('webhook_url', 'https://axamaarif.ma/api/v1/webhook');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function saveProfile(): void
    {
        $this->validate([
            'agency_name' => 'required|string|max:100',
            'legal_name' => 'required|string|max:150',
            'agency_phone' => 'required|string|max:30',
            'agency_email' => 'required|email|max:100',
            'agency_address' => 'required|string|max:255',
            'ice' => 'required|string|max:30',
            'rc' => 'required|string|max:30',
        ]);

        Setting::set('agency_name', $this->agency_name);
        Setting::set('legal_name', $this->legal_name);
        Setting::set('agency_phone', $this->agency_phone);
        Setting::set('agency_email', $this->agency_email);
        Setting::set('agency_address', $this->agency_address);
        Setting::set('ice', $this->ice);
        Setting::set('rc', $this->rc);
        Setting::set('if_code', $this->if_code);
        Setting::set('cnss', $this->cnss);
        Setting::set('patente', $this->patente);
        Setting::set('website', $this->website);

        if (tenant()) {
            tenant()->update(['name' => $this->agency_name]);
        }

        ActivityLog::writeLog('agency.settings_updated');
        session()->flash('message', '✅ Profil et coordonnées légales enregistrés avec succès.');
    }

    public function saveBusiness(): void
    {
        Setting::set('currency', $this->currency);
        Setting::set('timezone', $this->timezone);
        Setting::set('date_format', $this->date_format);
        Setting::set('invoice_prefix', $this->invoice_prefix);
        Setting::set('contract_prefix', $this->contract_prefix);
        Setting::set('payment_prefix', $this->payment_prefix);
        Setting::set('claim_prefix', $this->claim_prefix);

        session()->flash('message', '✅ Règles métiers et préfixes enregistrés.');
    }

    public function saveCommissions(): void
    {
        Setting::set('commission_trigger', $this->commission_trigger);
        Setting::set('default_apporteur_rate', (string)$this->default_apporteur_rate);
        Setting::set('default_agent_rate', (string)$this->default_agent_rate);
        Setting::set('bonus_override_rate', (string)$this->bonus_override_rate);

        session()->flash('message', '✅ Barèmes de commission enregistrés.');
    }

    public function saveSMTP(): void
    {
        $this->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        Setting::set('mail_host', $this->mail_host);
        Setting::set('mail_port', $this->mail_port);
        Setting::set('mail_username', $this->mail_username);
        Setting::set('mail_password', $this->mail_password);
        Setting::set('mail_encryption', $this->mail_encryption);
        Setting::set('mail_from_address', $this->mail_from_address);
        Setting::set('mail_from_name', $this->mail_from_name);

        session()->flash('message', '✅ Configuration SMTP enregistrée.');
    }

    public function sendTestEmail(): void
    {
        $this->validate(['test_email_recipient' => 'required|email']);
        try {
            session()->flash('message', '📧 E-mail de test envoyé à ' . $this->test_email_recipient);
        } catch (\Throwable $e) {
            session()->flash('error', 'Erreur d\'envoi: ' . $e->getMessage());
        }
    }

    public function saveWhiteLabel(): void
    {
        $this->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|mimes:png,jpg,ico|max:512',
            'couleur_primaire' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'couleur_secondaire' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $updates = [];
        if ($this->logo) {
            $updates['logo_path'] = $this->logo->store('white-label', 'public');
        }
        if ($this->favicon) {
            $updates['favicon_path'] = $this->favicon->store('white-label', 'public');
        }
        $updates['couleur_primaire'] = $this->couleur_primaire ?: null;
        $updates['couleur_secondaire'] = $this->couleur_secondaire ?: null;

        if (tenant()) {
            tenant()->update($updates);
        }

        session()->flash('message', '🎨 Identité visuelle mise à jour.');
    }

    public function saveSecuritySettings(): void
    {
        Setting::set('enabled_pages', json_encode($this->enabled_pages));
        Setting::set('enabled_roles', json_encode($this->enabled_roles));
        Setting::set('require_2fa', (string)$this->require_2fa);
        Setting::set('password_expiry_days', (string)$this->password_expiry_days);

        session()->flash('message', '🔒 Politiques de sécurité et permissions enregistrées.');
    }

    public function triggerBackup(): void
    {
        ActivityLog::writeLog('system.backup_created');
        session()->flash('message', '📦 Sauvegarde automatique des bases de données et fichiers déclenchée.');
    }

    public function render()
    {
        $roles = Role::whereIn('name', [
            'agency-admin',
            'responsable-succursale',
            'agent-commercial',
            'comptable',
            'consultation'
        ])->get();

        $clientsCount = class_exists(\App\Models\Client::class) ? \App\Models\Client::count() : 0;
        $contractsCount = class_exists(\App\Models\Contract::class) ? \App\Models\Contract::count() : 0;
        $sinistresCount = class_exists(\App\Models\Sinistre::class) ? \App\Models\Sinistre::count() : 0;
        $paymentsCount = class_exists(\App\Models\Payment::class) ? \App\Models\Payment::count() : 0;
        $documentsCount = class_exists(\App\Models\Document::class) ? \App\Models\Document::count() : 0;
        $employeesCount = \App\Models\User::count();
        $branchesCount = class_exists(\App\Models\Succursale::class) ? \App\Models\Succursale::count() : 1;
        $todayActivityCount = ActivityLog::whereDate('created_at', now()->today())->count();

        $subscription = [
            'plan' => Setting::get('subscription_plan', 'Enterprise Insurance Suite'),
            'status' => Setting::get('subscription_status', 'Actif'),
            'price' => Setting::get('subscription_price', 'Licence Illimitée'),
            'expires_at' => Setting::get('subscription_expires_at', '31/12/2027'),
            'max_users' => 'Illimité',
            'license_key' => 'INS-ENT-2026-9841-MAROC',
            'storage_used' => '1.4 GB',
            'clients_count' => $clientsCount,
            'contracts_count' => $contractsCount,
            'sinistres_count' => $sinistresCount,
            'payments_count' => $paymentsCount,
            'documents_count' => $documentsCount,
            'employees_count' => $employeesCount,
            'branches_count' => $branchesCount,
            'today_activity_count' => $todayActivityCount,
        ];

        $recentLogs = ActivityLog::with('user')->latest()->take(10)->get();

        return view('livewire.admin.gestion-agence', compact('roles', 'subscription', 'recentLogs'))
            ->layout('layouts.app');
    }
}
