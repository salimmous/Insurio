<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\TenantWebsiteConfig;
use App\Models\WebsiteTheme;

class AgencyWebsiteManager extends Component
{
    public $activeTab = 'content';
    public $previewMode = 'desktop';

    // French Content Fields
    public $hero_title;
    public $hero_subtitle;
    public $badge_text;
    public $about_title;
    public $about_text;

    // Arabic Content Fields
    public $hero_title_ar;
    public $hero_subtitle_ar;
    public $badge_text_ar;
    public $about_title_ar;
    public $about_text_ar;

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
                    'hero_subtitle' => 'Votre agence d\'assurance de confiance au Maroc.',
                    'hero_title_ar' => 'احمِ ما يهمك أكثر مع وكالتنا المعتمدة',
                    'hero_subtitle_ar' => 'وكالة تأمين واستشارات احترافية في المغرب',
                    'badge_text' => '🛡️ Assurances professionnelles au Maroc',
                    'badge_text_ar' => '🛡️ خدمات التأمين والاستشارات بالمغرب',
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

        $this->hero_title = $content['hero_title'] ?? 'Protégez ce qui compte le plus';
        $this->hero_subtitle = $content['hero_subtitle'] ?? 'Votre agence d\'assurance de confiance au Maroc.';
        $this->badge_text = $content['badge_text'] ?? '🛡️ Assurances professionnelles au Maroc';
        $this->about_title = $content['about_title'] ?? 'À Propos de Notre Agence';
        $this->about_text = $content['about_text'] ?? 'Plus de 15 ans d\'expérience dans le courtage et le conseil en assurances.';

        $this->hero_title_ar = $content['hero_title_ar'] ?? 'احمِ ما يهمك أكثر مع وكالتنا المعتمدة';
        $this->hero_subtitle_ar = $content['hero_subtitle_ar'] ?? 'وكالة تأمين واستشارات احترافية في المغرب.';
        $this->badge_text_ar = $content['badge_text_ar'] ?? '🛡️ خدمات التأمين والاستشارات بالمغرب';
        $this->about_title_ar = $content['about_title_ar'] ?? 'حول وكالتنا';
        $this->about_text_ar = $content['about_text_ar'] ?? 'أكثر من 15 سنة من الخبرة في مجال التأمين والاستشارة بالمغرب.';

        $this->phone = $content['phone'] ?? '+212 5 22 00 00 00';
        $this->whatsapp = $content['whatsapp'] ?? '+212 6 00 00 00 00';
        $this->email = $content['email'] ?? 'contact@agence-assurance.ma';
        $this->address = $content['address'] ?? 'Boulevard Zerktouni, Casablanca, Maroc';
        $this->opening_hours = $content['opening_hours'] ?? 'Lun - Ven: 08:30 - 18:00';

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
            'hero_title_ar' => $this->hero_title_ar,
            'hero_subtitle_ar' => $this->hero_subtitle_ar,
            'badge_text_ar' => $this->badge_text_ar,
            'about_title_ar' => $this->about_title_ar,
            'about_text_ar' => $this->about_text_ar,
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

        session()->flash('message', 'Contenu du site web et paramètres FR/AR sauvegardés avec succès !');
    }

    public function render()
    {
        return view('livewire.admin.agency-website-manager')
            ->layout('layouts.app');
    }
}
