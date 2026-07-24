<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employe;
use App\Models\Succursale;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\Audit\SecurityAuditService;
use App\Mail\EmployeeInvitationMail;
use App\Mail\EmployeePasswordResetMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GestionEmployes extends Component
{
    public $employes = [];
    public $succursales = [];

    // Form fields
    public $employeId;
    public $nom;
    public $prenom;
    public $cin;
    public $telephone;
    public $email;
    public $succursale_id;
    public $poste = 'Agent commercial';
    public $taux_commission_defaut = 0.00;
    public $statut = 'invitation_pending';

    public $isEditing = false;
    public $showModal = false;
    public $showDeleteModal = false;
    public $showProfileModal = false;
    public $viewingEmploye = null;

    public $showActivationPackageModal = false;
    public $activationEmploye = null;
    public $activationUser = null;
    public $activationTempPassword = '';
    public $activationLink = '';

    public $deletingEmployeId = null;
    public $deleteError = '';

    public $searchQuery = '';
    public $filterStatut = '';

    protected function rules()
    {
        $rules = [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'cin' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:50',
            'succursale_id' => 'required|exists:succursales,id',
            'poste' => 'required|string|in:Administrateur,Responsable succursale,Agent commercial,Comptable,Consultation',
            'taux_commission_defaut' => 'required|numeric|min:0|max:100',
        ];

        if ($this->isEditing) {
            $rules['email'] = 'required|email|max:150|unique:users,email,' . optional(Employe::find($this->employeId))->user_id;
        } else {
            $rules['email'] = 'required|email|max:150|unique:users,email';
        }

        return $rules;
    }

    public function mount()
    {
        if (!auth()->user() || (!auth()->user()->hasRole('agency-admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Accès non autorisé.');
        }
        $this->loadData();
    }

    public function loadData()
    {
        $query = Employe::with(['succursale', 'user']);

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('prenom', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('cin', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        $this->employes = $query->get();
        $this->succursales = Succursale::all();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->loadData();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $employe = Employe::findOrFail($id);
        $this->employeId = $employe->id;
        $this->nom = $employe->nom;
        $this->prenom = $employe->prenom;
        $this->cin = $employe->cin;
        $this->telephone = $employe->telephone;
        $this->email = $employe->email;
        $this->succursale_id = $employe->succursale_id;
        $this->poste = $employe->poste;
        $this->taux_commission_defaut = $employe->taux_commission_defaut;
        $this->statut = $employe->statut;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Save employee profile. Administrator NEVER enters password.
     */
    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $employe = Employe::findOrFail($this->employeId);
            $employe->update([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'cin' => $this->cin,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'succursale_id' => $this->succursale_id,
                'poste' => $this->poste,
                'taux_commission_defaut' => $this->taux_commission_defaut,
            ]);

            if ($employe->user) {
                $employe->user->update([
                    'name' => "{$this->prenom} {$this->nom}",
                    'email' => $this->email,
                    'branch_id' => $this->succursale_id,
                ]);
                $this->syncUserRole($employe->user, $this->poste);
            }

            ActivityLog::writeLog('employee.profile_updated', $employe, null, [
                'updated_by' => auth()->user()->name,
                'ip' => request()->ip(),
            ]);

            session()->flash('message', 'Profil employé mis à jour avec succès.');
        } else {
            // STEP 2: Automatic Creation & 24h Token Generation
            $token = Str::random(64);
            $expiresAt = now()->addHours(24);
            $matricule = 'EMP-' . strtoupper(Str::random(5));
            $tempPassword = 'Ins#' . rand(1000, 9999) . 'P';

            $user = User::create([
                'name' => "{$this->prenom} {$this->nom}",
                'email' => $this->email,
                'password' => Hash::make($tempPassword),
                'branch_id' => $this->succursale_id,
                'status' => 'pending_activation',
                'first_login' => true,
                'activation_token' => $token,
                'activation_token_expires_at' => $expiresAt,
                'invitation_token' => $token,
                'invitation_expires_at' => $expiresAt,
                'invitation_sent_at' => now(),
            ]);

            $this->syncUserRole($user, $this->poste);

            $employe = Employe::create([
                'user_id' => $user->id,
                'matricule_employe' => $matricule,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'cin' => $this->cin,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'succursale_id' => $this->succursale_id,
                'poste' => $this->poste,
                'taux_commission_defaut' => $this->taux_commission_defaut,
                'date_embauche' => now(),
                'statut' => 'pending_activation',
            ]);

            // STEP 3: Save Created Temporary Password to session for Activation Package
            session(['created_temp_password_' . $employe->id => $tempPassword]);

            // STEP 4: Send Professional Invitation Email
            $activationUrl = route('activation.token', ['token' => $token]);
            try {
                Mail::to($this->email)->send(new EmployeeInvitationMail($user, $employe, $activationUrl));
                session()->flash('message', 'Employé créé et lien d\'activation envoyé par email (Valable 24h).');
            } catch (\Exception $e) {
                session()->flash('message', 'Employé créé. Erreur SMTP lors de l\'envoi de l\'email: ' . $e->getMessage());
            }

            ActivityLog::writeLog('employee.invitation_sent', $employe, null, [
                'sent_by' => auth()->user()->name,
                'email' => $this->email,
                'ip' => request()->ip(),
            ]);

            SecurityAuditService::log(SecurityAuditService::EVENT_ACCOUNT_CREATED, 'success', $user, "Création du compte employé pour {$user->name}");
            SecurityAuditService::log(SecurityAuditService::EVENT_PASSWORD_TEMP_GENERATED, 'success', $user, "Génération d'un mot de passe temporaire à la création");
            SecurityAuditService::log(SecurityAuditService::EVENT_ACTIVATION_LINK_GENERATED, 'success', $user, "Génération du lien d'activation (24h)");

            // Auto-open Activation Package Modal for Super Admin
            $this->openActivationPackage($employe->id);
        }

        $this->showModal = false;
        $this->loadData();
    }

    /**
     * Open Activation Package Modal
     */
    public function openActivationPackage($employeId)
    {
        $this->activationEmploye = Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $this->activationUser = $this->activationEmploye->user;

        if (!$this->activationUser) {
            session()->flash('error', 'Aucun utilisateur associé à cet employé.');
            return;
        }

        $token = $this->activationUser->activation_token ?: $this->activationUser->invitation_token;
        if (!$token || ($this->activationUser->activation_token_expires_at && $this->activationUser->activation_token_expires_at->isPast())) {
            $token = Str::random(64);
            $expiresAt = now()->addHours(24);
            $this->activationUser->update([
                'activation_token' => $token,
                'activation_token_expires_at' => $expiresAt,
                'invitation_token' => $token,
                'invitation_expires_at' => $expiresAt,
            ]);
            SecurityAuditService::log(SecurityAuditService::EVENT_ACTIVATION_LINK_REGENERATED, 'success', $this->activationUser, "Régénération automatique de token expiré à l'ouverture du pack");
        }

        $this->activationTempPassword = session('created_temp_password_' . $employeId) ?: ('Ins#' . substr(md5($token), 0, 4) . 'P!');
        $this->activationLink = route('activation.token', ['token' => $token]);
        $this->showActivationPackageModal = true;
    }

    /**
     * Resend Activation Email / Link (24h Token)
     */
    public function resendInvitation($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);

        if (!$employe->user) {
            session()->flash('error', 'Aucun compte utilisateur associé à cet employé.');
            return;
        }

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        $employe->user->update([
            'status' => 'pending_activation',
            'activation_token' => $token,
            'activation_token_expires_at' => $expiresAt,
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
            'invitation_sent_at' => now(),
        ]);

        $employe->update(['statut' => 'pending_activation']);

        $activationUrl = route('activation.token', ['token' => $token]);
        try {
            Mail::to($employe->email)->send(new EmployeeInvitationMail($employe->user, $employe, $activationUrl));
            session()->flash('message', "Lien d'activation réenvoyé avec succès à {$employe->email} (Valable 24h).");
        } catch (\Exception $e) {
            session()->flash('error', "Erreur d'envoi SMTP: " . $e->getMessage());
        }

        ActivityLog::writeLog('employee.invitation_resent', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

        SecurityAuditService::log(SecurityAuditService::EVENT_ACTIVATION_LINK_REGENERATED, 'success', $employe->user, "Régénération du lien d'activation (24h) par l'administrateur");

        $this->loadData();
    }

    /**
     * Password Reset & Force Re-activation Trigger by Admin
     */
    public function resetPassword($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);

        if (!$employe->user) {
            session()->flash('error', 'Aucun compte utilisateur associé à cet employé.');
            return;
        }

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);
        $tempPassword = 'Ins#' . rand(1000, 9999) . 'P';

        $employe->user->update([
            'password' => Hash::make($tempPassword),
            'first_login' => true,
            'activated_at' => null,
            'password_changed_at' => null,
            'activation_token' => $token,
            'activation_token_expires_at' => $expiresAt,
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
            'status' => 'pending_activation',
        ]);

        $employe->update(['statut' => 'pending_activation']);

        $resetUrl = route('activation.token', ['token' => $token]);
        try {
            Mail::to($employe->email)->send(new EmployeePasswordResetMail($employe->user, $employe, $resetUrl));
            session()->flash('message', "Réinitialisation et lien d'activation (24h) envoyés à {$employe->email}.");
        } catch (\Exception $e) {
            session()->flash('error', "Erreur d'envoi SMTP: " . $e->getMessage());
        }

        ActivityLog::writeLog('employee.password_reset_requested', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

        SecurityAuditService::log(SecurityAuditService::EVENT_PASSWORD_RESET, 'success', $employe->user, "Réinitialisation du mot de passe initiée par l'administrateur");
        SecurityAuditService::log(SecurityAuditService::EVENT_PASSWORD_TEMP_GENERATED, 'success', $employe->user, "Nouveau mot de passe temporaire généré");
        SecurityAuditService::log(SecurityAuditService::EVENT_ACTIVATION_LINK_REGENERATED, 'success', $employe->user, "Lien d'activation (24h) régénéré après réinitialisation");

        $this->loadData();
    }

    /**
     * Reset 2FA configuration
     */
    public function resetTwoFactor($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);
        if ($employe->user) {
            $employe->user->update([
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
                'two_factor_recovery_codes' => null,
            ]);
            SecurityAuditService::log(SecurityAuditService::EVENT_2FA_DISABLED, 'warning', $employe->user, "Désactivation & réinitialisation 2FA par l'administrateur");
        }

        ActivityLog::writeLog('employee.2fa_reset', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

        session()->flash('message', "L'authentification 2FA pour {$employe->nom_complet} a été réinitialisée.");
        $this->loadData();
    }

    /**
     * Suspend / Reactivate Employee
     */
    public function toggleStatut($employeId, $newStatut)
    {
        $employe = Employe::with('user')->findOrFail($employeId);
        $employe->update(['statut' => $newStatut]);
        if ($employe->user) {
            $employe->user->update(['status' => $newStatut]);
            $evt = $newStatut === 'suspended' ? SecurityAuditService::EVENT_ACCOUNT_SUSPENDED : SecurityAuditService::EVENT_ACCOUNT_REACTIVATED;
            SecurityAuditService::log($evt, 'warning', $employe->user, "Modification du statut du compte: {$newStatut}");
        }

        ActivityLog::writeLog("employee.{$newStatut}", $employe, null, [
            'admin' => auth()->user()->name,
            'new_status' => $newStatut,
            'ip' => request()->ip(),
        ]);

        session()->flash('message', "Statut de {$employe->nom_complet} changé en {$newStatut}.");
        $this->loadData();
    }

    /**
     * Confirm Secure Deletion Check
     */
    public function confirmDelete($id)
    {
        $this->deletingEmployeId = $id;
        $this->deleteError = '';
        $employe = Employe::findOrFail($id);

        $reason = '';
        if (!$employe->canBeDeleted($reason)) {
            $this->deleteError = "Cet employé possède des enregistrements commerciaux ou comptables liés et ne peut pas être supprimé définitivement. Veuillez suspendre l'employé à la place.\n\nRaison: {$reason}";
        }

        $this->showDeleteModal = true;
    }

    /**
     * Delete Employee if Guardrails pass
     */
    public function deleteEmployee()
    {
        if (!$this->deletingEmployeId) return;

        $employe = Employe::with('user')->findOrFail($this->deletingEmployeId);

        $reason = '';
        if (!$employe->canBeDeleted($reason)) {
            $this->deleteError = "Suppression impossible: {$reason}";
            return;
        }

        $nom = $employe->nom_complet;
        if ($employe->user) {
            $employe->user->delete();
        }
        $employe->delete();

        ActivityLog::writeLog('employee.deleted', null, null, [
            'admin' => auth()->user()->name,
            'deleted_employee_name' => $nom,
            'ip' => request()->ip(),
        ]);

        $this->showDeleteModal = false;
        $this->deletingEmployeId = null;
        session()->flash('message', "Employé {$nom} supprimé définitivement.");
        $this->loadData();
    }

    /**
     * View Employee Profile Modal
     */
    public function viewProfile($id)
    {
        $this->viewingEmploye = Employe::with(['succursale', 'user'])->findOrFail($id);
        $this->showProfileModal = true;
    }

    /**
     * Send Employee Profile Badge via Email using Laravel Mail
     */
    public function sendByEmail($id)
    {
        $employe = Employe::with('user')->findOrFail($id);
        
        try {
            Mail::to($employe->email)->send(new EmployeeWelcomeMail($employe->user, $employe));
            session()->flash('message', "La fiche de profil employé a été envoyée par email à {$employe->email}.");
            ActivityLog::writeLog('employee.profile_emailed', $employe, null, [
                'admin' => auth()->user()->name,
                'recipient' => $employe->email,
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            session()->flash('error', "Échec de l'envoi de l'email : " . $e->getMessage());
        }
    }

    /**
     * Share Employee Profile Details via WhatsApp (wa.me)
     */
    public function sendWhatsApp($id)
    {
        $employe = Employe::with(['succursale', 'user'])->findOrFail($id);
        
        $phone = preg_replace('/[^0-9]/', '', $employe->telephone ?: '');
        if (str_starts_with($phone, '0')) {
            $phone = '212' . substr($phone, 1);
        }

        $agencyName = \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        $tempPassword = $employe->invitation_token ? substr(md5($employe->invitation_token), 0, 4) . '#92Lm' : 'A7xP#92Lm';
        
        $text = "🔑 *KIT D'ACTIVATION EMPLOYÉ — {$agencyName}*\n\n";
        $text .= "Bonjour *{$employe->nom_complet}*,\n\n";
        $text .= "Votre compte d'entreprise a été créé. Voici vos accès d'accréditation :\n";
        $text .= "• *Matricule* : {$employe->matricule_employe}\n";
        $text .= "• *Email Pro* : {$employe->email}\n";
        $text .= "• *Mot de passe temporaire* : {$tempPassword}\n\n";
        $text .= "📌 *Procédure d'activation de votre compte :*\n";
        $text .= "1. Se connecter avec votre Email & Mot de Passe temporaire.\n";
        $text .= "2. Scanner le QR Code 2FA avec Google Authenticator.\n";
        $text .= "3. Saisir le code temporaire à 6 chiffres.\n";
        $text .= "4. Définir votre mot de passe personnel sécurisé.\n\n";
        $text .= "🌐 *Espace Agence :* " . route('login');

        $url = "https://wa.me/{$phone}?text=" . urlencode($text);

        ActivityLog::writeLog('employee.whatsapp_shared', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

        $this->dispatch('open-url', ['url' => $url]);
        session()->flash('message', "Lien WhatsApp généré pour {$employe->nom_complet}.");
    }

    /**
     * Test SMTP Connection
     */
    public function testSmtp()
    {
        try {
            Mail::raw("Test de connexion SMTP Insurio réussi à " . date('d/m/Y H:i:s'), function ($message) {
                $message->to(auth()->user()->email)
                        ->subject("✅ Test de Connexion SMTP Insurio");
            });
            session()->flash('message', "Test SMTP réussi ! Email de test envoyé avec succès à " . auth()->user()->email);
        } catch (\Exception $e) {
            session()->flash('error', "Échec du test SMTP : " . $e->getMessage());
        }
    }

    private function syncUserRole(User $user, string $poste)
    {
        $roleMap = [
            'Administrateur' => 'agency-admin',
            'Responsable succursale' => 'responsable-succursale',
            'Agent commercial' => 'agent-commercial',
            'Comptable' => 'comptable',
            'Consultation' => 'consultation',
        ];

        $roleName = $roleMap[$poste] ?? 'consultation';
        $user->syncRoles([$roleName]);
    }

    public function resetForm()
    {
        $this->employeId = null;
        $this->nom = '';
        $this->prenom = '';
        $this->cin = '';
        $this->telephone = '';
        $this->email = '';
        $this->succursale_id = optional($this->succursales->first())->id;
        $this->poste = 'Agent commercial';
        $this->taux_commission_defaut = 0.00;
        $this->statut = 'invitation_pending';
    }

    public function render()
    {
        return view('livewire.admin.gestion-employes')
            ->layout('layouts.app');
    }
}
