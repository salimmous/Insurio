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
    public $assignStep = 1; // 1: Select Agency, 2: Preview with Agency Info, 3: Confirm

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
    public $bg_color = '#FFFFFF';
    public $card_bg_color = '#FFFFFF';
    public $accent_color = '#2563EB';
    public $is_locked = true;

    public function mount()
    {
        $this->ensureFifteenThemesSeeded();
        $this->loadThemes();
        $this->tenants = Tenant::all();
    }

    public function ensureFifteenThemesSeeded()
    {
        if (WebsiteTheme::count() < 15) {
            $themes = [
                [
                    'name' => 'AXA Inspire',
                    'slug' => 'axa-inspire',
                    'description' => 'Minimal luxe, grandes photographies, fond blanc épuré et typographie audacieuse.',
                    'colors' => json_encode(['primary' => '#00008F', 'secondary' => '#1E293B', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#FF0000']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '900']),
                    'components_config' => json_encode(['hero_style' => 'axa_bold', 'radius' => 'rounded-none', 'dark' => false]),
                ],
                [
                    'name' => 'RMA Inspire',
                    'slug' => 'rma-inspire',
                    'description' => 'Style institutionnel marocain avec carrousel hero, mega-menu et accès direct aux agences.',
                    'colors' => json_encode(['primary' => '#1E3A8A', 'secondary' => '#2563EB', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'rma_slider', 'radius' => 'rounded-xl', 'dark' => false]),
                ],
                [
                    'name' => 'Wafa Inspire',
                    'slug' => 'wafa-inspire',
                    'description' => 'Tons vert émeraude, cartes arrondies et univers familial chaleureux.',
                    'colors' => json_encode(['primary' => '#047857', 'secondary' => '#10B981', 'bg' => '#F0FDF4', 'card_bg' => '#FFFFFF', 'text' => '#064E3B', 'accent' => '#34D399']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                    'components_config' => json_encode(['hero_style' => 'wafa_family', 'radius' => 'rounded-3xl', 'dark' => false]),
                ],
                [
                    'name' => 'Sanlam Inspire',
                    'slug' => 'sanlam-inspire',
                    'description' => 'Grille d\'information entreprise riche pour particuliers et professionnels.',
                    'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0369A1', 'bg' => '#FFFFFF', 'card_bg' => '#F0F9FF', 'text' => '#0C4A6E', 'accent' => '#38BDF8']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'sanlam_grid', 'radius' => 'rounded-2xl', 'dark' => false]),
                ],
                [
                    'name' => 'Allianz Inspire',
                    'slug' => 'allianz-inspire',
                    'description' => 'Style corporate allemand hyper épuré avec formulaire de simulation intégré.',
                    'colors' => json_encode(['primary' => '#00377B', 'secondary' => '#00529C', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0B192C', 'accent' => '#0084FF']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'allianz_form', 'radius' => 'rounded-xl', 'dark' => false]),
                ],
                [
                    'name' => 'Apple Insurance',
                    'slug' => 'apple-insurance',
                    'description' => 'Inspiré par Apple : Blanc pur, typographie géante et visuels haute définition.',
                    'colors' => json_encode(['primary' => '#000000', 'secondary' => '#1D1D1F', 'bg' => '#FFFFFF', 'card_bg' => '#F5F5F7', 'text' => '#1D1D1F', 'accent' => '#0071E3']),
                    'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '700']),
                    'components_config' => json_encode(['hero_style' => 'apple_clean', 'radius' => 'rounded-3xl', 'dark' => false]),
                ],
                [
                    'name' => 'Luxury Private',
                    'slug' => 'luxury-private',
                    'description' => 'Gestion de patrimoine VIP et banques privées avec détails noirs et dorés.',
                    'colors' => json_encode(['primary' => '#B45309', 'secondary' => '#D97706', 'bg' => '#FAF9F6', 'card_bg' => '#FFFFFF', 'text' => '#18181B', 'accent' => '#FBBF24']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'luxury_vip', 'radius' => 'rounded-xl', 'dark' => false]),
                ],
                [
                    'name' => 'Startup Insurance',
                    'slug' => 'startup-insurance',
                    'description' => 'Inspiré de Stripe & Linear : Design SaaS moderne avec micro-interactions.',
                    'colors' => json_encode(['primary' => '#6366F1', 'secondary' => '#4F46E5', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                    'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'stripe_saas', 'radius' => 'rounded-2xl', 'dark' => false]),
                ],
                [
                    'name' => 'Morocco Premium',
                    'slug' => 'morocco-premium',
                    'description' => 'Identité marocaine 🇲🇦 avec priorités arabe et motifs zellige.',
                    'colors' => json_encode(['primary' => '#9A3412', 'secondary' => '#C2410C', 'bg' => '#FAFAF9', 'card_bg' => '#FFFFFF', 'text' => '#1C1917', 'accent' => '#F97316']),
                    'typography' => json_encode(['font' => 'Noto Kufi Arabic', 'heading_weight' => '900']),
                    'components_config' => json_encode(['hero_style' => 'moroccan_zellij', 'radius' => 'rounded-2xl', 'dark' => false]),
                ],
                [
                    'name' => 'Future AI',
                    'slug' => 'future-ai',
                    'description' => 'Technologie IA avec grille cyber, néon violet et visuels dynamiques.',
                    'colors' => json_encode(['primary' => '#7C3AED', 'secondary' => '#A855F7', 'bg' => '#F5F3FF', 'card_bg' => '#FFFFFF', 'text' => '#1E1B4B', 'accent' => '#C084FC']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'ai_cyber', 'radius' => 'rounded-3xl', 'dark' => false]),
                ],
                [
                    'name' => 'Healthcare',
                    'slug' => 'healthcare',
                    'description' => 'Dédié au secteur médical, hôpitaux, médecins et mutuelles de santé.',
                    'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0D9488', 'bg' => '#F0F9FF', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                    'components_config' => json_encode(['hero_style' => 'medical_care', 'radius' => 'rounded-2xl', 'dark' => false]),
                ],
                [
                    'name' => 'Real Estate',
                    'slug' => 'real-estate',
                    'description' => 'Assurance promoteurs, projets immobiliers, immeubles et chantiers.',
                    'colors' => json_encode(['primary' => '#334155', 'secondary' => '#475569', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#2563EB']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'building_arch', 'radius' => 'rounded-xl', 'dark' => false]),
                ],
                [
                    'name' => 'Family Protection',
                    'slug' => 'family-protection',
                    'description' => 'Protection des proches, enfants, avenir et prévoyance familiale.',
                    'colors' => json_encode(['primary' => '#E11D48', 'secondary' => '#F43F5E', 'bg' => '#FFF1F2', 'card_bg' => '#FFFFFF', 'text' => '#881337', 'accent' => '#FB7185']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                    'components_config' => json_encode(['hero_style' => 'family_warmth', 'radius' => 'rounded-3xl', 'dark' => false]),
                ],
                [
                    'name' => 'Corporate Enterprise',
                    'slug' => 'corporate-enterprise',
                    'description' => 'Inspiré d\'IBM et Microsoft : Design sobre pour risques d\'entreprises.',
                    'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#1E293B', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#2563EB']),
                    'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'enterprise_grid', 'radius' => 'rounded-lg', 'dark' => false]),
                ],
                [
                    'name' => 'Marketplace',
                    'slug' => 'marketplace',
                    'description' => 'Comparateur interactif avec simulateur de devis et filtres intelligents.',
                    'colors' => json_encode(['primary' => '#2563EB', 'secondary' => '#3B82F6', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#60A5FA']),
                    'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                    'components_config' => json_encode(['hero_style' => 'comparator_hub', 'radius' => 'rounded-2xl', 'dark' => false]),
                ],
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

    // Workflow Assignment Actions
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
        session()->flash('message', "🚀 Le thème structural '{$this->targetTheme->name}' a été appliqué et publié live sur l'agence " . ($tenant->name ?? $tenant->id));
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
