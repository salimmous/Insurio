<?php

namespace App\Livewire\Admin;

use App\Models\Import;
use App\Imports\ClientsImport;
use App\Imports\ContractsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportManager extends Component
{
    use WithFileUploads;

    public $importFile;
    public $importType = 'clients'; // clients, contracts
    public $previewRows = [];
    public $headers = [];
    public $showPreview = false;

    public function updatedImportFile()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:10240', // max 10MB
        ]);

        try {
            $path = $this->importFile->getRealPath();
            $rows = Excel::toArray(new \stdClass(), $path);
            
            if (!empty($rows) && !empty($rows[0])) {
                $sheet = $rows[0];
                $this->headers = $sheet[0] ?? [];
                // Load up to 5 rows for preview
                $this->previewRows = array_slice($sheet, 1, 5);
                $this->showPreview = true;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Impossible de lire le fichier Excel : ' . $e->getMessage());
        }
    }

    public function runImport()
    {
        $this->validate([
            'importFile' => 'required',
            'importType' => 'required|in:clients,contracts',
        ]);

        try {
            $originalName = $this->importFile->getClientOriginalName();
            $storedPath = $this->importFile->store('imports');

            if ($this->importType === 'clients') {
                $importInstance = new ClientsImport();
            } else {
                $importInstance = new ContractsImport();
            }

            Excel::import($importInstance, $this->importFile->getRealPath());

            // Save Import entry to database
            Import::create([
                'user_id' => auth()->id(),
                'type' => $this->importType,
                'file' => $originalName,
                'total_rows' => $importInstance->getSuccessCount() + $importInstance->getFailedCount(),
                'success_rows' => $importInstance->getSuccessCount(),
                'failed_rows' => $importInstance->getFailedCount(),
                'errors' => $importInstance->getErrors(),
            ]);

            session()->flash('success', 'Importation terminée avec succès !');
            $this->reset(['importFile', 'showPreview', 'previewRows', 'headers']);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function render()
    {
        $history = Import::with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.import-manager', [
            'history' => $history
        ])->layout('layouts.app');
    }
}
