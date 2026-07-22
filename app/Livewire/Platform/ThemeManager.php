<?php

namespace App\Livewire\Platform;

use Livewire\Component;
use App\Models\WebsiteTheme;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ThemeManager extends Component
{
    public $themes;
    public $tenants;
    public $searchAgency = '';
    
    // Selection state for Modals
    public $targetThemeId;
    public $targetTheme;
    public $selectedTenantId = '';
    public $selectedTenant;

    // Workflow Steps inside Assign Modal
    public $assignStep = 1;

    // Modals visibility
    public $showAssignModal = false;
    public $showPreviewModal = false;
    public $showDetailsModal = false;
    public $showEditModal = false;

    // Live Preview Device
    public $previewDevice = 'desktop';

    // Theme Editing Form
    public $theme_id;
    public $name;
    public $description;
    public $primary_color = '#1E40AF';
    public $secondary_color = '#3B82F6';
    public $bg_color = '#FFFFFF';
    public $card_bg_color = '#FFFFFF';
    public $accent_color = '#2563EB';
    public $is_locked = true;

    public function mount()
    {
        $this->ensureThirtyThemesSeeded();
        $this->loadThemes();
        $this->tenants = Tenant::all();
    }

    public function ensureThirtyThemesSeeded()
    {
        if (WebsiteTheme::count() < 30) {
            $themes = [
                ['name' => 'Tesla Insurance', 'slug' => 'tesla-insurance', 'description' => 'Futuriste et minimaliste inspiré par Tesla.', 'colors' => json_encode(['primary' => '#E82127', 'secondary' => '#171A20', 'bg' => '#FFFFFF', 'card_bg' => '#F4F4F4', 'text' => '#171A20', 'accent' => '#E82127'])],
                ['name' => 'Apple Enterprise', 'slug' => 'apple-enterprise', 'description' => 'Directives Human Interface d\'Apple.', 'colors' => json_encode(['primary' => '#000000', 'secondary' => '#1D1D1F', 'bg' => '#FFFFFF', 'card_bg' => '#F5F5F7', 'text' => '#1D1D1F', 'accent' => '#0071E3'])],
                ['name' => 'Stripe SaaS', 'slug' => 'stripe-saas', 'description' => 'Inspiration Stripe.com avec grille moderne.', 'colors' => json_encode(['primary' => '#6366F1', 'secondary' => '#4F46E5', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#38BDF8'])],
                ['name' => 'Linear', 'slug' => 'linear', 'description' => 'Design sombre et fluide inspiré de Linear.app.', 'colors' => json_encode(['primary' => '#5E6AD2', 'secondary' => '#1C1D22', 'bg' => '#0B0C0E', 'card_bg' => '#151619', 'text' => '#F7F8F8', 'accent' => '#707EED'])],
                ['name' => 'Notion', 'slug' => 'notion', 'description' => 'Design éditorial minimaliste inspiré de Notion.', 'colors' => json_encode(['primary' => '#000000', 'secondary' => '#37352F', 'bg' => '#FFFFFF', 'card_bg' => '#F7F6F3', 'text' => '#37352F', 'accent' => '#2EAADC'])],
                ['name' => 'Vercel', 'slug' => 'vercel', 'description' => 'Style développeur haute précision inspiré de Vercel.', 'colors' => json_encode(['primary' => '#000000', 'secondary' => '#111111', 'bg' => '#000000', 'card_bg' => '#111111', 'text' => '#FFFFFF', 'accent' => '#0070F3'])],
                ['name' => 'Airbnb', 'slug' => 'airbnb', 'description' => 'Grandes photographies et cartes d\'exploration.', 'colors' => json_encode(['primary' => '#FF385C', 'secondary' => '#E00B41', 'bg' => '#FFFFFF', 'card_bg' => '#F7F7F7', 'text' => '#222222', 'accent' => '#FF385C'])],
                ['name' => 'Booking', 'slug' => 'booking', 'description' => 'Cartes de comparaison rapides style Booking.', 'colors' => json_encode(['primary' => '#003580', 'secondary' => '#00224F', 'bg' => '#F5F5F5', 'card_bg' => '#FFFFFF', 'text' => '#262626', 'accent' => '#FEBB02'])],
                ['name' => 'Porsche Premium', 'slug' => 'porsche-premium', 'description' => 'Ingénierie de luxe et élégance automobile.', 'colors' => json_encode(['primary' => '#D5001C', 'secondary' => '#191919', 'bg' => '#FFFFFF', 'card_bg' => '#F2F2F2', 'text' => '#191919', 'accent' => '#D5001C'])],
                ['name' => 'Mercedes Corporate', 'slug' => 'mercedes-corporate', 'description' => 'Élégance allemande et lignes chromées.', 'colors' => json_encode(['primary' => '#000000', 'secondary' => '#1C1C1C', 'bg' => '#0A0A0A', 'card_bg' => '#141414', 'text' => '#F5F5F5', 'accent' => '#00A3E0'])],
                ['name' => 'Rolex Prestige', 'slug' => 'rolex-prestige', 'description' => 'Luxe noir et or avec typographie d\'exception.', 'colors' => json_encode(['primary' => '#006039', 'secondary' => '#A37E2C', 'bg' => '#0C0D0E', 'card_bg' => '#151719', 'text' => '#F4E5B8', 'accent' => '#C5A059'])],
                ['name' => 'Louis Vuitton', 'slug' => 'louis-vuitton', 'description' => 'Haute couture et prestige éditorial.', 'colors' => json_encode(['primary' => '#1A1817', 'secondary' => '#8C6239', 'bg' => '#FAF9F6', 'card_bg' => '#FFFFFF', 'text' => '#1A1817', 'accent' => '#8C6239'])],
                ['name' => 'Hermes', 'slug' => 'hermes', 'description' => 'Luxe français avec accents orange emblématiques.', 'colors' => json_encode(['primary' => '#F37021', 'secondary' => '#2D2926', 'bg' => '#FFFDF9', 'card_bg' => '#FFFFFF', 'text' => '#2D2926', 'accent' => '#F37021'])],
                ['name' => 'Dior', 'slug' => 'dior', 'description' => 'Éditorial épuré haute définition.', 'colors' => json_encode(['primary' => '#000000', 'secondary' => '#333333', 'bg' => '#FFFFFF', 'card_bg' => '#FAFAFA', 'text' => '#000000', 'accent' => '#000000'])],
                ['name' => 'IBM Enterprise', 'slug' => 'ibm-enterprise', 'description' => 'B2B sérieux et grille d\'ingénierie IBM.', 'colors' => json_encode(['primary' => '#0F62FE', 'secondary' => '#161616', 'bg' => '#F4F4F4', 'card_bg' => '#FFFFFF', 'text' => '#161616', 'accent' => '#0F62FE'])],
                ['name' => 'Microsoft Business', 'slug' => 'microsoft-business', 'description' => 'Style professionnel d\'entreprise Fluent.', 'colors' => json_encode(['primary' => '#0078D4', 'secondary' => '#107C41', 'bg' => '#F3F2F1', 'card_bg' => '#FFFFFF', 'text' => '#201F1E', 'accent' => '#0078D4'])],
                ['name' => 'Oracle Enterprise', 'slug' => 'oracle-enterprise', 'description' => 'Grands tableaux de bord et risques d\'entreprise.', 'colors' => json_encode(['primary' => '#C74634', 'secondary' => '#312D2A', 'bg' => '#FAF8F5', 'card_bg' => '#FFFFFF', 'text' => '#312D2A', 'accent' => '#C74634'])],
                ['name' => 'SAP Corporate', 'slug' => 'sap-corporate', 'description' => 'Architecture de données et assurances SAP.', 'colors' => json_encode(['primary' => '#0A6ED1', 'secondary' => '#1C2D42', 'bg' => '#F7F9FA', 'card_bg' => '#FFFFFF', 'text' => '#1C2D42', 'accent' => '#F0AB00'])],
                ['name' => 'Healthcare Plus', 'slug' => 'healthcare-plus', 'description' => 'Style médical pour cliniques et médecins.', 'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0D9488', 'bg' => '#F0F9FF', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#38BDF8'])],
                ['name' => 'Children Insurance', 'slug' => 'children-insurance', 'description' => 'Assurance éducation et protection enfants.', 'colors' => json_encode(['primary' => '#F59E0B', 'secondary' => '#10B981', 'bg' => '#FFFBEB', 'card_bg' => '#FFFFFF', 'text' => '#78350F', 'accent' => '#EC4899'])],
                ['name' => 'Islamic Finance', 'slug' => 'islamic-finance', 'description' => 'Finance islamique et Takaful au Maroc.', 'colors' => json_encode(['primary' => '#047857', 'secondary' => '#B45309', 'bg' => '#FAFDFB', 'card_bg' => '#FFFFFF', 'text' => '#064E3B', 'accent' => '#D97706'])],
                ['name' => 'Morocco Luxury', 'slug' => 'morocco-luxury', 'description' => 'Architecture et riads de luxe marocains.', 'colors' => json_encode(['primary' => '#9A3412', 'secondary' => '#B45309', 'bg' => '#FAFAF9', 'card_bg' => '#FFFFFF', 'text' => '#1C1917', 'accent' => '#D97706'])],
                ['name' => 'Royal Morocco', 'slug' => 'royal-morocco', 'description' => 'Tradition marocaine prestige et artisanat.', 'colors' => json_encode(['primary' => '#854D0E', 'secondary' => '#A16207', 'bg' => '#FEFCE8', 'card_bg' => '#FFFFFF', 'text' => '#713F12', 'accent' => '#CA8A04'])],
                ['name' => 'Glassmorphism Future', 'slug' => 'glassmorphism-future', 'description' => 'Effet verre dépoli et flou d\'arrière-plan.', 'colors' => json_encode(['primary' => '#6366F1', 'secondary' => '#818CF8', 'bg' => '#090D16', 'card_bg' => '#1E1B4B', 'text' => '#EEF2FF', 'accent' => '#38BDF8'])],
                ['name' => 'Neo Brutalism', 'slug' => 'neo-brutalism', 'description' => 'Bordures noires nettes et ombres solides.', 'colors' => json_encode(['primary' => '#FF6B6B', 'secondary' => '#4ECDC4', 'bg' => '#FFE66D', 'card_bg' => '#FFFFFF', 'text' => '#000000', 'accent' => '#1A535C'])],
                ['name' => 'Bento UI', 'slug' => 'bento-ui', 'description' => 'Grille Bento moderne style Apple & Framer.', 'colors' => json_encode(['primary' => '#18181B', 'secondary' => '#27272A', 'bg' => '#F4F4F5', 'card_bg' => '#FFFFFF', 'text' => '#18181B', 'accent' => '#6366F1'])],
                ['name' => 'Material Design 3', 'slug' => 'material-design-3', 'description' => 'Standards Google Material You / Material 3.', 'colors' => json_encode(['primary' => '#6750A4', 'secondary' => '#625B71', 'bg' => '#FEF7FF', 'card_bg' => '#F3EDF7', 'text' => '#1D1B20', 'accent' => '#7D5260'])],
                ['name' => 'Fluent Design', 'slug' => 'fluent-design', 'description' => 'Design Microsoft Fluent 2 avec ombres douces.', 'colors' => json_encode(['primary' => '#0078D4', 'secondary' => '#2B88D8', 'bg' => '#F5F5F5', 'card_bg' => '#FFFFFF', 'text' => '#242424', 'accent' => '#005A9E'])],
                ['name' => 'Cyber Insurance', 'slug' => 'cyber-insurance', 'description' => 'Protection risques cybernétiques & piratage.', 'colors' => json_encode(['primary' => '#10B981', 'secondary' => '#065F46', 'bg' => '#022C22', 'card_bg' => '#064E3B', 'text' => '#ECFDF5', 'accent' => '#34D399'])],
                ['name' => 'AI Insurance 2035', 'slug' => 'ai-insurance-2035', 'description' => 'Futur 2035 avec souscription algorithmique.', 'colors' => json_encode(['primary' => '#A855F7', 'secondary' => '#9333EA', 'bg' => '#0F172A', 'card_bg' => '#1E293B', 'text' => '#F8FAFC', 'accent' => '#C084FC'])],
            ];

            foreach ($themes as $t) {
                DB::table('website_themes')->updateOrInsert(
                    ['slug' => $t['slug']],
                    array_merge($t, [
                        'version' => '3.0.0',
                        'author' => 'Insurio Studio',
                        'is_locked' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }
    }

    public function loadThemes()
    {
        $this->themes = WebsiteTheme::all();
    }

    public function openAssignModal($themeId)
    {
        $this->targetThemeId = $themeId;
        $this->targetTheme = WebsiteTheme::findOrFail($themeId);
        $this->selectedTenantId = '';
        $this->selectedTenant = null;
        $this->searchAgency = '';
        $this->assignStep = 1;
        $this->showAssignModal = true;
    }

    public function selectAgencyForAssign($tenantId)
    {
        $this->selectedTenantId = $tenantId;
        $this->selectedTenant = Tenant::find($tenantId);
        $this->assignStep = 2;
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
        $this->bg_color = $colors['bg'] ?? '#FFFFFF';
        $this->card_bg_color = $colors['card_bg'] ?? '#FFFFFF';
        $this->accent_color = $colors['accent'] ?? '#2563EB';
        $this->is_locked = (bool)$theme->is_locked;
        $this->showEditModal = true;
    }

    public function toggleLock($id)
    {
        $theme = WebsiteTheme::findOrFail($id);
        $theme->update(['is_locked' => !$theme->is_locked]);
        $this->loadThemes();
        session()->flash('message', 'Statut de verrouillage mis à jour.');
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
                'text' => '#0F172A',
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
        session()->flash('message', 'Thème sauvegardé avec succès !');
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
        session()->flash('message', "🚀 Le thème '{$this->targetTheme->name}' a été appliqué live sur l'agence " . ($tenant->name ?? $tenant->id));
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
