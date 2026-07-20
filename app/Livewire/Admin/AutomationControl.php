<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AutomationRule;

class AutomationControl extends Component
{
    public $rulesList = [];
    
    // Create form fields
    public $name = '';
    public $event = 'contract.expiring';
    public $daysBeforeExpiry = 30;
    public $actionWhatsapp = true;
    public $actionTask = true;
    public $actionEmail = false;

    public function mount()
    {
        // Auto-seed default rules if empty
        if (AutomationRule::count() === 0) {
            AutomationRule::create([
                'name' => 'Relance Renouvellement Standard (30 Jours)',
                'event' => 'contract.expiring',
                'conditions' => ['days_before_expiry' => 30],
                'actions' => [
                    ['type' => 'whatsapp'],
                    ['type' => 'task']
                ],
                'is_active' => true,
            ]);

            AutomationRule::create([
                'name' => 'Relance Critique WhatsApp (7 Jours)',
                'event' => 'contract.expiring',
                'conditions' => ['days_before_expiry' => 7],
                'actions' => [
                    ['type' => 'whatsapp']
                ],
                'is_active' => true,
            ]);
        }

        $this->loadRules();
    }

    public function loadRules()
    {
        $this->rulesList = AutomationRule::latest()->get();
    }

    public function toggleRule($ruleId)
    {
        $rule = AutomationRule::findOrFail($ruleId);
        $rule->update([
            'is_active' => !$rule->is_active
        ]);

        $this->loadRules();
        $this->dispatch('swal:success', ['message' => 'Statut de la règle modifié avec succès.']);
    }

    public function deleteRule($ruleId)
    {
        $rule = AutomationRule::findOrFail($ruleId);
        $rule->delete();

        $this->loadRules();
        $this->dispatch('swal:success', ['message' => 'Règle de relance supprimée.']);
    }

    public function saveRule()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'event' => 'required|string',
            'daysBeforeExpiry' => 'required|integer|min:1|max:365',
        ]);

        $actions = [];
        if ($this->actionWhatsapp) {
            $actions[] = ['type' => 'whatsapp'];
        }
        if ($this->actionTask) {
            $actions[] = ['type' => 'task'];
        }
        if ($this->actionEmail) {
            $actions[] = ['type' => 'email'];
        }

        AutomationRule::create([
            'name' => $this->name,
            'event' => $this->event,
            'conditions' => [
                'days_before_expiry' => (int) $this->daysBeforeExpiry
            ],
            'actions' => $actions,
            'is_active' => true,
        ]);

        // Reset fields
        $this->name = '';
        $this->daysBeforeExpiry = 30;
        $this->actionWhatsapp = true;
        $this->actionTask = true;
        $this->actionEmail = false;

        $this->loadRules();
        $this->dispatch('swal:success', ['message' => 'Règle d\'automation créée avec succès.']);
    }

    public function render()
    {
        return view('livewire.admin.automation-control')
            ->layout('layouts.app');
    }
}
