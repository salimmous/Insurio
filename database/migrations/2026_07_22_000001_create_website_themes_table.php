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
                $table->string('version')->default('2.0.0');
                $table->string('author')->default('Insurio Design System');
                $table->text('description')->nullable();
                $table->json('colors')->nullable(); // primary, secondary, bg, card_bg, text, accent
                $table->json('typography')->nullable(); // font_family, headings, body_size
                $table->json('components_config')->nullable(); // hero_style, card_style, navbar_style, footer_style, radius
                $table->boolean('is_locked')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Seed 10 Production-Ready Distinct Structural Themes
        $themes = [
            [
                'name' => 'RMA Corporate',
                'slug' => 'rma-corporate',
                'description' => 'Inspiré par RMA Assurance : Style institutionnel bleu avec top-bar et Mega Menu',
                'colors' => json_encode(['primary' => '#1E3A8A', 'secondary' => '#2563EB', 'bg' => '#0F172A', 'card_bg' => '#1E293B', 'text' => '#F8FAFC', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'rma_split', 'card_style' => 'corporate_border', 'navbar' => 'topbar_sticky', 'radius' => 'rounded-xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'AXA Minimal',
                'slug' => 'axa-minimal',
                'description' => 'Inspiré par AXA Maroc : Design épuré, typographie audacieuse et grands espaces blancs',
                'colors' => json_encode(['primary' => '#00008F', 'secondary' => '#FF0000', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#00008F']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '900']),
                'components_config' => json_encode(['hero_style' => 'axa_bold', 'card_style' => 'clean_line', 'navbar' => 'minimal_white', 'radius' => 'rounded-none', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Wafa Family',
                'slug' => 'wafa-family',
                'description' => 'Inspiré par Wafa Assurance : Tons vert émeraude, cartes douces et univers familial',
                'colors' => json_encode(['primary' => '#047857', 'secondary' => '#10B981', 'bg' => '#064E3B', 'card_bg' => '#065F46', 'text' => '#ECFDF5', 'accent' => '#34D399']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'wafa_wave', 'card_style' => 'soft_rounded', 'navbar' => 'emerald_nav', 'radius' => 'rounded-3xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sanlam Corporate',
                'slug' => 'sanlam-corporate',
                'description' => 'Inspiré par Sanlam : Grille d\'information riche pour entreprises et particuliers',
                'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0369A1', 'bg' => '#0C4A6E', 'card_bg' => '#075985', 'text' => '#F0F9FF', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'sanlam_grid', 'card_style' => 'blue_accent', 'navbar' => 'sanlam_blue', 'radius' => 'rounded-2xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Allianz Business',
                'slug' => 'allianz-business',
                'description' => 'Inspiré par Allianz : Formulaire de simulation intégré directement dans le Hero',
                'colors' => json_encode(['primary' => '#00377B', 'secondary' => '#00529C', 'bg' => '#0B192C', 'card_bg' => '#1E3E62', 'text' => '#F8FAFC', 'accent' => '#0084FF']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'allianz_hero_form', 'card_style' => 'navy_box', 'navbar' => 'navy_nav', 'radius' => 'rounded-xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Executive Black',
                'slug' => 'executive-black',
                'description' => 'Sombre ultra-premium avec navbar glassmorphic flottante et effet néon',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#1E293B', 'bg' => '#020617', 'card_bg' => '#0F172A', 'text' => '#F8FAFC', 'accent' => '#2DD4BF']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'dark_glow', 'card_style' => 'glass_dark', 'navbar' => 'floating_pill', 'radius' => 'rounded-full', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Luxury Gold',
                'slug' => 'luxury-gold',
                'description' => 'Banque Privée & Gestion de Patrimoine VIP avec bordures dorées',
                'colors' => json_encode(['primary' => '#B45309', 'secondary' => '#D97706', 'bg' => '#18181B', 'card_bg' => '#27272A', 'text' => '#FAFAFA', 'accent' => '#FBBF24']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'gold_prestige', 'card_style' => 'gold_framed', 'navbar' => 'gold_bar', 'radius' => 'rounded-xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Minimal White',
                'slug' => 'minimal-white',
                'description' => 'Style Apple minimaliste avec ombres douces et typographie haute définition',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#475569', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#2563EB']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'apple_clean', 'card_style' => 'soft_shadow', 'navbar' => 'light_sticky', 'radius' => 'rounded-3xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Moroccan Prestige',
                'slug' => 'moroccan-prestige',
                'description' => 'Priorité à la langue Arabe 🇲🇦 avec accents zellige et terre cuite marocaine',
                'colors' => json_encode(['primary' => '#9A3412', 'secondary' => '#C2410C', 'bg' => '#1C1917', 'card_bg' => '#292524', 'text' => '#FAFAF9', 'accent' => '#F97316']),
                'typography' => json_encode(['font' => 'Noto Kufi Arabic', 'heading_weight' => '900']),
                'components_config' => json_encode(['hero_style' => 'zellij_pattern', 'card_style' => 'moroccan_card', 'navbar' => 'moroccan_nav', 'radius' => 'rounded-2xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Future AI',
                'slug' => 'future-ai',
                'description' => 'Plateforme SaaS IA futuriste avec grille cyber et lueurs violettes',
                'colors' => json_encode(['primary' => '#7C3AED', 'secondary' => '#A855F7', 'bg' => '#1E1B4B', 'card_bg' => '#312E81', 'text' => '#F5F3FF', 'accent' => '#C084FC']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'cyber_grid', 'card_style' => 'neon_card', 'navbar' => 'cyber_nav', 'radius' => 'rounded-3xl', 'dark' => true]),
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
