<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentPreviewController extends Controller
{
    public function show($id)
    {
        $document = Document::findOrFail($id);

        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        // Branch boundary checks for Agent and non-owner roles
        if (!$user->hasRole('Super Admin') && !$user->hasRole('agency-admin') && !$user->hasRole('Agency Owner')) {
            $client = $document->client;
            if ($client && $user->branch_id && $client->succursale_id !== $user->branch_id) {
                abort(403, 'Accès non autorisé à ce document (succursale différente).');
            }
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Fichier introuvable.');
        }

        $file = Storage::disk('local')->get($document->file_path);
        
        return response($file, 200, [
            'Content-Type' => $document->mime_type ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]);
    }
}
