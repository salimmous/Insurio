<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('website_themes')) {
            Schema::create('website_themes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('version')->default('3.0.0');
                $table->string('author')->default('Insurio Studio');
                $table->text('description')->nullable();
                $table->json('colors')->nullable();
                $table->json('typography')->nullable();
                $table->json('components_config')->nullable();
                $table->boolean('is_locked')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Seed 15 Production-Ready Distinct Themes
        $themes = [
            [
                'name' => 'AXA Inspire',
                'slug' => 'axa-inspire',
                'description' => 'Minimal luxe, grandes photographies, fond blanc épuré et typographie audacieuse.',
                'colors' => json_encode(['primary' => '#00008F', 'secondary' => '#1E293B', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#FF0000']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '900']),
                'components_config' => json_encode(['hero_style' => 'axa_bold', 'radius' => 'rounded-none', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'RMA Inspire',
                'slug' => 'rma-inspire',
                'description' => 'Style institutionnel marocain avec carrousel hero, mega-menu et accès direct aux agences.',
                'colors' => json_encode(['primary' => '#1E3A8A', 'secondary' => '#2563EB', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'rma_slider', 'radius' => 'rounded-xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Wafa Inspire',
                'slug' => 'wafa-inspire',
                'description' => 'Tons vert émeraude, cartes arrondies et univers familial chaleureux.',
                'colors' => json_encode(['primary' => '#047857', 'secondary' => '#10B981', 'bg' => '#F0FDF4', 'card_bg' => '#FFFFFF', 'text' => '#064E3B', 'accent' => '#34D399']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'wafa_family', 'radius' => 'rounded-3xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sanlam Inspire',
                'slug' => 'sanlam-inspire',
                'description' => 'Grille d\'information entreprise riche pour particuliers et professionnels.',
                'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0369A1', 'bg' => '#FFFFFF', 'card_bg' => '#F0F9FF', 'text' => '#0C4A6E', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'sanlam_grid', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Allianz Inspire',
                'slug' => 'allianz-inspire',
                'description' => 'Style corporate allemand hyper épuré avec formulaire de simulation intégré.',
                'colors' => json_encode(['primary' => '#00377B', 'secondary' => '#00529C', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0B192C', 'accent' => '#0084FF']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'allianz_form', 'radius' => 'rounded-xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Apple Insurance',
                'slug' => 'apple-insurance',
                'description' => 'Inspiré par Apple : Blanc pur, typographie géante et visuels haute définition.',
                'colors' => json_encode(['primary' => '#000000', 'secondary' => '#1D1D1F', 'bg' => '#FFFFFF', 'card_bg' => '#F5F5F7', 'text' => '#1D1D1F', 'accent' => '#0071E3']),
                'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'apple_clean', 'radius' => 'rounded-3xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Luxury Private',
                'slug' => 'luxury-private',
                'description' => 'Gestion de patrimoine VIP et banques privées avec détails noirs et dorés.',
                'colors' => json_encode(['primary' => '#B45309', 'secondary' => '#D97706', 'bg' => '#FAF9F6', 'card_bg' => '#FFFFFF', 'text' => '#18181B', 'accent' => '#FBBF24']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'luxury_vip', 'radius' => 'rounded-xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Startup Insurance',
                'slug' => 'startup-insurance',
                'description' => 'Inspiré de Stripe & Linear : Design SaaS moderne avec micro-interactions.',
                'colors' => json_encode(['primary' => '#6366F1', 'secondary' => '#4F46E5', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'stripe_saas', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Morocco Premium',
                'slug' => 'morocco-premium',
                'description' => 'Identité marocaine 🇲🇦 avec priorités arabe et motifs zellige.',
                'colors' => json_encode(['primary' => '#9A3412', 'secondary' => '#C2410C', 'bg' => '#FAFAF9', 'card_bg' => '#FFFFFF', 'text' => '#1C1917', 'accent' => '#F97316']),
                'typography' => json_encode(['font' => 'Noto Kufi Arabic', 'heading_weight' => '900']),
                'components_config' => json_encode(['hero_style' => 'moroccan_zellij', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Future AI',
                'slug' => 'future-ai',
                'description' => 'Technologie IA avec grille cyber, néon violet et visuels dynamiques.',
                'colors' => json_encode(['primary' => '#7C3AED', 'secondary' => '#A855F7', 'bg' => '#F5F3FF', 'card_bg' => '#FFFFFF', 'text' => '#1E1B4B', 'accent' => '#C084FC']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'ai_cyber', 'radius' => 'rounded-3xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Healthcare',
                'slug' => 'healthcare',
                'description' => 'Dédié au secteur médical, hôpitaux, médecins et mutuelles de santé.',
                'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0D9488', 'bg' => '#F0F9FF', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'medical_care', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Real Estate',
                'slug' => 'real-estate',
                'description' => 'Assurance promoteurs, projets immobiliers, immeubles et chantiers.',
                'colors' => json_encode(['primary' => '#334155', 'secondary' => '#475569', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#2563EB']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'building_arch', 'radius' => 'rounded-xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Family Protection',
                'slug' => 'family-protection',
                'description' => 'Protection des proches, enfants, avenir et prévoyance familiale.',
                'colors' => json_encode(['primary' => '#E11D48', 'secondary' => '#F43F5E', 'bg' => '#FFF1F2', 'card_bg' => '#FFFFFF', 'text' => '#881337', 'accent' => '#FB7185']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'family_warmth', 'radius' => 'rounded-3xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Corporate Enterprise',
                'slug' => 'corporate-enterprise',
                'description' => 'Inspiré d\'IBM et Microsoft : Design sobre pour risques d\'entreprises.',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#1E293B', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#2563EB']),
                'typography' => json_encode(['font' => 'Inter', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'enterprise_grid', 'radius' => 'rounded-lg', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Marketplace',
                'slug' => 'marketplace',
                'description' => 'Comparateur interactif avec simulateur de devis et filtres intelligents.',
                'colors' => json_encode(['primary' => '#2563EB', 'secondary' => '#3B82F6', 'bg' => '#F8FAFC', 'card_bg' => '#FFFFFF', 'text' => '#0F172A', 'accent' => '#60A5FA']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'comparator_hub', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            \Illuminate\Support\Facades\DB::table('website_themes')->updateOrInsert(
                ['slug' => $theme['slug']],
                array_merge($theme, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('website_themes');
    }
};
