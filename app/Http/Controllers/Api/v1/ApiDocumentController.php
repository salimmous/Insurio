<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiDocumentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'type' => 'required|in:cin,passport,driving_license,vehicle_registration,contract,invoice,other',
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $tenantId = tenant('id') ?? 'default';
        $file = $request->file('file');
        
        $directory = "{$tenantId}/clients/{$client->id}";
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();

        // Store file privately under local disk
        $path = $file->storeAs($directory, Str::random(40) . '.' . $file->getClientOriginalExtension(), 'local');

        $document = Document::create([
            'client_id' => $client->id,
            'contract_id' => $validated['contract_id'] ?? null,
            'type' => $validated['type'],
            'file_path' => $path,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'uploaded_by' => auth()->id(),
            'nom' => $fileName, // Compatibility
            'chemin_fichier' => $path, // Compatibility
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document téléversé avec succès.',
            'data' => [
                'id' => $document->id,
                'file_name' => $document->file_name,
                'type' => $document->type,
                'mime_type' => $document->mime_type,
            ]
        ], 201);
    }
}
