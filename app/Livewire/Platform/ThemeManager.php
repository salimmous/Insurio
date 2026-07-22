<?php

namespace App\Livewire\Platform;

use Livewire\Component;
use App\Models\WebsiteTheme;
use App\Models\Tenant;
use Illuminate\Support\Str;

class ThemeManager extends Component
{
    public $themes;
    public $tenants;
    public $searchAgency = '';
    
    // Selection state for Modals
    public $targetThemeId;
    public $targetTheme;
    public $selectedTenantId = '';

    // Modals visibility
    public $showAssignModal = false;
    public $showPreviewModal = false;
    public $showDetailsModal = false;
    public $showEditModal = false;

    // Live Preview Device
    public $previewDevice = 'desktop'; // desktop, tablet, mobile

    // Theme Editing Form
    public $theme_id;
    public $name;
    public $description;
    public $primary_color = '#1E40AF';
    public $secondary_color = '#3B82F6';
    public $bg_color = '#0F172A';
    public $card_bg_color = '#1E293B';
    public $accent_color = '#38BDF8';
    public $is_locked = true;

    public function mount()
    {
        $this->loadThemes();
        $this->tenants = Tenant::all();
    }

    public function loadThemes()
    {
        $this->themes = WebsiteTheme::all();
    }

    // Modal Trigger Actions
    public function openAssignModal($themeId)
    {
        $this->targetThemeId = $themeId;
        $this->targetTheme = WebsiteTheme::findOrFail($themeId);
        $this->selectedTenantId = '';
        $this->searchAgency = '';
        $this->showAssignModal = true;
    }

    public function openPreviewModal($themeId)
    {
        $this->targetThemeId = $themeId;
        $this->targetTheme = WebsiteTheme::findOrFail($themeId);
        $this->previewDevice = 'desktop';
        $this->showPreviewModal = true;
    }

    public function openDetailsModal($themeId)
    {
        $this->targetThemeId = $themeId;
        $this->targetTheme = WebsiteTheme::findOrFail($themeId);
        $this->showDetailsModal = true;
    }

    public function editTheme($id)
    {
        $theme = WebsiteTheme::findOrFail($id);
        $this->theme_id = $theme->id;
        $this->name = $theme->name;
        $this->description = $theme->description;
        $colors = $theme->colors ?? [];
        $this->primary_color = $colors['primary'] ?? '#1E40AF';
        $this->secondary_color = $colors['secondary'] ?? '#3B82F6';
        $this->bg_color = $colors['bg'] ?? '#0F172A';
        $this->card_bg_color = $colors['card_bg'] ?? '#1E293B';
        $this->accent_color = $colors['accent'] ?? '#38BDF8';
        $this->is_locked = (bool)$theme->is_locked;
        $this->showEditModal = true;
    }

    public function toggleLock($id)
    {
        $theme = WebsiteTheme::findOrFail($id);
        $theme->update(['is_locked' => !$theme->is_locked]);
        $this->loadThemes();
        session()->flash('message', 'Statut de verrouillage du thème mis à jour.');
    }

    public function saveTheme()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required',
            'secondary_color' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'colors' => [
                'primary' => $this->primary_color,
                'secondary' => $this->secondary_color,
                'bg' => $this->bg_color,
                'card_bg' => $this->card_bg_color,
                'accent' => $this->accent_color,
                'text' => '#F8FAFC',
            ],
            'is_locked' => $this->is_locked,
        ];

        if ($this->theme_id) {
            WebsiteTheme::findOrFail($this->theme_id)->update($data);
        } else {
            WebsiteTheme::create($data);
        }

        $this->showEditModal = false;
        $this->loadThemes();
        session()->flash('message', 'Thème enregistré avec succès !');
    }

    public function confirmAssignToAgency()
    {
        $this->validate([
            'selectedTenantId' => 'required',
            'targetThemeId' => 'required',
        ]);

        $tenant = Tenant::findOrFail($this->selectedTenantId);
        $tenant->run(function () {
            $config = \App\Models\TenantWebsiteConfig::firstOrCreate([], ['theme_id' => $this->targetThemeId]);
            $config->update(['theme_id' => $this->targetThemeId]);
        });

        $this->showAssignModal = false;
        session()->flash('message', "Le thème '{$this->targetTheme->name}' a été immédiatement appliqué & publié sur l'agence " . ($tenant->name ?? $tenant->id));
    }

    public function render()
    {
        $filteredTenants = $this->tenants;
        if (!empty($this->searchAgency)) {
            $filteredTenants = $this->tenants->filter(function ($t) {
                return str_contains(strtolower($t->name ?? ''), strtolower($this->searchAgency)) ||
                       str_contains(strtolower($t->id ?? ''), strtolower($this->searchAgency));
            });
        }

        return view('livewire.platform.theme-manager', [
            'filteredTenants' => $filteredTenants
        ])->layout('layouts.platform');
    }
}
