<?php

namespace App\Livewire\Apporteur;

use Livewire\Component;
use App\Models\Apporteur;
use App\Models\Client;

use App\Models\Setting;

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

    public function selectApporteur($id, $nom = '', $prenom = '', $taux = 0.00, $clientId = null)
    {
        $this->dispatch('apporteurSelected', [
            'id' => is_numeric($id) ? (int)$id : null,
            'client_id' => is_numeric($clientId) ? (int)$clientId : (is_numeric($id) ? (int)$id : null),
            'nom' => $nom,
            'prenom' => $prenom,
            'taux' => (float)$taux,
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
        $defaultRate = (float) Setting::get('default_apporteur_commission_rate', 0.00);

        if (empty($query)) {
            $apporteursList = Apporteur::latest()->limit(15)->get();
        } else {
            $apporteursList = Apporteur::where('nom', 'like', '%' . $query . '%')
                ->orWhere('prenom', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('telephone', 'like', '%' . $query . '%')
                ->orWhere('code_apporteur', 'like', '%' . $query . '%')
                ->limit(20)
                ->get();
        }

        $apporteurs = $apporteursList->map(function ($app) use ($defaultRate) {
            return (object) [
                'id' => $app->id,
                'client_id' => null,
                'code' => $app->code_apporteur ?? 'APP-' . str_pad($app->id, 5, '0', STR_PAD_LEFT),
                'nom' => $app->nom,
                'prenom' => $app->prenom,
                'telephone' => $app->telephone,
                'source' => 'Apporteur',
                'taux_commission' => $app->taux_commission !== null ? (float)$app->taux_commission : $defaultRate,
            ];
        });

        return view('livewire.apporteur.vision-apporteur-modal', ['apporteurs' => $apporteurs]);
    }
}
