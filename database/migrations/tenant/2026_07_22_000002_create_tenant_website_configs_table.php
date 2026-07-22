<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_website_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theme_id')->default(1);
            $table->json('content')->nullable(); // hero_title, hero_subtitle, about_text, phone, email, whatsapp, address, etc.
            $table->json('seo')->nullable(); // meta_title, meta_description, keywords, og_image, google_analytics
            $table->json('social_links')->nullable(); // facebook, instagram, linkedin
            $table->string('custom_domain')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Insert default initial config for tenant
        \Illuminate\Support\Facades\DB::table('tenant_website_configs')->insert([
            'theme_id' => 1,
            'content' => json_encode([
                'hero_title' => 'Protégez ce qui compte le plus',
                'hero_subtitle' => 'Une agence moderne, réactive et à votre écoute pour vous proposer les meilleures solutions d\'assurance.',
                'badge_text' => '🛡️ Assurances professionnelles au Maroc',
                'phone' => '+212 5 22 00 00 00',
                'whatsapp' => '+212 6 00 00 00 00',
                'email' => 'contact@agence-assurance.ma',
                'address' => 'Boulevard Anfa, Casablanca, Maroc',
                'opening_hours' => 'Lun - Ven: 08:30 - 18:00',
                'about_title' => 'Votre partenaire de confiance en assurance',
                'about_text' => 'Depuis plus de 15 ans, nous accompagnons les particuliers et entreprises avec rigueur et proximité.',
            ]),
            'seo' => json_encode([
                'meta_title' => 'Agence d\'Assurance Officielle | Conseils & Polices',
                'meta_description' => 'Découvrez nos solutions d\'assurance Auto, MRH, Santé et Professionnelle.',
                'keywords' => 'assurance, auto, casablanca, maroc, devis assurance',
            ]),
            'social_links' => json_encode([
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
            ]),
            'is_published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_website_configs');
    }
};
