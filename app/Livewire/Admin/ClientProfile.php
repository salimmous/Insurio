<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Document;
use App\Models\Communication;
use App\Models\User;
use App\Models\Task;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Dossier;
use App\Services\CloudStorageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientProfile extends Component
{
    use WithFileUploads;

    public $client;
    public $activeTab = 'contracts';
    
    // Core KPIs
    public $riskScore = 'A';
    public $customerLifetimeValue = 0.00;
    public $outstandingBalance = 0.00;
    public $satisfactionScore = 5;
    public $assignedAdvisorId = null;

    // Notes tab
    public $clientNotes = '';

    // Copilot AI properties
    public $showAiPanel = false;
    public $aiQuery = '';
    public $aiResult = '';

    // Communication form (Email, SMS, WhatsApp, Call, Note)
    public $communicationType = 'whatsapp';
    public $communicationMessage = '';

    // Document upload form
    public $uploadedFile;
    public $documentType = 'cin';

    // Family Member Modal Form
    public $showFamilyModal = false;
    public $familyMemberName = '';
    public $familyMemberRelation = 'conjoint';
    public $familyMemberCin = '';
    public $familyMemberPhone = '';
    public $familyMemberBirthDate = '';

    // Beneficiary Modal Form
    public $showBeneficiaryModal = false;
    public $beneficiaryName = '';
    public $beneficiaryRelation = 'conjoint';
    public $beneficiaryCin = '';
    public $beneficiaryPercentage = 100;
    public $beneficiaryPhone = '';

    // Task Modal Form
    public $showTaskModal = false;
    public $taskTitle = '';
    public $taskDueDate = '';
    public $taskPriority = 'normal';
    public $taskType = 'call';

    protected $rules = [
        'clientNotes' => 'nullable|string|max:10000',
    ];

    public function mount($clientId)
    {
        $this->client = Client::where('id', $clientId)
            ->orWhere('uuid', $clientId)
            ->firstOrFail();

        $this->clientNotes = $this->client->notes;
        $this->satisfactionScore = $this->client->satisfaction_score ?? 5;
        $this->assignedAdvisorId = $this->client->assigned_to;

        // Calculate Risk Score based on claims
        $claimsCount = \Illuminate\Support\Facades\Schema::hasTable('dossiers') 
            ? Dossier::where('client_id', $this->client->id)->where('type', 'claim')->count() 
            : 0;

        if ($claimsCount === 0) {
            $this->riskScore = 'A';
        } elseif ($claimsCount === 1) {
            $this->riskScore = 'B';
        } elseif ($claimsCount === 2) {
            $this->riskScore = 'C';
        } else {
            $this->riskScore = 'D';
        }

        // Financial KPIs
        $this->customerLifetimeValue = \Illuminate\Support\Facades\Schema::hasTable('contracts') 
            ? (float) Contract::where('client_id', $this->client->id)->sum('premium_amount')
            : 0.00;

        $this->outstandingBalance = \Illuminate\Support\Facades\Schema::hasTable('payments')
            ? (float) Payment::where('client_id', $this->client->id)->whereNotIn('payment_status', ['paid', 'cancelled'])->sum('remaining_amount')
            : 0.00;
    }

    public function updateAdvisor()
    {
        $this->client->update([
            'assigned_to' => $this->assignedAdvisorId,
        ]);

        $advisorName = User::find($this->assignedAdvisorId)?->name ?? 'Aucun';
        $this->logActivity('system', "Conseiller réattribué à : {$advisorName}");
        $this->dispatch('swal:success', ['message' => 'Conseiller mis à jour.']);
    }

    public function setSatisfaction($score)
    {
        $this->satisfactionScore = (int)$score;
        $this->client->update([
            'satisfaction_score' => $this->satisfactionScore,
        ]);

        $this->logActivity('system', "Niveau de satisfaction mis à jour : {$score}/5 étoiles");
        $this->dispatch('swal:success', ['message' => 'Satisfaction client mise à jour.']);
    }

    public function addFamilyMember()
    {
        $this->validate([
            'familyMemberName' => 'required|string|max:255',
            'familyMemberRelation' => 'required|string|max:100',
            'familyMemberPhone' => 'nullable|string|max:50',
            'familyMemberCin' => 'nullable|string|max:50',
        ]);

        $family = $this->client->family_members ?? [];
        $family[] = [
            'id' => Str::uuid()->toString(),
            'name' => $this->familyMemberName,
            'relation' => $this->familyMemberRelation,
            'cin' => $this->familyMemberCin,
            'phone' => $this->familyMemberPhone,
            'birth_date' => $this->familyMemberBirthDate,
        ];

        $this->client->update(['family_members' => $family]);
        $this->logActivity('note', "Membre de famille ajouté : {$this->familyMemberName} ({$this->familyMemberRelation})");

        $this->reset(['familyMemberName', 'familyMemberRelation', 'familyMemberCin', 'familyMemberPhone', 'familyMemberBirthDate', 'showFamilyModal']);
        $this->dispatch('swal:success', ['message' => 'Membre de famille ajouté avec succès.']);
    }

    public function removeFamilyMember($index)
    {
        $family = $this->client->family_members ?? [];
        if (isset($family[$index])) {
            $name = $family[$index]['name'] ?? 'Membre';
            unset($family[$index]);
            $this->client->update(['family_members' => array_values($family)]);
            $this->logActivity('note', "Membre de famille retiré : {$name}");
            $this->dispatch('swal:success', ['message' => 'Membre supprimé.']);
        }
    }

    public function addBeneficiary()
    {
        $this->validate([
            'beneficiaryName' => 'required|string|max:255',
            'beneficiaryRelation' => 'required|string|max:100',
            'beneficiaryPercentage' => 'required|numeric|min:1|max:100',
        ]);

        $beneficiaries = $this->client->beneficiaries ?? [];
        $beneficiaries[] = [
            'id' => Str::uuid()->toString(),
            'name' => $this->beneficiaryName,
            'relation' => $this->beneficiaryRelation,
            'cin' => $this->beneficiaryCin,
            'percentage' => $this->beneficiaryPercentage,
            'phone' => $this->beneficiaryPhone,
        ];

        $this->client->update(['beneficiaries' => $beneficiaries]);
        $this->logActivity('note', "Bénéficiaire désigné : {$this->beneficiaryName} ({$this->beneficiaryPercentage}%)");

        $this->reset(['beneficiaryName', 'beneficiaryRelation', 'beneficiaryCin', 'beneficiaryPercentage', 'beneficiaryPhone', 'showBeneficiaryModal']);
        $this->dispatch('swal:success', ['message' => 'Bénéficiaire ajouté avec succès.']);
    }

    public function removeBeneficiary($index)
    {
        $beneficiaries = $this->client->beneficiaries ?? [];
        if (isset($beneficiaries[$index])) {
            $name = $beneficiaries[$index]['name'] ?? 'Bénéficiaire';
            unset($beneficiaries[$index]);
            $this->client->update(['beneficiaries' => array_values($beneficiaries)]);
            $this->logActivity('note', "Bénéficiaire supprimé : {$name}");
            $this->dispatch('swal:success', ['message' => 'Bénéficiaire supprimé.']);
        }
    }

    public function addTask()
    {
        $this->validate([
            'taskTitle' => 'required|string|max:255',
            'taskDueDate' => 'required|date',
            'taskPriority' => 'required|in:low,normal,high,urgent',
            'taskType' => 'required|in:call,meeting,email,follow_up',
        ]);

        Task::create([
            'client_id' => $this->client->id,
            'title' => $this->taskTitle,
            'due_date' => $this->taskDueDate,
            'priority' => $this->taskPriority,
            'type' => $this->taskType,
            'status' => 'pending',
            'assigned_to' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        $this->client->update(['next_contact_at' => $this->taskDueDate]);

        $this->logActivity('note', "Tâche / Rendez-vous planifié : {$this->taskTitle} pour le {$this->taskDueDate}");
        $this->reset(['taskTitle', 'taskDueDate', 'taskPriority', 'taskType', 'showTaskModal']);
        $this->dispatch('swal:success', ['message' => 'Tâche créée avec succès.']);
    }

    public function completeTask($taskId)
    {
        $task = Task::where('client_id', $this->client->id)->findOrFail($taskId);
        $task->update(['status' => 'completed']);

        $this->client->update(['last_contact_at' => now()]);
        $this->logActivity('system', "Tâche terminée : {$task->title}");
        $this->dispatch('swal:success', ['message' => 'Tâche marquée comme terminée.']);
    }

    public function saveNotes()
    {
        $this->validate();

        $this->client->update([
            'notes' => $this->clientNotes,
        ]);

        $this->logActivity('note', 'Modification des notes internes du client.');
        $this->dispatch('swal:success', ['message' => 'Notes enregistrées avec succès.']);
    }

    public function addCommunication()
    {
        $this->validate([
            'communicationMessage' => 'required|string|max:5000',
            'communicationType' => 'required|in:whatsapp,email,sms,call,note',
        ]);

        Communication::create([
            'client_id' => $this->client->id,
            'type' => $this->communicationType,
            'message' => $this->communicationMessage,
            'user_id' => auth()->id(),
        ]);

        $this->client->update(['last_contact_at' => now()]);

        $this->communicationMessage = '';
        $this->dispatch('swal:success', ['message' => 'Activité enregistrée dans la Timeline 360°.']);
    }

    public function uploadDocument()
    {
        $this->validate([
            'uploadedFile' => 'required|file|max:10240',
            'documentType' => 'required|in:cin,passport,driving_license,vehicle_registration,contract,invoice,other',
        ]);

        $tenantId = tenant('id') ?? 'default';
        $path = "clients/{$this->client->id}/" . Str::random(30) . '.' . $this->uploadedFile->getClientOriginalExtension();
        $fileName = $this->uploadedFile->getClientOriginalName();
        $mimeType = $this->uploadedFile->getMimeType();

        // Store via CloudStorageService
        $storedPath = CloudStorageService::putFile($path, file_get_contents($this->uploadedFile->getRealPath()));

        Document::create([
            'client_id' => $this->client->id,
            'type' => $this->documentType,
            'file_path' => $storedPath ?? $path,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'uploaded_by' => auth()->id(),
            'nom' => $fileName,
            'chemin_fichier' => $storedPath ?? $path,
        ]);

        $this->uploadedFile = null;
        $this->logActivity('note', "Téléversement du document : {$fileName} ({$this->documentType})");
        $this->dispatch('swal:success', ['message' => 'Document téléversé avec succès.']);
    }

    public function deleteDocument($id)
    {
        $document = Document::where('client_id', $this->client->id)->findOrFail($id);
        $fileName = $document->file_name;

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        $this->logActivity('note', "Suppression du document : {$fileName}");
        $this->dispatch('swal:success', ['message' => 'Document supprimé avec succès.']);
    }

    public function downloadDocument($id)
    {
        $document = Document::where('client_id', $this->client->id)->findOrFail($id);
        $secureUrl = CloudStorageService::getSecureUrl($document->file_path);

        return redirect()->away($secureUrl);
    }

    private function logActivity($type, $message)
    {
        Communication::create([
            'client_id' => $this->client->id,
            'type' => $type,
            'message' => $message,
            'user_id' => auth()->id(),
        ]);
    }

    public function askAiCopilot()
    {
        $this->validate([
            'aiQuery' => 'required|string|min:3|max:2000',
        ]);

        $this->aiResult = \App\Services\AiCopilotService::generateClientAdvice($this->client, $this->aiQuery);
        $this->aiQuery = '';
    }

    public function render()
    {
        $contracts = $this->client->contrats()->with(['compagnie', 'product', 'reglements'])->latest()->get();
        $documents = \Illuminate\Support\Facades\Schema::hasTable('documents') ? Document::where('client_id', $this->client->id)->latest()->get() : collect();
        $payments = \Illuminate\Support\Facades\Schema::hasTable('payments') ? Payment::where('client_id', $this->client->id)->with('contrat.compagnie')->latest()->get() : collect();
        $timeline = \Illuminate\Support\Facades\Schema::hasTable('communications') ? Communication::where('client_id', $this->client->id)->with('user')->latest()->get() : collect();
        $tasks = \Illuminate\Support\Facades\Schema::hasTable('tasks') ? Task::where('client_id', $this->client->id)->latest()->get() : collect();
        $claims = \Illuminate\Support\Facades\Schema::hasTable('dossiers') ? Dossier::where('client_id', $this->client->id)->where('type', 'claim')->latest()->get() : collect();
        $advisors = User::where('is_active', true)->get();

        return view('livewire.admin.client-profile', compact('contracts', 'documents', 'payments', 'timeline', 'tasks', 'claims', 'advisors'))
            ->layout('layouts.app');
    }
}
