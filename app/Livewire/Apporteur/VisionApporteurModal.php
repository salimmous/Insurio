<?php

namespace App\Livewire\Apporteur;

use Livewire\Component;
use App\Models\Apporteur;
use App\Models\Client;

class VisionApporteurModal extends Component
{
    public $search = '';
    public $isOpen = false;

    protected $listeners = ['openVisionApporteur' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->search = '';
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function selectApporteur($id, $nom = '', $prenom = '', $taux = 10.00)
    {
        $this->dispatch('apporteurSelected', [
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'taux' => $taux,
        ]);
        $this->close();
    }

    public function clearApporteur()
    {
        $this->dispatch('apporteurSelected', null);
        $this->close();
    }

    public function render()
    {
        $query = trim($this->search);

        if (empty($query)) {
            $clients = Client::latest()->limit(10)->get();
            $apporteurs = Apporteur::latest()->limit(10)->get();
        } else {
            $clients = Client::where('last_name', 'like', '%' . $query . '%')
                ->orWhere('first_name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('phone', 'like', '%' . $query . '%')
                ->orWhere('cin', 'like', '%' . $query . '%')
                ->limit(10)
                ->get();

            $apporteurs = Apporteur::where('nom', 'like', '%' . $query . '%')
                ->orWhere('prenom', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('code_apporteur', 'like', '%' . $query . '%')
                ->limit(10)
                ->get();
        }

        $combined = collect();

        foreach ($clients as $client) {
            $appRec = Apporteur::where('email', $client->email)->orWhere('telephone', $client->phone)->first();
            $combined->push((object) [
                'id' => $appRec ? $appRec->id : $client->id,
                'code' => $client->formatted_reference,
                'nom' => $client->nom,
                'prenom' => $client->prenom,
                'telephone' => $client->telephone,
                'source' => 'Client',
                'taux_commission' => $appRec ? $appRec->taux_commission : 10.00,
            ]);
        }

        foreach ($apporteurs as $app) {
            if (!$combined->contains('id', $app->id)) {
                $combined->push((object) [
                    'id' => $app->id,
                    'code' => $app->code_apporteur ?? 'APP-' . $app->id,
                    'nom' => $app->nom,
                    'prenom' => $app->prenom,
                    'telephone' => $app->telephone,
                    'source' => 'Apporteur',
                    'taux_commission' => $app->taux_commission ?? 10.00,
                ]);
            }
        }

        return view('livewire.apporteur.vision-apporteur-modal', ['apporteurs' => $combined]);
    }
}
