<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class GestionProducts extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $productId = null;
    public $code = '';
    public $nom = '';
    public $description = '';
    public $statut = 'actif';

    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:products,code,' . $this->productId,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'statut' => 'required|in:actif,inactif',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        $this->productId = $id;

        if ($id) {
            $product = Product::findOrFail($id);
            $this->code = $product->code;
            $this->nom = $product->nom;
            $this->description = $product->description;
            $this->statut = $product->statut;
        } else {
            $this->resetForm();
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->productId = null;
        $this->code = '';
        $this->nom = '';
        $this->description = '';
        $this->statut = 'actif';
    }

    public function save()
    {
        $this->validate();

        Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'code' => strtoupper($this->code),
                'nom' => $this->nom,
                'description' => $this->description,
                'statut' => $this->statut,
            ]
        );

        $this->closeModal();
        $this->dispatch('swal:success', ['message' => $this->productId ? 'Produit modifié avec succès.' : 'Produit créé avec succès.']);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        // Prevent delete if contracts exist with this product
        if ($product->contrats()->exists()) {
            $this->dispatch('swal:error', ['message' => 'Impossible de supprimer ce produit car il est associé à des contrats.']);
            return;
        }

        $product->delete();
        $this->dispatch('swal:success', ['message' => 'Produit supprimé avec succès.']);
    }

    public function render()
    {
        $query = Product::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.admin.gestion-products', [
            'products' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
