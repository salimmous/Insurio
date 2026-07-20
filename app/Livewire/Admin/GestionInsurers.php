<?php

namespace App\Livewire\Admin;

use App\Models\Compagnie;
use Livewire\Component;
use Livewire\WithPagination;

class GestionInsurers extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $code, $logo, $address, $phone, $contact, $email;
    public $active = true;
    public $editingInsurerId = null;
    public $isOpen = false;

    public function render()
    {
        $insurers = Compagnie::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.gestion-insurers', [
            'insurers' => $insurers
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $insurer = Compagnie::findOrFail($id);
        $this->editingInsurerId = $id;
        $this->name = $insurer->name;
        $this->code = $insurer->code;
        $this->logo = $insurer->logo;
        $this->address = $insurer->address;
        $this->phone = $insurer->phone;
        $this->contact = $insurer->contact;
        $this->email = $insurer->email;
        $this->active = $insurer->active;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $data = [
            'nom' => $this->name,
            'code' => $this->code,
            'logo' => $this->logo,
            'adresse' => $this->address,
            'telephone' => $this->phone,
            'contact' => $this->contact,
            'email' => $this->email,
            'active' => $this->active,
        ];

        if ($this->editingInsurerId) {
            Compagnie::findOrFail($this->editingInsurerId)->update($data);
            session()->flash('message', 'Compagnie d\'assurance mise à jour !');
        } else {
            Compagnie::create($data);
            session()->flash('message', 'Compagnie d\'assurance créée !');
        }

        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Compagnie::findOrFail($id)->delete();
        session()->flash('message', 'Compagnie d\'assurance supprimée avec succès.');
    }

    private function resetInputFields()
    {
        $this->editingInsurerId = null;
        $this->name = '';
        $this->code = '';
        $this->logo = '';
        $this->address = '';
        $this->phone = '';
        $this->contact = '';
        $this->email = '';
        $this->active = true;
    }
}
