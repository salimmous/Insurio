<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Client;
use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $client_id, $contract_id, $amount, $payment_method, $reference;
    public $status = 'paid';
    public $isOpen = false;

    public $contracts = [];

    public function mount()
    {
        $this->contracts = Contract::all();
    }

    public function updatedClientId()
    {
        if ($this->client_id) {
            $this->contracts = Contract::where('client_id', $this->client_id)->get();
        } else {
            $this->contracts = Contract::all();
        }
    }

    public function render()
    {
        $payments = Payment::with(['client', 'contract'])
            ->whereHas('client', function ($q) {
                $q->where('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%');
            })
            ->orWhere('reference', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $clients = Client::all();

        return view('livewire.admin.payment-manager', [
            'payments' => $payments,
            'clients' => $clients,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,card,cheque',
            'status' => 'required|in:paid,pending,partial',
        ]);

        Payment::create([
            'client_id' => $this->client_id,
            'contract_id' => $this->contract_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'reference' => $this->reference,
        ]);

        session()->flash('message', 'Paiement enregistré avec succès.');
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->client_id = null;
        $this->contract_id = null;
        $this->amount = '';
        $this->payment_method = 'cash';
        $this->status = 'paid';
        $this->reference = '';
    }
}
