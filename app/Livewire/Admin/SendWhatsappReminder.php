<?php

namespace App\Livewire\Admin;

use App\Models\Contract;
use App\Services\WhatsApp\WhatsAppProvider;
use Livewire\Component;

class SendWhatsappReminder extends Component
{
    public $contractId;
    public $phoneNumber;
    public $message;
    public $showModal = false;

    protected $listeners = ['triggerWhatsAppReminder' => 'openModal'];

    public function mount($contractId = null)
    {
        if ($contractId) {
            $this->loadContractData($contractId);
        }
    }

    public function openModal($contractId)
    {
        $this->loadContractData($contractId);
        $this->showModal = true;
    }

    public function loadContractData($contractId)
    {
        $this->contractId = $contractId;
        $contract = Contract::with('client')->find($contractId);
        if ($contract) {
            $clientName = $contract->client ? ($contract->client->first_name . ' ' . $contract->client->last_name) : 'Cher client';
            $this->phoneNumber = $contract->client?->phone ?? '';
            $this->message = "Bonjour {$clientName},\n\nVotre contrat {$contract->contract_number} expire le {$contract->end_date}.\n\nContactez-nous pour le renouvellement.";
        }
    }

    public function send(WhatsAppProvider $whatsapp)
    {
        $this->validate([
            'phoneNumber' => 'required',
            'message' => 'required|string',
        ]);

        try {
            $result = $whatsapp->sendMessage($this->phoneNumber, $this->message);

            if ($result['success']) {
                session()->flash('whatsapp_success', 'Rappel WhatsApp envoyé avec succès via ' . $result['driver'] . '!');
            } else {
                session()->flash('whatsapp_error', 'Échec de l\'envoi du message.');
            }
        } catch (\Exception $e) {
            session()->flash('whatsapp_error', 'Erreur : ' . $e->getMessage());
        }

        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.send-whatsapp-reminder');
    }
}
