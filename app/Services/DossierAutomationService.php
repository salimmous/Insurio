<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\DossierFollowUp;
use App\Models\Employe;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Communication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DossierAutomationService
{
    public static function handleDossierCreation(Dossier $dossier): void
    {
        // 1. Assign employee if not already assigned
        if (!$dossier->assigned_employee_id) {
            // Find an active employee in the same succursale
            $employee = Employe::where('succursale_id', $dossier->succursale_id)
                ->where('statut', 'actif')
                ->first();

            if ($employee) {
                $dossier->assigned_employee_id = $employee->id;
            }
        }

        // 2. Set manager if not already set (Siège/admin manager or succursale responsable)
        if (!$dossier->manager_id) {
            $manager = User::role('agency-admin')->first();
            if ($manager) {
                $dossier->manager_id = $manager->id;
            }
        }

        $dossier->save();

        // 3. Create initial activity in ActivityLog
        ActivityLog::writeLog("Création du dossier {$dossier->dossier_number} (Type: {$dossier->type})", $dossier);

        // 4. Create timeline event (as a system note communication)
        Communication::create([
            'client_id' => $dossier->client_id,
            'dossier_id' => $dossier->id,
            'type' => 'note',
            'message' => "Système : Dossier {$dossier->dossier_number} ouvert. Statut : {$dossier->status}.",
            'user_id' => auth()->id() ?? $dossier->manager_id,
            'created_at' => now(),
        ]);

        // 5. Create initial follow-up reminder
        DossierFollowUp::create([
            'dossier_id' => $dossier->id,
            'date' => Carbon::now()->addDays(2)->toDateString(),
            'employee_id' => $dossier->assigned_employee_id,
            'reminder_at' => Carbon::now()->addDays(2)->setHour(9)->setMinute(0)->toDateTimeString(),
            'priority' => 'high',
            'notes' => 'Premier suivi : Contacter le client pour faire le point sur le dossier.',
            'completed' => false,
        ]);

        // 6. Mock Email / WhatsApp Notification
        Log::info("Notification : Dossier {$dossier->dossier_number} créé. E-mail envoyé au gestionnaire et WhatsApp envoyé au client.");
    }
}
