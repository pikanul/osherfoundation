<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\NewsCategory;
use App\Models\ProjectCategory;
use Inertia\Inertia;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$bannerImage, $bannerImageIds] = settings('banner_image', 998, 1);
        $bannerImages = collect(explode(',', (string) $bannerImageIds))
            ->filter(fn ($id) => trim($id) !== '')
            ->map(fn ($id) => dynamic_asset($id))
            ->values()
            ->all();
        $headerSetting = function (string $name, string $fallback) {
            $value = settings($name, 302);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        [$headerLogo, $headerLogoId] = settings('header_logo_image', 302, 1);

        return [
            ...parent::share($request),
            'app_url' => url(''),
            'base_url' => $request->getBaseUrl(),
            'newsscategories' => NewsCategory::select('id', 'name', 'slug')->where('status', 1)->get(),
            'projectcategories' => ProjectCategory::select('id', 'name', 'slug')->where('status', 1)->get(),
            'pages' => \App\Models\Page::select('id', 'name', 'slug')->where('status', 1)->get(),
            'img' => settings('app_image', 9),
            'header_settings' => [
                'logo' => is_numeric($headerLogoId) && (int) $headerLogoId > 0 ? $headerLogo : settings('app_image', 9),
                'tagline' => $headerSetting('header_tagline_text', settings('app_tagline', 9)),
                'phone' => $headerSetting('header_phone_text', settings('app_tel', 9)),
                'email' => $headerSetting('header_email_text', settings('app_email', 9)),
                'search_text' => $headerSetting('header_search_text', 'Search'),
                'partner_button_text' => $headerSetting('header_partner_button_text', 'Partner With Us'),
                'partner_button_link' => $headerSetting('header_partner_button_link', '/ProjectPartners'),
                'nav_home_text' => $headerSetting('header_nav_home_text', 'Home'),
                'nav_about_text' => $headerSetting('header_nav_about_text', 'About OSHE'),
                'nav_work_text' => $headerSetting('header_nav_work_text', 'Our Work'),
                'nav_programs_text' => $headerSetting('header_nav_programs_text', 'Programs & Projects'),
                'nav_research_text' => $headerSetting('header_nav_research_text', 'Research & Publications'),
                'nav_media_text' => $headerSetting('header_nav_media_text', 'Media Center'),
                'nav_partners_text' => $headerSetting('header_nav_partners_text', 'Partners'),
                'nav_news_events_text' => $headerSetting('header_nav_news_events_text', 'News & Events'),
                'nav_contact_text' => $headerSetting('header_nav_contact_text', 'Contact Us'),
                'top_bar_color' => $headerSetting('header_top_bar_color', '#064a86'),
                'top_text_color' => $headerSetting('header_top_text_color', '#ffffff'),
                'background_color' => $headerSetting('header_background_color', '#ffffff'),
                'tagline_color' => $headerSetting('header_tagline_color', '#111111'),
                'nav_background_color' => $headerSetting('header_nav_background_color', '#ffffff'),
                'nav_text_color' => $headerSetting('header_nav_text_color', '#0b3769'),
                'nav_active_color' => $headerSetting('header_nav_active_color', '#f6f8fb'),
            ],
            'banner_image_heor' => count($bannerImages) > 0 ? $bannerImages : $bannerImage,
            'app_about' => settings('app_about', 9),
            'app_tagline' => settings('app_tagline', 9),
            'social_links' => [
                'facebook' => settings('app_facebook', 9),
                'twitter' => settings('app_twitter', 9),
                'linkedin' => settings('app_linkedin', 9),
                'youtube' => settings('app_youtube', 9),
                'instagram' => settings('app_instagram', 9),
                'tiktok' => settings('app_tiktok', 9),
                'pinterest' => settings('app_pinterest', 9),
                'email' => settings('app_email', 9),
                'phone' => settings('app_tel', 9),
            ],
            //
        ];
    }
}
