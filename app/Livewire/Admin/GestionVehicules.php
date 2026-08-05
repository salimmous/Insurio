<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\VehiculeMarque;
use App\Models\VehiculeModele;
use App\Services\VehiculeCatalogService;

class GestionVehicules extends Component
{
    public $search = '';
    public $filterType = 'all'; // all, voiture, moto, autocar
    public $displayMode = 'table'; // 'table' or 'grid'

    // Brand Form
    public $showBrandModal = false;
    public $brandId = null;
    public $brandNom = '';
    public $brandType = 'voiture';
    public $brandLogo = '';

    // Model Form
    public $showModelModal = false;
    public $selectedBrandId = null;
    public $modelId = null;
    public $modelNom = '';
    public $modelAnneeDebut = null;
    public $modelAnneeFin = null;

    public function mount()
    {
        VehiculeCatalogService::seedIfEmpty();
    }

    public function openBrandModal($id = null)
    {
        $this->resetValidation();
        $this->brandId = $id;

        if ($id) {
            $brand = VehiculeMarque::findOrFail($id);
            $this->brandNom = $brand->nom;
            $this->brandType = $brand->type;
            $this->brandLogo = $brand->logo ?? '';
        } else {
            $this->brandNom = '';
            $this->brandType = 'voiture';
            $this->brandLogo = '';
        }

        $this->showBrandModal = true;
    }

    public function saveBrand()
    {
        $this->validate([
            'brandNom' => 'required|string|max:100',
            'brandType' => 'required|in:voiture,moto,autocar',
            'brandLogo' => 'nullable|url|max:500',
        ]);

        VehiculeMarque::updateOrCreate(
            ['id' => $this->brandId],
            [
                'nom' => trim($this->brandNom),
                'type' => $this->brandType,
                'logo' => trim($this->brandLogo) ?: null,
                'is_active' => true,
            ]
        );

        session()->flash('success', $this->brandId ? 'Marque mise à jour avec succès.' : 'Nouvelle marque ajoutée avec succès.');
        $this->showBrandModal = false;
    }

    public function deleteBrand($id)
    {
        $brand = VehiculeMarque::findOrFail($id);
        $brand->delete();
        session()->flash('success', "La marque '{$brand->nom}' a été supprimée.");
    }

    public function openModelModal($brandId, $modelId = null)
    {
        $this->resetValidation();
        $this->selectedBrandId = $brandId;
        $this->modelId = $modelId;

        if ($modelId) {
            $model = VehiculeModele::findOrFail($modelId);
            $this->modelNom = $model->nom;
            $this->modelAnneeDebut = $model->annee_debut;
            $this->modelAnneeFin = $model->annee_fin;
        } else {
            $this->modelNom = '';
            $this->modelAnneeDebut = null;
            $this->modelAnneeFin = null;
        }

        $this->showModelModal = true;
    }

    public function saveModel()
    {
        $this->validate([
            'modelNom' => 'required|string|max:100',
            'selectedBrandId' => 'required|exists:vehicule_marques,id',
            'modelAnneeDebut' => 'nullable|integer|min:1970|max:2030',
            'modelAnneeFin' => 'nullable|integer|min:1970|max:2030|gte:modelAnneeDebut',
        ]);

        VehiculeModele::updateOrCreate(
            ['id' => $this->modelId],
            [
                'marque_id' => $this->selectedBrandId,
                'nom' => trim($this->modelNom),
                'annee_debut' => $this->modelAnneeDebut ?: null,
                'annee_fin' => $this->modelAnneeFin ?: null,
                'is_active' => true,
            ]
        );

        session()->flash('success', $this->modelId ? 'Modèle mis à jour.' : 'Nouveau modèle ajouté avec succès.');
        $this->showModelModal = false;
    }

    public function deleteModel($id)
    {
        $model = VehiculeModele::findOrFail($id);
        $model->delete();
        session()->flash('success', "Le modèle '{$model->nom}' a été supprimé.");
    }

    public function render()
    {
        $query = VehiculeMarque::with('modeles')->orderBy('nom');

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nom', 'like', $searchTerm)
                  ->orWhereHas('modeles', function ($mQuery) use ($searchTerm) {
                      $mQuery->where('nom', 'like', $searchTerm);
                  });
            });
        }

        $marques = $query->get();
        $totalMarques = VehiculeMarque::count();
        $totalModeles = VehiculeModele::count();

        return view('livewire.admin.gestion-vehicules', compact('marques', 'totalMarques', 'totalModeles'))
            ->layout('layouts.app');
    }
}
