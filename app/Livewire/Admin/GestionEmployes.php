<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employe;
use App\Models\Succursale;
use App\Models\User;
use App\Mail\EmployeeInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GestionEmployes extends Component
{
    public $employes = [];
    public $succursales = [];

    // Form fields (Step 1: Admin fills profile only, NO password fields)
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
     * Save employee profile (Step 1 & Step 2).
     * The admin NEVER enters a password.
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

            session()->flash('message', 'Profil employé mis à jour avec succès.');
        } else {
            // STEP 2: Automatic Creation & Invitation Token Generation
            $token = Str::random(64);
            $expiresAt = now()->addHours(48);
            $matricule = 'EMP-' . strtoupper(Str::random(5));

            // Create User account without password (un-usable random hash until activated)
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

            // Create Employe record
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

            // STEP 3: Send professional invitation email
            $activationUrl = route('employee.activate', ['token' => $token]);
            try {
                Mail::to($this->email)->send(new EmployeeInvitationMail($user, $employe, $activationUrl));
                session()->flash('message', 'Employé créé et invitation envoyée par email (Lien valable 48h).');
            } catch (\Exception $e) {
                session()->flash('message', 'Employé créé. Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
            }
        }

        $this->showModal = false;
        $this->loadData();
    }

    /**
     * Resend invitation link with fresh 48h token.
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
            session()->flash('error', "Erreur d'envoi de l'email: " . $e->getMessage());
        }

        $this->loadData();
    }

    /**
     * Revoke active invitation.
     */
    public function revokeInvitation($employeId)
    {
        $employe = Employe::with('user')->findOrFail($employeId);
        if ($employe->user) {
            $employe->user->update([
                'invitation_token' => null,
                'invitation_expires_at' => null,
                'status' => 'disabled',
            ]);
        }
        $employe->update(['statut' => 'disabled']);

        session()->flash('message', "L'invitation pour {$employe->nom_complet} a été révoquée.");
        $this->loadData();
    }

    /**
     * Reset 2FA configuration for employee.
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
            session()->flash('message', "L'authentification 2FA pour {$employe->nom_complet} a été réinitialisée. L'employé devra configurer 2FA lors de sa prochaine connexion.");
        }
        $this->loadData();
    }

    /**
     * Force password reset (generates new activation/reset link).
     */
    public function forcePasswordReset($employeId)
    {
        $this->resendInvitation($employeId);
    }

    /**
     * Change employee status (Active / Suspended / Disabled).
     */
    public function toggleStatut($employeId, $newStatut)
    {
        $employe = Employe::with('user')->findOrFail($employeId);
        $employe->update(['statut' => $newStatut]);
        if ($employe->user) {
            $employe->user->update(['status' => $newStatut]);
        }

        session()->flash('message', "Statut de {$employe->nom_complet} changé en {$newStatut}.");
        $this->loadData();
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
