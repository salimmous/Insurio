<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Dossier;
use App\Models\DossierAccidentDetail;
use App\Models\DossierInvolvedParty;
use App\Models\DossierExpertDetail;
use App\Models\DossierGarageDetail;
use App\Models\DossierChequeDetail;
use App\Models\DossierFollowUp;
use App\Models\Employe;
use App\Models\User;
use App\Models\Task;
use App\Models\Communication;
use App\Models\Document;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DossierWorkspace extends Component
{
    use WithFileUploads;

    public $dossierId;
    public $activeTab = 'overview'; // overview, timeline, communications, tasks, payments, parties, expert_garage, documents, ai_assistant

    // Inline edit / assignee / manager fields
    public $assigned_employee_id;
    public $manager_id;
    public $priority;
    public $urgency;
    public $status;

    // Sub-form properties
    // 1. Accident Form
    public $accident_date;
    public $accident_time;
    public $accident_city;
    public $accident_address;
    public $accident_gps;
    public $weather;
    public $road_condition;
    public $police_present = false;
    public $ambulance_present = false;
    public $witnesses;
    public $number_of_vehicles = 1;
    public $responsible_party;
    public $description;
    public $statement_client;
    public $notes_employee;
    public $notes_police;

    // 2. Expert Form
    public $expert_name;
    public $expert_company;
    public $expert_phone;
    public $expert_visit_date;
    public $expert_visit_time;
    public $expert_report;
    public $estimated_damage = 0;
    public $repair_cost = 0;
    public $repairable = true;
    public $total_loss = false;
    public $expert_recommendations;

    // 3. Garage Form
    public $garage_name;
    public $garage_address;
    public $garage_phone;
    public $garage_appointment_at;
    public $garage_repair_start_date;
    public $garage_repair_end_date;
    public $garage_estimate = 0;
    public $garage_invoice = 0;
    public $garage_status = 'pending';

    // 4. Cheque Form
    public $cheque_number;
    public $cheque_bank;
    public $cheque_issue_date;
    public $cheque_deposit_date;
    public $cheque_clearance_date;
    public $cheque_returned_date;
    public $cheque_reason;

    // 5. Involved Party Form
    public $party_name;
    public $party_role = 'victim';
    public $party_phone;
    public $party_email;
    public $party_company;
    public $party_notes;

    // 6. Task Form
    public $task_title;
    public $task_due_date;
    public $task_priority = 'medium';

    // 7. Follow-up Form
    public $follow_date;
    public $follow_notes;
    public $follow_priority = 'medium';

    // 8. Communication Form
    public $comm_message;
    public $comm_type = 'whatsapp'; // whatsapp, email, call, note

    // 9. Document Upload
    public $upload_file;
    public $upload_type = 'other'; // constat, expert_report, invoice, photo, etc.

    // AI properties
    public $aiResponse = '';
    public $aiLoading = false;

    // Dropdown options
    public $employees = [];
    public $users = [];

    public function mount($id)
    {
        $this->dossierId = $id;
        $this->loadDossier();
        
        $dossier = $this->getDossier();
        $this->employees = Employe::where('succursale_id', $dossier->succursale_id)->get();
        $this->users = User::all();

        // Assign headers
        $this->assigned_employee_id = $dossier->assigned_employee_id;
        $this->manager_id = $dossier->manager_id;
        $this->priority = $dossier->priority;
        $this->urgency = $dossier->urgency;
        $this->status = $dossier->status;

        // Load Accident info
        if ($dossier->accidentDetail) {
            $det = $dossier->accidentDetail;
            $this->accident_date = $det->accident_date?->format('Y-m-d');
            $this->accident_time = $det->accident_time;
            $this->accident_city = $det->accident_city;
            $this->accident_address = $det->accident_address;
            $this->accident_gps = $det->accident_gps;
            $this->weather = $det->weather;
            $this->road_condition = $det->road_condition;
            $this->police_present = $det->police_present;
            $this->ambulance_present = $det->ambulance_present;
            $this->witnesses = $det->witnesses;
            $this->number_of_vehicles = $det->number_of_vehicles;
            $this->responsible_party = $det->responsible_party;
            $this->description = $det->description;
            $this->statement_client = $det->statement_client;
            $this->notes_employee = $det->notes_employee;
            $this->notes_police = $det->notes_police;
        }

        // Load Expert info
        if ($dossier->expertDetail) {
            $exp = $dossier->expertDetail;
            $this->expert_name = $exp->expert_name;
            $this->expert_company = $exp->expert_company;
            $this->expert_phone = $exp->expert_phone;
            $this->expert_visit_date = $exp->visit_date?->format('Y-m-d');
            $this->expert_visit_time = $exp->visit_time;
            $this->expert_report = $exp->report;
            $this->estimated_damage = $exp->estimated_damage;
            $this->repair_cost = $exp->repair_cost;
            $this->repairable = $exp->repairable;
            $this->total_loss = $exp->total_loss;
            $this->expert_recommendations = $exp->recommendations;
        }

        // Load Garage info
        if ($dossier->garageDetail) {
            $gar = $dossier->garageDetail;
            $this->garage_name = $gar->garage_name;
            $this->garage_address = $gar->address;
            $this->garage_phone = $gar->phone;
            $this->garage_appointment_at = $gar->appointment_at?->format('Y-m-d\TH:i');
            $this->garage_repair_start_date = $gar->repair_start_date?->format('Y-m-d');
            $this->garage_repair_end_date = $gar->repair_end_date?->format('Y-m-d');
            $this->garage_estimate = $gar->estimate;
            $this->garage_invoice = $gar->invoice;
            $this->garage_status = $gar->status;
        }

        // Load Cheque info
        if ($dossier->chequeDetail) {
            $chq = $dossier->chequeDetail;
            $this->cheque_number = $chq->cheque_number;
            $this->cheque_bank = $chq->cheque_bank;
            $this->cheque_issue_date = $chq->issue_date?->format('Y-m-d');
            $this->cheque_deposit_date = $chq->deposit_date?->format('Y-m-d');
            $this->cheque_clearance_date = $chq->clearance_date?->format('Y-m-d');
            $this->cheque_returned_date = $chq->returned_date?->format('Y-m-d');
            $this->cheque_reason = $chq->reason;
        }
    }

    private function getDossier()
    {
        return Dossier::with([
            'client', 'contract', 'compagnie', 'succursale', 'assignedEmployee', 'manager', 
            'accidentDetail', 'involvedParties', 'expertDetail', 'garageDetail', 'chequeDetail', 
            'followUps', 'tasks', 'communications.user', 'documents'
        ])->findOrFail($this->dossierId);
    }

    public function loadDossier()
    {
        // Force Livewire reactivity refresh
    }

    // Update case headers
    public function updatedAssignedEmployeeId()
    {
        $dossier = $this->getDossier();
        $oldEmployee = $dossier->assignedEmployee ? $dossier->assignedEmployee->nom_complet : 'Non assigné';
        $dossier->assigned_employee_id = $this->assigned_employee_id ?: null;
        
        // Auto update status to assigned if still open
        if ($dossier->status === 'open' && $this->assigned_employee_id) {
            $dossier->status = 'assigned';
            $this->status = 'assigned';
            $dossier->progress = 20;
        }
        $dossier->save();

        $newEmployee = $dossier->assignedEmployee ? $dossier->assignedEmployee->nom_complet : 'Non assigné';
        ActivityLog::writeLog("Dossier {$dossier->dossier_number} : Agent assigné modifié de '{$oldEmployee}' à '{$newEmployee}'", $dossier);
        
        $this->addSystemTimelineNote("Agent assigné modifié à '{$newEmployee}'.");
        session()->flash('success', "Agent mis à jour.");
    }

    public function updatedManagerId()
    {
        $dossier = $this->getDossier();
        $dossier->manager_id = $this->manager_id ?: null;
        $dossier->save();
        session()->flash('success', "Manager mis à jour.");
    }

    public function updateStatus($newStatus)
    {
        $dossier = $this->getDossier();
        $oldStatus = $dossier->status;
        $dossier->status = $newStatus;
        $this->status = $newStatus;

        // Auto progress calculations
        $progressMap = [
            'open' => 10,
            'assigned' => 20,
            'waiting_client' => 30,
            'waiting_company' => 45,
            'waiting_expert' => 50,
            'waiting_garage' => 60,
            'docs_missing' => 40,
            'in_progress' => 70,
            'approved' => 85,
            'resolved' => 95,
            'closed' => 100,
            'cancelled' => 0,
            'rejected' => 0
        ];
        $dossier->progress = $progressMap[$newStatus] ?? 10;
        
        if ($newStatus === 'closed' || $newStatus === 'resolved') {
            $dossier->closing_date = Carbon::now()->toDateString();
        } else {
            $dossier->closing_date = null;
        }
        
        $dossier->save();

        ActivityLog::writeLog("Dossier {$dossier->dossier_number} : Statut modifié de '{$oldStatus}' à '{$newStatus}'", $dossier);
        $this->addSystemTimelineNote("Statut changé en '{$newStatus}'.");
        session()->flash('success', "Statut mis à jour.");
    }

    public function updatePriorityUrgency()
    {
        $dossier = $this->getDossier();
        $dossier->priority = $this->priority;
        $dossier->urgency = $this->urgency;
        $dossier->save();

        session()->flash('success', "Priorité / Urgence mises à jour.");
    }

    // Toggle checklist progress
    public function toggleChecklistItem($index)
    {
        $dossier = $this->getDossier();
        $checklist = $dossier->checklist;
        if (isset($checklist[$index])) {
            $checklist[$index]['completed'] = !$checklist[$index]['completed'];
            $dossier->checklist = $checklist;
            
            // Recompute progress based on check items
            $completed = count(array_filter($checklist, fn($i) => $i['completed']));
            $total = count($checklist);
            $dossier->progress = min(100, round(($completed / $total) * 100));
            
            $dossier->save();
            $this->addSystemTimelineNote("Étape de checklist modifiée : '" . $checklist[$index]['name'] . "' marqué comme " . ($checklist[$index]['completed'] ? 'complété' : 'incomplet') . ".");
        }
    }

    // Save Accident Details
    public function saveAccidentDetails()
    {
        $dossier = $this->getDossier();
        $detail = $dossier->accidentDetail ?: new DossierAccidentDetail(['dossier_id' => $dossier->id]);
        
        $detail->fill([
            'accident_date' => $this->accident_date ?: null,
            'accident_time' => $this->accident_time ?: null,
            'accident_city' => $this->accident_city,
            'accident_address' => $this->accident_address,
            'accident_gps' => $this->accident_gps,
            'weather' => $this->weather,
            'road_condition' => $this->road_condition,
            'police_present' => (bool)$this->police_present,
            'ambulance_present' => (bool)$this->ambulance_present,
            'witnesses' => $this->witnesses,
            'number_of_vehicles' => $this->number_of_vehicles,
            'responsible_party' => $this->responsible_party,
            'description' => $this->description,
            'statement_client' => $this->statement_client,
            'notes_employee' => $this->notes_employee,
            'notes_police' => $this->notes_police,
        ]);
        $detail->save();

        $this->addSystemTimelineNote("Informations de l'accident mises à jour.");
        session()->flash('success', "Détails de l'accident enregistrés.");
    }

    // Save Expert Details
    public function saveExpertDetails()
    {
        $dossier = $this->getDossier();
        $expert = $dossier->expertDetail ?: new DossierExpertDetail(['dossier_id' => $dossier->id]);

        $expert->fill([
            'expert_name' => $this->expert_name,
            'expert_company' => $this->expert_company,
            'expert_phone' => $this->expert_phone,
            'visit_date' => $this->expert_visit_date ?: null,
            'visit_time' => $this->expert_visit_time ?: null,
            'report' => $this->expert_report,
            'estimated_damage' => $this->estimated_damage ?: 0,
            'repair_cost' => $this->repair_cost ?: 0,
            'repairable' => (bool)$this->repairable,
            'total_loss' => (bool)$this->total_loss,
            'recommendations' => $this->expert_recommendations,
        ]);
        $expert->save();

        $this->addSystemTimelineNote("Rapport / Informations de l'expert mis à jour.");
        session()->flash('success', "Détails de l'expertise enregistrés.");
    }

    // Save Garage Details
    public function saveGarageDetails()
    {
        $dossier = $this->getDossier();
        $garage = $dossier->garageDetail ?: new DossierGarageDetail(['dossier_id' => $dossier->id]);

        $garage->fill([
            'garage_name' => $this->garage_name,
            'address' => $this->garage_address,
            'phone' => $this->garage_phone,
            'appointment_at' => $this->garage_appointment_at ?: null,
            'repair_start_date' => $this->garage_repair_start_date ?: null,
            'repair_end_date' => $this->garage_repair_end_date ?: null,
            'estimate' => $this->garage_estimate ?: 0,
            'invoice' => $this->garage_invoice ?: 0,
            'status' => $this->garage_status,
        ]);
        $garage->save();

        $this->addSystemTimelineNote("Informations du garage mises à jour.");
        session()->flash('success', "Détails du garage enregistrés.");
    }

    // Save Cheque Details
    public function saveChequeDetails()
    {
        $dossier = $this->getDossier();
        $cheque = $dossier->chequeDetail ?: new DossierChequeDetail(['dossier_id' => $dossier->id]);

        $cheque->fill([
            'cheque_number' => $this->cheque_number,
            'cheque_bank' => $this->cheque_bank,
            'issue_date' => $this->cheque_issue_date ?: null,
            'deposit_date' => $this->cheque_deposit_date ?: null,
            'clearance_date' => $this->cheque_clearance_date ?: null,
            'returned_date' => $this->cheque_returned_date ?: null,
            'reason' => $this->cheque_reason,
        ]);
        $cheque->save();

        $this->addSystemTimelineNote("Informations du chèque impayé mises à jour.");
        session()->flash('success', "Détails du chèque enregistrés.");
    }

    // Add Involved Party
    public function addInvolvedParty()
    {
        $this->validate([
            'party_name' => 'required|string|max:255',
            'party_role' => 'required|string',
        ]);

        DossierInvolvedParty::create([
            'dossier_id' => $this->dossierId,
            'name' => $this->party_name,
            'role' => $this->party_role,
            'phone' => $this->party_phone ?: null,
            'email' => $this->party_email ?: null,
            'company' => $this->party_company ?: null,
            'notes' => $this->party_notes ?: null,
        ]);

        $this->addSystemTimelineNote("Partie impliquée ajoutée : {$this->party_name} ({$this->party_role}).");

        $this->party_name = '';
        $this->party_phone = '';
        $this->party_email = '';
        $this->party_company = '';
        $this->party_notes = '';

        session()->flash('success', "Partie impliquée ajoutée.");
    }

    public function deleteInvolvedParty($id)
    {
        $party = DossierInvolvedParty::where('dossier_id', $this->dossierId)->findOrFail($id);
        $name = $party->name;
        $party->delete();

        $this->addSystemTimelineNote("Partie impliquée retirée : {$name}.");
        session()->flash('success', "Partie retirée.");
    }

    // Add Task
    public function addTask()
    {
        $this->validate([
            'task_title' => 'required|string|max:255',
        ]);

        $dossier = $this->getDossier();

        Task::create([
            'title' => $this->task_title,
            'assigned_user_id' => $dossier->assigned_employee_id ? Employe::find($dossier->assigned_employee_id)?->user_id : auth()->id(),
            'client_id' => $dossier->client_id,
            'contract_id' => $dossier->contract_id,
            'dossier_id' => $dossier->id,
            'priority' => $this->task_priority,
            'status' => 'todo',
            'due_date' => $this->task_due_date ?: null,
        ]);

        $this->addSystemTimelineNote("Tâche créée : '{$this->task_title}' (Priorité: {$this->task_priority}).");
        
        $this->task_title = '';
        $this->task_due_date = '';
        
        session()->flash('success', "Tâche ajoutée.");
    }

    public function toggleTask($taskId)
    {
        $task = Task::where('dossier_id', $this->dossierId)->findOrFail($taskId);
        $task->status = $task->status === 'completed' ? 'todo' : 'completed';
        $task->save();

        $this->addSystemTimelineNote("Tâche '{$task->title}' marquée comme " . ($task->status === 'completed' ? 'terminée' : 'à faire') . ".");
    }

    // Add Follow-up
    public function addFollowUp()
    {
        $this->validate([
            'follow_date' => 'required|date',
            'follow_notes' => 'required|string',
        ]);

        $dossier = $this->getDossier();

        DossierFollowUp::create([
            'dossier_id' => $this->dossierId,
            'date' => $this->follow_date,
            'employee_id' => $dossier->assigned_employee_id,
            'reminder_at' => Carbon::parse($this->follow_date)->setHour(9)->setMinute(0),
            'priority' => $this->follow_priority,
            'notes' => $this->follow_notes,
            'completed' => false,
        ]);

        $this->addSystemTimelineNote("Rappel de suivi planifié pour le " . Carbon::parse($this->follow_date)->format('d/m/Y') . ".");

        $this->follow_date = '';
        $this->follow_notes = '';
        
        session()->flash('success', "Suivi planifié.");
    }

    public function toggleFollowUp($id)
    {
        $follow = DossierFollowUp::where('dossier_id', $this->dossierId)->findOrFail($id);
        $follow->completed = !$follow->completed;
        $follow->save();

        $this->addSystemTimelineNote("Rappel de suivi marqué comme " . ($follow->completed ? 'effectué' : 'non-effectué') . ".");
    }

    // Record Communication
    public function sendMessage()
    {
        $this->validate([
            'comm_message' => 'required|string',
        ]);

        $dossier = $this->getDossier();

        Communication::create([
            'client_id' => $dossier->client_id,
            'dossier_id' => $dossier->id,
            'type' => $this->comm_type,
            'message' => $this->comm_message,
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);

        $this->comm_message = '';
        session()->flash('success', "Communication enregistrée.");
    }

    // Document / File uploads
    public function uploadDocument()
    {
        $this->validate([
            'upload_file' => 'required|file|max:10240', // Max 10MB
        ]);

        $dossier = $this->getDossier();

        $filename = $this->upload_file->getClientOriginalName();
        $path = $this->upload_file->storeAs("dossiers/{$dossier->dossier_number}", $filename, 'public');

        Document::create([
            'nom' => $filename,
            'chemin_fichier' => $path,
            'client_id' => $dossier->client_id,
            'contract_id' => $dossier->contract_id,
            'dossier_id' => $dossier->id,
            'type' => $this->upload_type,
            'file_path' => $path,
            'file_name' => $filename,
            'mime_type' => $this->upload_file->getMimeType(),
            'uploaded_by' => auth()->id(),
        ]);

        $this->addSystemTimelineNote("Nouveau document chargé : '{$filename}' (Type: {$this->upload_type}).");

        $this->upload_file = null;
        session()->flash('success', "Document ajouté.");
    }

    // AI Assistant integrations
    public function askAi($mode)
    {
        $this->aiLoading = true;
        
        $dossier = $this->getDossier();
        $clientName = $dossier->client->nom_complet;
        $typeLabel = $dossier->type === 'claim' ? 'Sinistre Automobile' : $dossier->type;

        // Mocked premium response logic simulating actual AI Analysis
        if ($mode === 'summary') {
            $this->aiResponse = "**Analyse Synthétique AI - Dossier {$dossier->dossier_number}**\n\n" .
                "• **Type de Cas :** {$typeLabel} | **Status Actuel :** {$dossier->status} | **SLA :** Conforme\n" .
                "• **Résumé :** Il s'agit d'un incident déclaré le " . $dossier->creation_date->format('d/m/Y') . " concernant le client **{$clientName}**.\n" .
                "• **Analyse Financière :** " . ($dossier->expertDetail ? "Dégâts estimés à **" . number_format($dossier->expertDetail->estimated_damage, 2) . " DH**." : "Aucune estimation d'expert enregistrée pour l'instant.") . "\n" .
                "• **Points Bloquants :** " . ($dossier->status === 'docs_missing' ? "Le dossier est bloqué car des pièces essentielles (Constat amiable ou photo du sinistre) n'ont pas encore été reçues." : "Aucun point de blocage critique détecté. Progression normale (" . $dossier->progress . "%).");
        } elseif ($mode === 'client_response') {
            $this->aiResponse = "**Proposition de message WhatsApp / Email pour le Client**\n\n" .
                "« Cher(e) {$clientName},\n\n" .
                "Nous faisons suite à l'ouverture de votre dossier de {$typeLabel} sous la référence **{$dossier->dossier_number}**.\n\n" .
                "Nos conseillers sont pleinement mobilisés. " . 
                ($dossier->status === 'waiting_expert' ? "Nous sommes actuellement en attente du passage de l'expert pour évaluer les réparations." : "Votre dossier est en cours de traitement de notre côté.") . 
                " Nous vous tiendrons informé(e) de chaque étape.\n\n" .
                "Restant à votre entière disposition,\n" .
                "L'équipe Insurio. »";
        } elseif ($mode === 'insurance_response') {
            $compName = $dossier->compagnie ? $dossier->compagnie->nom : 'l\'assureur';
            $this->aiResponse = "**Proposition de courrier officiel destiné à la Compagnie ({$compName})**\n\n" .
                "« Objet : Demande d'accord de prise en charge - Dossier {$dossier->dossier_number}\n" .
                "Réf. Police : " . ($dossier->contract ? $dossier->contract->policy_number : 'N/A') . "\n\n" .
                "Messieurs,\n\n" .
                "Nous vous prions de bien vouloir trouver ci-joint les pièces du dossier de sinistre ouvert pour notre assuré commun **{$clientName}**.\n\n" .
                "L'évaluation contradictoire de l'expert " . ($dossier->expertDetail ? "fixe le montant des réparations à " . number_format($dossier->expertDetail->repair_cost, 2) . " DH" : "est en cours de finalisation") . ".\n\n" .
                "Nous vous remercions de bien vouloir émettre votre accord de prise en charge dans les meilleurs délais.\n\n" .
                "Cordialement,\n" .
                "Direction des Sinistres, Insurio. »";
        } elseif ($mode === 'missing_docs') {
            $checklist = $dossier->checklist;
            $missing = [];
            foreach ($checklist as $item) {
                if (!$item['completed']) {
                    $missing[] = $item['name'];
                }
            }
            
            $this->aiResponse = "**Analyse AI - Détection des pièces manquantes**\n\n" .
                "D'après les étapes requises, les éléments suivants sont manquants ou non validés :\n" .
                implode("\n", array_map(fn($m) => "❌ • *{$m}*", $missing)) . "\n\n" .
                "👉 *Action recommandée :* Envoyer un rappel WhatsApp automatisé au client pour réclamer le document.";
        }

        $this->aiLoading = false;
    }

    private function addSystemTimelineNote($message)
    {
        $dossier = $this->getDossier();
        Communication::create([
            'client_id' => $dossier->client_id,
            'dossier_id' => $dossier->id,
            'type' => 'note',
            'message' => "Système : {$message}",
            'user_id' => auth()->id() ?: $dossier->manager_id,
            'created_at' => now(),
        ]);
        
        $this->loadDossier();
    }

    public function render()
    {
        return view('livewire.admin.dossier-workspace', [
            'dossier' => $this->getDossier(),
        ])->layout('layouts.app');
    }
}
