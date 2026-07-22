<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->string('author')->default('Insurio Design System');
            $table->text('description')->nullable();
            $table->json('colors')->nullable(); // primary, secondary, bg, card_bg, text, accent
            $table->json('typography')->nullable(); // font_family, headings, body_size
            $table->json('components_config')->nullable(); // hero_style, card_style, navbar_style, footer_style
            $table->boolean('is_locked')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default Themes
        $themes = [
            [
                'name' => 'Corporate Blue',
                'slug' => 'corporate-blue',
                'description' => 'Style d\'assurance institutionnel bleu élégant et structuré',
                'colors' => json_encode(['primary' => '#1E40AF', 'secondary' => '#3B82F6', 'bg' => '#0F172A', 'card_bg' => '#1E293B', 'text' => '#F8FAFC', 'accent' => '#38BDF8']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'gradient', 'card_style' => 'glass', 'navbar' => 'sticky']),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Executive Dark',
                'slug' => 'executive-dark',
                'description' => 'Design sombre ultra-premium pour agences de courtage haut de gamme',
                'colors' => json_encode(['primary' => '#0F172A', 'secondary' => '#1E293B', 'bg' => '#020617', 'card_bg' => '#0F172A', 'text' => '#F8FAFC', 'accent' => '#2DD4BF']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'dark_glow', 'card_style' => 'bordered', 'navbar' => 'transparent']),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Modern Emerald',
                'slug' => 'modern-emerald',
                'description' => 'Style vert émeraude dynamique axé sur la sérénité et la réactivité',
                'colors' => json_encode(['primary' => '#0D9488', 'secondary' => '#14B8A6', 'bg' => '#064E3B', 'card_bg' => '#047857', 'text' => '#ECFDF5', 'accent' => '#34D399']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '700']),
                'components_config' => json_encode(['hero_style' => 'emerald_flow', 'card_style' => 'soft', 'navbar' => 'sticky']),
                'is_locked' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Luxury Gold',
                'slug' => 'luxury-gold',
                'description' => 'Design dore prestige pour banques et assurances de patrimoine',
                'colors' => json_encode(['primary' => '#B45309', 'secondary' => '#D97706', 'bg' => '#18181B', 'card_bg' => '#27272A', 'text' => '#FAFAFA', 'accent' => '#FBBF24']),
                'typography' => json_encode(['font' => 'Plus Jakarta Sans', 'heading_weight' => '800']),
                'components_config' => json_encode(['hero_style' => 'gold_prestige', 'card_style' => 'gold_border', 'navbar' => 'sticky']),
                'is_locked' => true,
                'is_active' => true,
            ],
        ];

        foreach ($themes as $theme) {
            \Illuminate\Support\Facades\DB::table('website_themes')->insert(array_merge($theme, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('website_themes');
    }
};
