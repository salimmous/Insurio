<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\TenantWebsiteConfig;
use App\Models\WebsiteTheme;

class AgencyWebsiteManager extends Component
{
    public $activeTab = 'general'; // general, hero, contact, social, seo, cookie
    public $previewMode = 'desktop';

    // General Brand Fields
    public $agency_name;
    public $logo_url;
    public $favicon_url;

    // Hero Section Fields
    public $hero_title;
    public $hero_subtitle;
    public $hero_image;
    public $cta_primary_text;
    public $cta_primary_link;
    public $cta_secondary_text;
    public $cta_secondary_link;

    // French & Arabic Content
    public $hero_title_ar;
    public $hero_subtitle_ar;
    public $about_title;
    public $about_text;

    // Contact & Location Fields
    public $phone;
    public $whatsapp;
    public $email;
    public $address;
    public $google_maps_embed;
    public $opening_hours;

    // Social Media Links
    public $facebook;
    public $instagram;
    public $tiktok;
    public $linkedin;

    // SEO & Analytics
    public $meta_title;
    public $meta_description;
    public $og_image;
    public $keywords;
    public $google_analytics;
    public $cookie_banner_enabled = true;
    public $cookie_banner_text;

    // Domain & Publish
    public $custom_domain;
    public $is_published = true;

    public $activeTheme;

    public function mount()
    {
        $config = TenantWebsiteConfig::firstOrCreate(
            [],
            [
                'theme_id' => 1,
                'content' => [
                    'agency_name' => tenant('name') ?? 'Agence d\'Assurance',
                    'hero_title' => 'Protégez ce qui compte le plus avec notre agence',
                    'hero_subtitle' => 'Votre agence d\'assurance agréée et conseil de confiance au Maroc.',
                    'hero_title_ar' => 'احمِ ما يهمك أكثر مع وكالتنا المعتمدة',
                    'hero_subtitle_ar' => 'وكالة تأمين واستشارات احترافية بالمملكة المغربية',
                    'phone' => '+212 5 22 00 00 00',
                    'whatsapp' => '+212 6 00 00 00 00',
                    'email' => 'contact@agence-assurance.ma',
                    'address' => 'Boulevard Zerktouni, Casablanca, Maroc',
                    'opening_hours' => 'Lun - Ven: 08:30 - 18:00 | Sam: 09:00 - 12:30',
                ],
            ]
        );

        $content = $config->content ?? [];
        $seo = $config->seo ?? [];
        $social = $config->social_links ?? [];

        $this->agency_name = $content['agency_name'] ?? (tenant('name') ?? 'Agence d\'Assurance');
        $this->logo_url = $content['logo_url'] ?? '';
        $this->favicon_url = $content['favicon_url'] ?? '';

        $this->hero_title = $content['hero_title'] ?? 'Protégez ce qui compte le plus avec notre agence';
        $this->hero_subtitle = $content['hero_subtitle'] ?? 'Votre agence d\'assurance agréée et conseil de confiance au Maroc.';
        $this->hero_image = $content['hero_image'] ?? '';
        $this->cta_primary_text = $content['cta_primary_text'] ?? 'Demander un Devis';
        $this->cta_primary_link = $content['cta_primary_link'] ?? '#devis';
        $this->cta_secondary_text = $content['cta_secondary_text'] ?? 'Déclarer un Sinistre';
        $this->cta_secondary_link = $content['cta_secondary_link'] ?? '#sinistre';

        $this->hero_title_ar = $content['hero_title_ar'] ?? 'احمِ ما يهمك أكثر مع وكالتنا المعتمدة';
        $this->hero_subtitle_ar = $content['hero_subtitle_ar'] ?? 'وكالة تأمين واستشارات احترافية بالمملكة المغربية';
        $this->about_title = $content['about_title'] ?? 'À Propos de Notre Agence';
        $this->about_text = $content['about_text'] ?? 'Plus de 15 ans d\'expérience dans le courtage et le conseil en assurances.';

        $this->phone = $content['phone'] ?? '+212 5 22 00 00 00';
        $this->whatsapp = $content['whatsapp'] ?? '+212 6 00 00 00 00';
        $this->email = $content['email'] ?? 'contact@agence-assurance.ma';
        $this->address = $content['address'] ?? 'Boulevard Zerktouni, Casablanca, Maroc';
        $this->google_maps_embed = $content['google_maps_embed'] ?? '';
        $this->opening_hours = $content['opening_hours'] ?? 'Lun - Ven: 08:30 - 18:00';

        $this->facebook = $social['facebook'] ?? '';
        $this->instagram = $social['instagram'] ?? '';
        $this->tiktok = $social['tiktok'] ?? '';
        $this->linkedin = $social['linkedin'] ?? '';

        $this->meta_title = $seo['meta_title'] ?? '';
        $this->meta_description = $seo['meta_description'] ?? '';
        $this->og_image = $seo['og_image'] ?? '';
        $this->keywords = $seo['keywords'] ?? '';
        $this->google_analytics = $seo['google_analytics'] ?? '';
        $this->cookie_banner_enabled = (bool)($seo['cookie_banner_enabled'] ?? true);
        $this->cookie_banner_text = $seo['cookie_banner_text'] ?? 'Nous utilisons des cookies pour optimiser votre expérience utilisateur.';

        $this->custom_domain = $config->custom_domain ?? '';
        $this->is_published = (bool)($config->is_published ?? true);

        $this->activeTheme = WebsiteTheme::find($config->theme_id) ?? WebsiteTheme::first();
    }

    public function saveContent()
    {
        $config = TenantWebsiteConfig::first();
        if (!$config) {
            $config = new TenantWebsiteConfig();
        }

        $config->content = [
            'agency_name' => $this->agency_name,
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_image' => $this->hero_image,
            'cta_primary_text' => $this->cta_primary_text,
            'cta_primary_link' => $this->cta_primary_link,
            'cta_secondary_text' => $this->cta_secondary_text,
            'cta_secondary_link' => $this->cta_secondary_link,
            'hero_title_ar' => $this->hero_title_ar,
            'hero_subtitle_ar' => $this->hero_subtitle_ar,
            'about_title' => $this->about_title,
            'about_text' => $this->about_text,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'address' => $this->address,
            'google_maps_embed' => $this->google_maps_embed,
            'opening_hours' => $this->opening_hours,
        ];

        $config->seo = [
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'og_image' => $this->og_image,
            'keywords' => $this->keywords,
            'google_analytics' => $this->google_analytics,
            'cookie_banner_enabled' => $this->cookie_banner_enabled,
            'cookie_banner_text' => $this->cookie_banner_text,
        ];

        $config->social_links = [
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'tiktok' => $this->tiktok,
            'linkedin' => $this->linkedin,
        ];

        $config->custom_domain = $this->custom_domain;
        $config->is_published = $this->is_published;
        $config->save();

        session()->flash('message', '🚀 Paramètres du CMS de l\'agence sauvegardés avec succès !');
    }

    public function render()
    {
        return view('livewire.admin.agency-website-manager')
            ->layout('layouts.app');
    }
}
