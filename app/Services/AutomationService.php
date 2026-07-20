<?php

namespace App\Services;

use App\Models\AutomationRule;
use App\Models\Contract;
use App\Models\Communication;
use App\Models\Task;

class AutomationService
{
    public static function handleEvent(string $event, $data)
    {
        $rules = AutomationRule::where('event', $event)->where('is_active', true)->get();

        foreach ($rules as $rule) {
            if (self::evaluateConditions($rule->conditions, $data)) {
                self::executeActions($rule->actions, $data);
            }
        }
    }

    private static function evaluateConditions(?array $conditions, $data): bool
    {
        if (empty($conditions)) {
            return true;
        }

        // Handle Contract Expiring event conditions
        if ($data instanceof Contract) {
            if (isset($conditions['days_before_expiry'])) {
                $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($data->end_date)->startOfDay(), false);
                if ((int)$daysLeft !== (int)$conditions['days_before_expiry']) {
                    return false;
                }
            }

            if (isset($conditions['insurance_type_id'])) {
                if ($data->insurance_type_id != $conditions['insurance_type_id']) {
                    return false;
                }
            }

            if (isset($conditions['premium_min'])) {
                if ($data->premium_amount < $conditions['premium_min']) {
                    return false;
                }
            }
        }

        return true;
    }

    private static function executeActions(?array $actions, $data)
    {
        if (empty($actions)) {
            return;
        }

        foreach ($actions as $action) {
            $type = $action['type'] ?? '';

            if ($type === 'whatsapp' && $data instanceof Contract) {
                // Log whatsapp interaction
                $clientName = $data->client ? ($data->client->first_name . ' ' . $data->client->last_name) : 'Client';
                $phone = $data->client->phone ?? $data->client->whatsapp_number ?? '';
                
                $msg = "Bonjour {$clientName}, votre contrat d'assurance N° {$data->contract_number} expire le {$data->end_date->format('d/m/Y')}. Veuillez nous contacter pour son renouvellement.";
                
                Communication::create([
                    'client_id' => $data->client_id,
                    'type' => 'whatsapp',
                    'message' => "[AUTOMATIQUE] WhatsApp envoyé à {$phone}: {$msg}",
                    'user_id' => null, // system automated
                ]);
            }

            if ($type === 'email' && $data instanceof Contract) {
                // Log email interaction
                $clientName = $data->client ? ($data->client->first_name . ' ' . $data->client->last_name) : 'Client';
                $email = $data->client->email ?? '';
                
                $msg = "Sujet: Échéance de votre contrat N° {$data->contract_number}\n\nBonjour {$clientName},\nVotre contrat arrive à échéance.";
                
                Communication::create([
                    'client_id' => $data->client_id,
                    'type' => 'email',
                    'message' => "[AUTOMATIQUE] Email envoyé à {$email}: {$msg}",
                    'user_id' => null, // system automated
                ]);
            }

            if ($type === 'task' && $data instanceof Contract) {
                // Create a task for renewal relance
                Task::create([
                    'title' => "Relance Renouvellement: Contrat #{$data->contract_number}",
                    'description' => "Le contrat de " . ($data->client ? ($data->client->first_name . ' ' . $data->client->last_name) : 'Client') . " expire le " . $data->end_date->format('d/m/Y') . ". Contacter le client.",
                    'assigned_user_id' => $data->employe ? $data->employe->user_id : null,
                    'client_id' => $data->client_id,
                    'contract_id' => $data->id,
                    'priority' => 'high',
                    'status' => 'todo',
                    'due_date' => now()->toDateString(),
                ]);
            }
        }
    }
}
