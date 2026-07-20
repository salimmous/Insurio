<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Document;
use App\Models\Communication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientProfile extends Component
{
    use WithFileUploads;

    public $client;
    public $activeTab = 'contracts';
    
    // Notes tab
    public $clientNotes = '';

    // Communication form
    public $communicationType = 'whatsapp';
    public $communicationMessage = '';

    // Document upload form
    public $uploadedFile;
    public $documentType = 'cin';

    protected $rules = [
        'clientNotes' => 'nullable|string|max:10000',
    ];

    public function mount($clientId)
    {
        // Load by ID or UUID
        $this->client = Client::where('id', $clientId)
            ->orWhere('uuid', $clientId)
            ->firstOrFail();

        $this->clientNotes = $this->client->notes;
    }

    public function saveNotes()
    {
        $this->validate();

        $this->client->update([
            'notes' => $this->clientNotes,
        ]);

        $this->dispatch('swal:success', ['message' => 'Notes enregistrées avec succès.']);
        
        // Log activity
        $this->logActivity('note', 'Modification des notes du client.');
    }

    public function addCommunication()
    {
        $this->validate([
            'communicationMessage' => 'required|string|max:5000',
            'communicationType' => 'required|in:whatsapp,email,call,note',
        ]);

        Communication::create([
            'client_id' => $this->client->id,
            'type' => $this->communicationType,
            'message' => $this->communicationMessage,
            'user_id' => auth()->id(),
        ]);

        $this->communicationMessage = '';
        $this->dispatch('swal:success', ['message' => 'Activité enregistrée dans l\'historique.']);
    }

    public function uploadDocument()
    {
        $this->validate([
            'uploadedFile' => 'required|file|max:10240', // max 10MB
            'documentType' => 'required|in:cin,passport,driving_license,vehicle_registration,contract,invoice,other',
        ]);

        $tenantId = tenant('id') ?? 'default';
        
        // Define directory: tenant_id/clients/client_id/
        $directory = "{$tenantId}/clients/{$this->client->id}";
        
        $fileName = $this->uploadedFile->getClientOriginalName();
        $mimeType = $this->uploadedFile->getMimeType();
        
        // Store securely on the local private disk
        $path = $this->uploadedFile->storeAs($directory, Str::random(40) . '.' . $this->uploadedFile->getClientOriginalExtension(), 'local');

        Document::create([
            'client_id' => $this->client->id,
            'type' => $this->documentType,
            'file_path' => $path,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'uploaded_by' => auth()->id(),
            'nom' => $fileName, // backward compatibility
            'chemin_fichier' => $path, // backward compatibility
        ]);

        $this->uploadedFile = null;
        $this->dispatch('swal:success', ['message' => 'Document téléversé avec succès.']);
        
        // Log activity
        $this->logActivity('note', "Téléversement du document : {$fileName} ({$this->documentType})");
    }

    public function deleteDocument($id)
    {
        $document = Document::where('client_id', $this->client->id)->findOrFail($id);
        
        // Delete physical file
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $fileName = $document->file_name;
        $document->delete();

        $this->dispatch('swal:success', ['message' => 'Document supprimé avec succès.']);
        
        // Log activity
        $this->logActivity('note', "Suppression du document : {$fileName}");
    }

    public function downloadDocument($id)
    {
        $document = Document::where('client_id', $this->client->id)->findOrFail($id);

        if (!Storage::disk('local')->exists($document->file_path)) {
            $this->dispatch('swal:error', ['message' => 'Le fichier n\'existe plus sur le serveur.']);
            return null;
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
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

    public function render()
    {
        $contracts = $this->client->contrats()->with(['compagnie', 'product', 'reglements'])->latest()->get();
        $documents = Document::where('client_id', $this->client->id)->latest()->get();
        $payments = \App\Models\Reglement::whereIn('contrat_id', $contracts->pluck('id'))->with('contrat')->latest()->get();
        $timeline = Communication::where('client_id', $this->client->id)->with('user')->latest()->get();

        return view('livewire.admin.client-profile', compact('contracts', 'documents', 'payments', 'timeline'))
            ->layout('layouts.app');
    }
}
