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
    public $selectedTenantId = '';
    public $selectedThemeId = 1;

    // Theme editing properties
    public $theme_id;
    public $name;
    public $description;
    public $primary_color = '#1E40AF';
    public $secondary_color = '#3B82F6';
    public $bg_color = '#0F172A';
    public $card_bg_color = '#1E293B';
    public $accent_color = '#38BDF8';
    public $is_locked = true;
    public $showModal = false;

    public function mount()
    {
        $this->loadThemes();
        $this->tenants = Tenant::all();
    }

    public function loadThemes()
    {
        $this->themes = WebsiteTheme::all();
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
        $this->showModal = true;
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

        $this->showModal = false;
        $this->loadThemes();
        session()->flash('message', 'Thème enregistré avec succès !');
    }

    public function assignThemeToTenant()
    {
        $this->validate([
            'selectedTenantId' => 'required',
            'selectedThemeId' => 'required',
        ]);

        $tenant = Tenant::findOrFail($this->selectedTenantId);
        $tenant->run(function () {
            $config = \App\Models\TenantWebsiteConfig::firstOrCreate([], ['theme_id' => $this->selectedThemeId]);
            $config->update(['theme_id' => $this->selectedThemeId]);
        });

        session()->flash('message', "Thème appliqué immédiatement à l'agence " . $tenant->name);
    }

    public function render()
    {
        return view('livewire.platform.theme-manager')
            ->layout('layouts.platform');
    }
}
