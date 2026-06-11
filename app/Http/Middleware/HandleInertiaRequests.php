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
        $headerMenuDefault = <<<'TEXT'
Home | /
- Home Page | /
About OSHE | /OrganizationProfile
- Organization Profile | /OrganizationProfile
- Mission & Vision | /OurMissionandVision
- OSHE's Core Values | /oshes-core-values
- National Policy Contributions | /national-policy-contributions
- Governance Structure | /governance-structure
- Board of Trustees | /board-of-trustees
- Executive Team | /executive-team
- Team OSHE | /TeamOshe
- Annual Reports | /annual-reports
What We Do | /what-we-do/occupational-safety-and-health
- Occupational Safety & Health (OSH) | /what-we-do/occupational-safety-and-health
- Labour Rights & Decent Work | /what-we-do/labour-rights-decent-work
- Social Protection | /what-we-do/social-protection
- Environmental Sustainability | /what-we-do/environmental-sustainability
- Climate Change & Just Transition | /what-we-do/climate-change-just-transition
- Trade Union Strengthening | /what-we-do/trade-union-strengthening
- Research & Advocacy | /what-we-do/research-advocacy
- Capacity Building & Training | /what-we-do/capacity-building-training
Sectoral Coverage | /sectoral-coverage
Thematic Priorities | /thematic-priorities/occupational-safety-health
Programs & Projects | /OngoingProject
- Ongoing Projects | /OngoingProject
- Completed Projects | /PastProject
- Project Database | /project-database
- Interactive Bangladesh Project Map | /bangladesh-project-map
- Project Success Stories | /project-success-stories
Partners & Donors | /ProjectPartners
Media & Resource Center | /news
- All News & Resources | /news
- Photo Gallery | /photo-gallery
- Video Gallery | /videos
- Publications | /news?category=publications
- Newsletter | /news?category=newsletter
- Meeting Reports | /news?category=meeting-report
- Partner Reports | /news?category=partners-report
- Training Reports | /news?category=training-report
- Day Observations | /news?category=day-observation
Career | /career
- Career Opportunities | /career
Contact Us | /contact
- Contact Information | /contact
- Office Location | /office-location
- Feedback & Complaints | /feedback-complaints
- Newsletter Subscription | /newsletter-subscription
TEXT;
        [$headerLogo, $headerLogoId] = settings('header_logo_image', 302, 1);
        $strategicPartnerSetting = function (string $name, string $fallback) {
            $value = settings($name, 402);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        [$strategicPartnersBackground, $strategicPartnersBackgroundId] = settings('strategic_partners_background_image', 402, 1);
        [$strategicPartnersLogo, $strategicPartnersLogoId] = settings('strategic_partners_oshe_logo_image', 402, 1);
        [$coreValuesBackground, $coreValuesBackgroundId] = settings('core_values_background_image', 403, 1);
        [$nationalPolicyBackground, $nationalPolicyBackgroundId] = settings('national_policy_background_image', 404, 1);
        [$nationalPolicyHeroImage, $nationalPolicyHeroImageId] = settings('national_policy_hero_image', 404, 1);
        $whatWeDoImageSetting = function (string $name) {
            [$image, $imageId] = settings($name, 405, 1);

            return is_numeric($imageId) && (int) $imageId > 0 ? $image : '';
        };
        $sectoralCoverageSetting = function (string $name, string $fallback) {
            $value = settings($name, 406);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        $sectorWideSetting = function (string $name, string $fallback) {
            $value = settings($name, 407);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        $sectorWideAsset = function (string $name) {
            [$asset, $assetId] = settings($name, 407, 1);

            return is_numeric($assetId) && (int) $assetId > 0 ? $asset : '';
        };
        [$thematicPrioritiesHeroImage, $thematicPrioritiesHeroImageId] = settings('thematic_priorities_hero_image', 408, 1);
        $footerSetting = function (string $name, string $fallback) {
            $value = settings($name, 410);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        [$footerBackgroundImage, $footerBackgroundImageId] = settings('footer_background_image', 410, 1);

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
                'partner_button_link' => in_array($headerSetting('header_partner_button_link', '/partner-with-us'), ['/ProjectPartners', '/contact'], true)
                    ? '/partner-with-us'
                    : $headerSetting('header_partner_button_link', '/partner-with-us'),
                'nav_home_text' => $headerSetting('header_nav_home_text', 'Home'),
                'nav_about_text' => $headerSetting('header_nav_about_text', 'About OSHE'),
                'nav_work_text' => $headerSetting('header_nav_work_text', 'Our Work'),
                'nav_programs_text' => $headerSetting('header_nav_programs_text', 'Programs & Projects'),
                'nav_research_text' => $headerSetting('header_nav_research_text', 'Research & Publications'),
                'nav_media_text' => $headerSetting('header_nav_media_text', 'Media Center'),
                'nav_partners_text' => $headerSetting('header_nav_partners_text', 'Partners'),
                'nav_news_events_text' => $headerSetting('header_nav_news_events_text', 'News & Events'),
                'nav_contact_text' => $headerSetting('header_nav_contact_text', 'Contact Us'),
                'menu_items_text' => $headerSetting('header_menu_items_text', $headerMenuDefault),
                'top_bar_color' => $headerSetting('header_top_bar_color', '#008f7a'),
                'top_text_color' => $headerSetting('header_top_text_color', '#ffffff'),
                'background_color' => $headerSetting('header_background_color', '#ffffff'),
                'tagline_color' => $headerSetting('header_tagline_color', '#09265c'),
                'nav_background_color' => $headerSetting('header_nav_background_color', '#007760'),
                'nav_text_color' => $headerSetting('header_nav_text_color', '#ffffff'),
                'nav_active_color' => $headerSetting('header_nav_active_color', '#17b7ad'),
            ],
            'strategic_partners_settings' => [
                'background_image' => is_numeric($strategicPartnersBackgroundId) && (int) $strategicPartnersBackgroundId > 0
                    ? $strategicPartnersBackground
                    : asset('assets/partners/strategic-partners-tree.png'),
                'oshe_logo' => is_numeric($strategicPartnersLogoId) && (int) $strategicPartnersLogoId > 0
                    ? $strategicPartnersLogo
                    : asset('assets/header/oshe-d-logo-trimmed.png'),
                'title' => $strategicPartnerSetting('strategic_partners_title_text', 'STRATEGIC PARTNERS & DONORS'),
                'subtitle' => $strategicPartnerSetting('strategic_partners_subtitle_text', 'Building change through collaboration'),
                'tree_text' => $strategicPartnerSetting('strategic_partners_tree_text', 'Stronger Partnerships for a Better Tomorrow'),
                'root_label' => $strategicPartnerSetting('strategic_partners_root_label_text', 'Together for Change'),
                'cta_title' => $strategicPartnerSetting('strategic_partners_cta_title_text', 'Together We Can Create Safe & Sustainable Workplaces'),
                'cta_description' => $strategicPartnerSetting('strategic_partners_cta_description_text', "Partner with OSHE and be part of our mission to promote workers' rights, safety, health and social justice."),
                'cta_button_text' => $strategicPartnerSetting('strategic_partners_cta_button_text', 'Become a Partner'),
                'cta_button_link' => $strategicPartnerSetting('strategic_partners_cta_button_link', '/partner-with-us') === '/contact'
                    ? '/partner-with-us'
                    : $strategicPartnerSetting('strategic_partners_cta_button_link', '/partner-with-us'),
                'items_text' => $strategicPartnerSetting('strategic_partners_items_text', ''),
            ],
            'core_values_settings' => [
                'page_image' => is_numeric($coreValuesBackgroundId) && (int) $coreValuesBackgroundId > 0
                    ? $coreValuesBackground
                    : asset('assets/core-values/oshes-core-values.png'),
            ],
            'national_policy_settings' => [
                'page_image' => is_numeric($nationalPolicyBackgroundId) && (int) $nationalPolicyBackgroundId > 0
                    ? $nationalPolicyBackground
                    : (is_numeric($nationalPolicyHeroImageId) && (int) $nationalPolicyHeroImageId > 0 ? $nationalPolicyHeroImage : ''),
            ],
            'what_we_do_settings' => [
                'occupational_safety_and_health' => $whatWeDoImageSetting('what_we_do_osh_image'),
                'labour_rights_decent_work' => $whatWeDoImageSetting('what_we_do_labour_rights_decent_work_image'),
                'social_protection' => $whatWeDoImageSetting('what_we_do_social_protection_image'),
                'environmental_sustainability' => $whatWeDoImageSetting('what_we_do_environmental_sustainability_image'),
                'climate_change_just_transition' => $whatWeDoImageSetting('what_we_do_climate_change_just_transition_image'),
                'trade_union_strengthening' => $whatWeDoImageSetting('what_we_do_trade_union_strengthening_image'),
                'research_advocacy' => $whatWeDoImageSetting('what_we_do_research_advocacy_image'),
                'capacity_building_training' => $whatWeDoImageSetting('what_we_do_capacity_building_training_image'),
            ],
            'sectoral_coverage_settings' => [
                'rmg_sector' => [
                    'kicker' => $sectoralCoverageSetting('rmg_sector_kicker_text', 'Sectoral Coverage'),
                    'title' => $sectoralCoverageSetting('rmg_sector_title_text', 'RMG Sector'),
                    'items_text' => $sectoralCoverageSetting('rmg_sector_timeline_items_text', ''),
                ],
            ],
            'sector_wide_settings' => [
                'status' => $sectorWideSetting('sector_wide_status', '1'),
                'title' => $sectorWideSetting('sector_wide_title_text', 'Sector-Wide Footprint'),
                'intro' => $sectorWideSetting('sector_wide_intro_text', ''),
                'sub_intro' => $sectorWideSetting('sector_wide_sub_intro_text', ''),
                'items_text' => $sectorWideSetting('sector_wide_items_text', ''),
                'cross_cutting_title' => $sectorWideSetting('sector_wide_cross_cutting_title_text', 'Cross-Cutting Sector Priorities'),
                'cross_cutting_points' => $sectorWideSetting('sector_wide_cross_cutting_points_text', ''),
                'closing_text' => $sectorWideSetting('sector_wide_closing_text', ''),
                'assets' => [
                    'rmg' => ['image' => $sectorWideAsset('sector_wide_rmg_image'), 'icon' => $sectorWideAsset('sector_wide_rmg_icon')],
                    'construction' => ['image' => $sectorWideAsset('sector_wide_construction_image'), 'icon' => $sectorWideAsset('sector_wide_construction_icon')],
                    'shipbreaking' => ['image' => $sectorWideAsset('sector_wide_shipbreaking_image'), 'icon' => $sectorWideAsset('sector_wide_shipbreaking_icon')],
                    'leather-tannery' => ['image' => $sectorWideAsset('sector_wide_leather_tannery_image'), 'icon' => $sectorWideAsset('sector_wide_leather_tannery_icon')],
                    'waste-management' => ['image' => $sectorWideAsset('sector_wide_waste_management_image'), 'icon' => $sectorWideAsset('sector_wide_waste_management_icon')],
                    'agriculture' => ['image' => $sectorWideAsset('sector_wide_agriculture_image'), 'icon' => $sectorWideAsset('sector_wide_agriculture_icon')],
                    'health-sanitation' => ['image' => $sectorWideAsset('sector_wide_health_sanitation_image'), 'icon' => $sectorWideAsset('sector_wide_health_sanitation_icon')],
                    'informal-home-based' => ['image' => $sectorWideAsset('sector_wide_informal_home_based_image'), 'icon' => $sectorWideAsset('sector_wide_informal_home_based_icon')],
                    'smes' => ['image' => $sectorWideAsset('sector_wide_smes_image'), 'icon' => $sectorWideAsset('sector_wide_smes_icon')],
                    'jhut' => ['image' => $sectorWideAsset('sector_wide_jhut_image'), 'icon' => $sectorWideAsset('sector_wide_jhut_icon')],
                    'other-vulnerable' => ['image' => $sectorWideAsset('sector_wide_other_vulnerable_image'), 'icon' => $sectorWideAsset('sector_wide_other_vulnerable_icon')],
                ],
            ],
            'thematic_priority_settings' => [
                'hero_image' => is_numeric($thematicPrioritiesHeroImageId) && (int) $thematicPrioritiesHeroImageId > 0
                    ? $thematicPrioritiesHeroImage
                    : '',
            ],
            'footer_settings' => [
                'background_image' => is_numeric($footerBackgroundImageId) && (int) $footerBackgroundImageId > 0
                    ? $footerBackgroundImage
                    : '',
                'background_position' => $footerSetting('footer_background_position_text', 'center bottom'),
                'background_size' => $footerSetting('footer_background_size', 'cover'),
                'overlay_start_color' => $footerSetting('footer_overlay_start_color', '#2e4b98'),
                'overlay_end_color' => $footerSetting('footer_overlay_end_color', '#4264b2'),
                'overlay_base_color' => $footerSetting('footer_overlay_base_color', '#26418c'),
                'overlay_start_opacity' => $footerSetting('footer_overlay_start_opacity', '0.62'),
                'overlay_end_opacity' => $footerSetting('footer_overlay_end_opacity', '0.56'),
                'overlay_base_opacity' => $footerSetting('footer_overlay_base_opacity', '0.50'),
                'text_color' => $footerSetting('footer_text_color', '#ffffff'),
                'accent_color' => $footerSetting('footer_accent_color', '#ffd51f'),
                'separator_color' => $footerSetting('footer_separator_color', '#ffffff'),
                'main_height' => $footerSetting('footer_main_height', '330'),
                'bottom_height' => $footerSetting('footer_bottom_height', '58'),
                'heading_font_size' => $footerSetting('footer_heading_font_size', '21'),
                'body_font_size' => $footerSetting('footer_body_font_size', '17'),
                'link_font_size' => $footerSetting('footer_link_font_size', '18'),
                'bottom_font_size' => $footerSetting('footer_bottom_font_size', '16'),
                'social_icon_size' => $footerSetting('footer_social_icon_size', '40'),
                'column_gap' => $footerSetting('footer_column_gap', '44'),
                'quick_links_title' => $footerSetting('footer_quick_links_title_text', 'Quick Links'),
                'quick_links_text' => $footerSetting('footer_quick_links_text', "About Us | /OrganizationProfile\nMedia | /news\nCareer | /career\nEvents | /Events"),
                'contact_title' => $footerSetting('footer_contact_title_text', 'Contact Us'),
                'address' => $footerSetting('footer_address_text', "House 15 (2nd Floor), Road 3/1,\nBlock-A, Sector-7, Mirpur,\nDhaka-1216, Bangladesh"),
                'phone' => $footerSetting('footer_phone_text', '+880 2 5501 1768'),
                'email' => $footerSetting('footer_email_text', 'info@oshefoundation.org'),
                'website' => $footerSetting('footer_website_text', 'www.oshefoundation.org'),
                'subscription_title' => $footerSetting('footer_subscription_title_text', 'Email Subscription'),
                'subscription_description' => $footerSetting('footer_subscription_description_text', 'Stay updated with our latest news and initiatives.'),
                'subscribe_button_text' => $footerSetting('footer_subscribe_button_text', 'Subscribe'),
                'follow_title' => $footerSetting('footer_follow_title_text', 'Follow Us'),
                'social_links_text' => $footerSetting('footer_social_links_text', "facebook | Facebook | https://www.facebook.com/OSHEfoundationBangladesh\ntwitter | X | #\nyoutube | YouTube | https://www.youtube.com/results?search_query=oshe+foundation\ninstagram | Instagram | #\nlinkedin | LinkedIn | #"),
                'copyright_text' => $footerSetting('footer_copyright_text', '© 2025 OSHE Foundation. All Rights Reserved.'),
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
