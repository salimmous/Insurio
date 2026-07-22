<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\TenantWebsiteConfig;
use App\Models\WebsiteTheme;

class AgencyWebsiteManager extends Component
{
    public $activeTab = 'content';
    public $previewMode = 'desktop';

    // Content Fields
    public $hero_title;
    public $hero_subtitle;
    public $badge_text;
    public $about_title;
    public $about_text;

    // Contact Fields
    public $phone;
    public $whatsapp;
    public $email;
    public $address;
    public $opening_hours;

    // SEO Fields
    public $meta_title;
    public $meta_description;
    public $keywords;
    public $google_analytics;

    // Social Links
    public $facebook;
    public $instagram;
    public $linkedin;

    // Domain
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
                    'hero_title' => 'Protégez ce qui compte le plus',
                    'hero_subtitle' => 'Votre agence d\'assurance de confiance.',
                    'phone' => '+212 5 22 00 00 00',
                    'email' => 'contact@agence-assurance.ma',
                ],
            ]
        );

        $content = $config->content ?? [];
        $seo = $config->seo ?? [];
        $social = $config->social_links ?? [];

        $this->hero_title = $content['hero_title'] ?? '';
        $this->hero_subtitle = $content['hero_subtitle'] ?? '';
        $this->badge_text = $content['badge_text'] ?? '';
        $this->about_title = $content['about_title'] ?? '';
        $this->about_text = $content['about_text'] ?? '';

        $this->phone = $content['phone'] ?? '';
        $this->whatsapp = $content['whatsapp'] ?? '';
        $this->email = $content['email'] ?? '';
        $this->address = $content['address'] ?? '';
        $this->opening_hours = $content['opening_hours'] ?? '';

        $this->meta_title = $seo['meta_title'] ?? '';
        $this->meta_description = $seo['meta_description'] ?? '';
        $this->keywords = $seo['keywords'] ?? '';
        $this->google_analytics = $seo['google_analytics'] ?? '';

        $this->facebook = $social['facebook'] ?? '';
        $this->instagram = $social['instagram'] ?? '';
        $this->linkedin = $social['linkedin'] ?? '';

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
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'badge_text' => $this->badge_text,
            'about_title' => $this->about_title,
            'about_text' => $this->about_text,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'address' => $this->address,
            'opening_hours' => $this->opening_hours,
        ];

        $config->seo = [
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'keywords' => $this->keywords,
            'google_analytics' => $this->google_analytics,
        ];

        $config->social_links = [
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
        ];

        $config->custom_domain = $this->custom_domain;
        $config->is_published = $this->is_published;
        $config->save();

        session()->flash('message', 'Contenu et SEO du site agence sauvegardés avec succès !');
    }

    public function render()
    {
        return view('livewire.admin.agency-website-manager')
            ->layout('layouts.app');
    }
}
