<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employe;
use App\Models\Succursale;
use App\Models\User;
use App\Models\ActivityLog;
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
            // STEP 2: Automatic Creation & Token Generation
            $token = Str::random(64);
            $expiresAt = now()->addHours(48);
            $matricule = 'EMP-' . strtoupper(Str::random(5));

            $user = User::create([
                'name' => "{$this->prenom} {$this->nom}",
                'email' => $this->email,
                'password' => Hash::make(Str::random(32)),
                'branch_id' => $this->succursale_id,
                'status' => 'invitation_sent',
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
                'statut' => 'invitation_sent',
            ]);

            // STEP 3: Send Professional Invitation Email
            $activationUrl = route('employee.activate', ['token' => $token]);
            try {
                Mail::to($this->email)->send(new EmployeeInvitationMail($user, $employe, $activationUrl));
                session()->flash('message', 'Employé créé et invitation envoyée par email (Lien valable 48h).');
            } catch (\Exception $e) {
                session()->flash('message', 'Employé créé. Erreur SMTP lors de l\'envoi de l\'email: ' . $e->getMessage());
            }

            ActivityLog::writeLog('employee.invitation_sent', $employe, null, [
                'sent_by' => auth()->user()->name,
                'email' => $this->email,
                'ip' => request()->ip(),
            ]);
        }

        $this->showModal = false;
        $this->loadData();
    }

    /**
     * Resend Activation Email (48h Token)
     */
    public function resendInvitation($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);

        if (!$employe->user) {
            session()->flash('error', 'Aucun compte utilisateur associé à cet employé.');
            return;
        }

        $token = Str::random(64);
        $expiresAt = now()->addHours(48);

        $employe->user->update([
            'status' => 'invitation_sent',
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
            'invitation_sent_at' => now(),
        ]);

        $employe->update(['statut' => 'invitation_sent']);

        $activationUrl = route('employee.activate', ['token' => $token]);
        try {
            Mail::to($employe->email)->send(new EmployeeInvitationMail($employe->user, $employe, $activationUrl));
            session()->flash('message', "Invitation réenvoyée avec succès à {$employe->email} (Valable 48h).");
        } catch (\Exception $e) {
            session()->flash('error', "Erreur d'envoi SMTP: " . $e->getMessage());
        }

        ActivityLog::writeLog('employee.invitation_resent', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

        $this->loadData();
    }

    /**
     * Password Reset Trigger by Admin
     */
    public function resetPassword($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);

        if (!$employe->user) {
            session()->flash('error', 'Aucun compte utilisateur associé à cet employé.');
            return;
        }

        $token = Str::random(64);
        $expiresAt = now()->addHours(48);

        $employe->user->update([
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
        ]);

        $resetUrl = route('employee.activate', ['token' => $token]);
        try {
            Mail::to($employe->email)->send(new EmployeePasswordResetMail($employe->user, $employe, $resetUrl));
            session()->flash('message', "Email de réinitialisation de mot de passe envoyé à {$employe->email}. L'employé créera lui-même son nouveau mot de passe.");
        } catch (\Exception $e) {
            session()->flash('error', "Erreur d'envoi SMTP: " . $e->getMessage());
        }

        ActivityLog::writeLog('employee.password_reset_requested', $employe, null, [
            'admin' => auth()->user()->name,
            'ip' => request()->ip(),
        ]);

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
