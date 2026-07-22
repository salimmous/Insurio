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
                $table->string('version')->default('1.0.0');
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

        // Seed 10 Production-Ready Distinct Themes
        $themes = [
            [
                'name' => 'Corporate Blue',
                'slug' => 'corporate-blue',
                'description' => 'Style d\'assurance institutionnel bleu élégant et structuré',
                'colors' => json_encode(['primary' => '#1E40AF', 'secondary' => '#3B82F6', 'bg' => '#0F172A', 'card_bg' => '#1E293B', 'text' => '#F8FAFC', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'gradient', 'card_style' => 'glass', 'navbar' => 'sticky', 'radius' => 'rounded-2xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Executive Dark',
                'slug' => 'executive-dark',
                'description' => 'Design sombre ultra-premium pour agences de courtage haut de gamme',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#1E293B', 'bg' => '#020617', 'card_bg' => '#0F172A', 'text' => '#F8FAFC', 'accent' => '#2DD4BF']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'dark_glow', 'card_style' => 'bordered', 'navbar' => 'transparent', 'radius' => 'rounded-3xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Minimal White',
                'slug' => 'minimal-white',
                'description' => 'Design minimaliste épuré et moderne style Apple',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#475569', 'bg' => '#FFFFFF', 'card_bg' => '#F8FAFC', 'text' => '#0F172A', 'accent' => '#2563EB']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'clean_white', 'card_style' => 'soft_shadow', 'navbar' => 'light_sticky', 'radius' => 'rounded-2xl', 'dark' => false]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Emerald Insurance',
                'slug' => 'emerald-insurance',
                'description' => 'Style vert émeraude dynamique axé sur la sérénité et la réactivité',
                'colors' => json_encode(['primary' => '#0D9488', 'secondary' => '#14B8A6', 'bg' => '#064E3B', 'card_bg' => '#047857', 'text' => '#ECFDF5', 'accent' => '#34D399']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'emerald_flow', 'card_style' => 'soft', 'navbar' => 'sticky', 'radius' => 'rounded-3xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Royal Gold',
                'slug' => 'royal-gold',
                'description' => 'Design doré prestige pour banques et assurances de patrimoine VIP',
                'colors' => json_encode(['primary' => '#B45309', 'secondary' => '#D97706', 'bg' => '#18181B', 'card_bg' => '#27272A', 'text' => '#FAFAFA', 'accent' => '#FBBF24']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'gold_prestige', 'card_style' => 'gold_border', 'navbar' => 'sticky', 'radius' => 'rounded-2xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Glass Enterprise',
                'slug' => 'glass-enterprise',
                'description' => 'Effet glassmorphism SaaS moderne avec flou d\'arrière-plan',
                'colors' => json_encode(['primary' => '#6366F1', 'secondary' => '#818CF8', 'bg' => '#090D16', 'card_bg' => '#1E1B4B', 'text' => '#EEF2FF', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'glassmorphism', 'card_style' => 'frosted_glass', 'navbar' => 'glass_nav', 'radius' => 'rounded-3xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Ocean Professional',
                'slug' => 'ocean-professional',
                'description' => 'Bleu océan profond inspiré des grands cabinets financiers européens',
                'colors' => json_encode(['primary' => '#0284C7', 'secondary' => '#0369A1', 'bg' => '#0C4A6E', 'card_bg' => '#075985', 'text' => '#F0F9FF', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'ocean_depth', 'card_style' => 'ocean_border', 'navbar' => 'sticky', 'radius' => 'rounded-2xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Future Insurance',
                'slug' => 'future-insurance',
                'description' => 'Design futuriste IA avec néon violet et grille technologique',
                'colors' => json_encode(['primary' => '#7C3AED', 'secondary' => '#A855F7', 'bg' => '#1E1B4B', 'card_bg' => '#312E81', 'text' => '#F5F3FF', 'accent' => '#C084FC']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'future_cyber', 'card_style' => 'neon_border', 'navbar' => 'cyber_nav', 'radius' => 'rounded-3xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Executive Platinum',
                'slug' => 'executive-platinum',
                'description' => 'Style argent platine élégant pour grandes entreprises',
                'colors' => json_encode(['primary' => '#334155', 'secondary' => '#64748B', 'bg' => '#0F172A', 'card_bg' => '#1E293B', 'text' => '#F8FAFC', 'accent' => '#94A3B8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'platinum_sharp', 'card_style' => 'platinum_border', 'navbar' => 'sticky', 'radius' => 'rounded-xl', 'dark' => true]),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Classic Morocco',
                'slug' => 'classic-morocco',
                'description' => 'Design adapté aux agences marocaines (Accents zellige & terre cuite)',
                'colors' => json_encode(['primary' => '#9A3412', 'secondary' => '#C2410C', 'bg' => '#1C1917', 'card_bg' => '#292524', 'text' => '#FAFAF9', 'accent' => '#F97316']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'morocco_warm', 'card_style' => 'zellij_accent', 'navbar' => 'sticky', 'radius' => 'rounded-2xl', 'dark' => true]),
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
