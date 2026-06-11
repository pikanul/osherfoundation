@extends('admin.layouts.app')

@section('title', 'Settings List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Settings' => route('admin.setting.index', ['page' => 'main']),
                'Settings list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Settings list' :links="$links" />
        <div class="content-body">
            @php
                $journeyTimelineDefault = <<<'TEXT'
2001 | OSHE begins its journey
2003 | Formal institutional establishment
2005 | OSH initiatives for informal and home-based workers
2006 | Decent work advocacy in shipbreaking; launch of medical health camps (2006–2016)
2007 | National OSH Policy drafting; OSH training in RMG and construction sectors
2008 | Development of OSH guidelines for construction
2009 | National OSH profiling; green jobs and strategy development
2010 | Awareness on child labour hazards, primary health care, and climate change
2011 | Waste pickers, health education, organizing of home-based workers
2012 | Climate change education, PPE support for informal workers
2013 | Rana Plaza victim support, asbestos exposure awareness, just transition in shipbreaking
2014 | Fire incident reviews (e.g., Tajreen), worker education support
2015 | OSH outreach in local communities and workplace safety promotion
2016 | Medical health camps in shipbreaking field (2006–2016) / Pioneered campaign on asbestosis, certified and supported victims to receive compensation
2017 | OSH training for trade union federations
2018 | Decent work in leather supply chains; establishment of union training institute
2019 | OSH assessments in SME sectors; expansion of social protection programs
2020 | COVID-19 Response, ongoing OSH and leather sector initiatives
2021 | Victim support campaigns, regional occupational disaster mapping
2022 | Continued engagement in leather, education, informal sectors; Workplace Accident monitoring and compensation benefit (Special focus on EIS)
2023 | Promotion of sustainable leather production; Asia-wide Occupational Disaster Mapping initiative
2024 | Continued monitoring, victim support, and Shipbreaking worker health camp promotion
2025 | Scaling of digital accident tracking systems, regional OSH campaigns, and union capacity development
TEXT;
                $headerMenuDefault = <<<'TEXT'
Home | /
- Home Page | /
About OSHE | /OrganizationProfile
- Organization Profile | /OrganizationProfile
- Mission & Vision | /OurMissionandVision
- OSHE’s Core Values | /oshes-core-values
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
                $sectorWideItemsDefault = <<<'TEXT'
rmg | 1 | 1 | Ready-Made Garments (RMG) | OSHE works with workers, trade unions, employers, and stakeholders in the RMG sector to promote occupational safety and health, labour rights, decent work, gender equality, social dialogue, and workplace protection. The RMG sector remains one of OSHE’s important areas of engagement due to its large workforce, high concentration of women workers, and strong relevance to labour standards, workplace safety, and social compliance. | Workplace safety awareness; Labour rights education; Trade union strengthening; Gender equality and GBV prevention; Social protection; Responsible workplace practices
construction | 1 | 2 | Construction | Construction is one of the most hazardous sectors for workers due to risks related to falls, unsafe equipment, lack of protective gear, electrical hazards, and weak safety practices. OSHE supports construction workers through OSH awareness, safety training, emergency preparedness, and advocacy for safer working conditions. | OSH training; Accident prevention; Use of protective equipment; Emergency preparedness; Safer working conditions; Compliance with safety standards
shipbreaking | 1 | 3 | Shipbreaking | Shipbreaking workers are exposed to serious occupational hazards, including heavy machinery risks, toxic substances, fire and explosion risks, unsafe dismantling practices, and environmental pollution. OSHE works to promote workplace safety, health protection, workers’ rights, environmental justice, and improved safety practices in this high-risk sector. | Accident prevention; Occupational disease awareness; Safe work practices; Toxic hazard awareness; Environmental justice; Worker protection and accountability
leather-tannery | 1 | 4 | Leather and Tannery | The leather and tannery sector involves chemical exposure, unsafe handling of materials, environmental pollution, and occupational health risks. OSHE promotes OSH awareness, labour rights, decent work, and environmental responsibility for workers and communities connected to the leather supply chain. | Chemical safety awareness; Workplace health protection; Labour standards; Social dialogue; Environmental responsibility; Safer handling practices
waste-management | 1 | 5 | Waste Management | Waste workers face major health and safety risks, including exposure to hazardous waste, sharp materials, toxic substances, infections, poor sanitation, and social exclusion. OSHE works with waste workers and related stakeholders to promote occupational safety, health protection, dignity, livelihood support, and social recognition. | OSH awareness; Health protection; Dignity at work; Worker empowerment; Social protection advocacy; Safer working conditions
agriculture | 1 | 6 | Agriculture | Agricultural workers often face risks from pesticides, unsafe tools, climate change, heat stress, poor access to health protection, and informal employment conditions. OSHE supports agriculture workers and communities through awareness, livelihood support, climate resilience, environmental justice, and social protection advocacy. | Pesticide safety; Climate resilience; Sustainable livelihoods; Health protection; Informal worker protection; Climate adaptation
health-sanitation | 1 | 7 | Health and Sanitation | Workers engaged in health, sanitation, cleaning, and related services are often exposed to biological hazards, waste, chemicals, infection risks, and unsafe working environments. OSHE promotes safety awareness, health protection, dignity at work, and social protection for these workers. | Biological hazard awareness; Infection prevention; Sanitation worker dignity; Health protection; Safe service delivery; Social protection
informal-home-based | 1 | 8 | Informal and Home-Based Work | OSHE works with informal and home-based workers who often lack formal contracts, legal protection, social security, workplace safety measures, and access to institutional support. These workers include women workers, community-based workers, self-employed workers, and vulnerable groups engaged in informal livelihoods. | Worker awareness; Legal protection advocacy; Social security inclusion; Livelihood protection; Women worker empowerment; Informal worker representation
smes | 1 | 9 | Small and Medium Enterprises (SMEs) | Workers in SMEs often face gaps in safety systems, documentation, legal compliance, training, and access to workplace protection. OSHE supports SMEs by promoting OSH awareness, labour rights, decent work principles, and practical workplace safety measures. | OSH awareness; Labour rights education; Workplace safety systems; Documentation support; Worker participation; Responsible business conduct
jhut | 1 | 10 | Jhut Industry | The jhut industry involves workers engaged in recycling, sorting, handling, and processing garment waste and related materials. Workers may face risks related to dust, unsafe handling practices, fire hazards, poor working conditions, and informal employment. | Dust and fire hazard awareness; Safer handling practices; Health protection; Informal worker rights; Better working conditions; Waste recycling safety
other-vulnerable | 1 | 11 | Other Labour-Intensive and Vulnerable Sectors | Beyond these sectors, OSHE also works with other labour-intensive and vulnerable worker groups where risks related to unsafe work, weak labour protection, poverty, informality, climate change, and social exclusion remain high. | Vulnerable worker protection; OSH awareness; Labour rights advocacy; Social protection; Climate justice; Community empowerment
TEXT;
                $sectorWideCrossCuttingDefault = <<<'TEXT'
Occupational safety and health awareness and risk prevention
Labour rights and decent work promotion
Trade union strengthening and worker participation
Social dialogue among workers, employers, government, and civil society
Social protection and livelihood security
Gender equality, GBV prevention, and workplace inclusion
Occupational disease monitoring and workplace health protection
Climate justice, environmental justice, and Just Transition
Policy advocacy, legal reform, and institutional strengthening
Community-based awareness, training, and worker empowerment
TEXT;
                $data = [
                    [
                        'key' => 9,
                        'title' => 'Main Settings',
                        'label' => '',
                        'referance' => '',

                        'data' => [
                            ['name' => 'app_title', 'required' => false],
                            ['name' => 'app_name_short'],
                             ['name' => 'app_about',  'col' => 'col-12'],
                             ['name' => 'app_tagline'],

                            ['name' => 'app_maps'],
                            ['name' => 'app_map_embaded_url', 'reference' => 'https://www.google.com/maps/', 'label' => 'Embeded URL'],
                             ['name' => 'app_address'],


                            [

                                'name' => 'app_fav_image',
                                'reference' => 'https://web.dev/articles/building/an-adaptive-favicon?hl=bn',
                            ],
                            ['name' => 'app_image'],
                            ['name' => 'app_footer_image'],
                            ['name' => 'login_admin_image'],

                        ],
                    ],


                    [
                        'key' => 9,
                        'title' => 'Social Media & Contact',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'app_instagram'],
                            ['name' => 'app_facebook'],
                            ['name' => 'app_messenger'],
                            ['name' => 'app_twitter'],
                            ['name' => 'app_linkedin'],
                            ['name' => 'app_website'],
                            ['name' => 'app_youtube'],
                            ['name' => 'app_pinterest'],
                            ['name' => 'app_email'],
                            ['name' => 'app_tel'],
                            ['name' => 'app_tiktok'],
                            [

                                'name' => 'app_whatsapp',
                                'reference' => 'https://api.whatsapp.com/send?phone=8801590084779',
                            ],

                        ],
                    ],

                    [
                        'key' => 302,
                        'title' => 'Header Settings',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'header_logo_image'],
                            ['name' => 'header_tagline_text', 'col' => 'col-12'],
                            ['name' => 'header_phone_text'],
                            ['name' => 'header_email_text'],
                            ['name' => 'header_search_text'],
                            ['name' => 'header_partner_button_text'],
                            ['name' => 'header_partner_button_link', 'default' => '/partner-with-us'],
                            ['name' => 'header_nav_home_text'],
                            ['name' => 'header_nav_about_text'],
                            ['name' => 'header_nav_work_text'],
                            ['name' => 'header_nav_programs_text'],
                            ['name' => 'header_nav_research_text'],
                            ['name' => 'header_nav_media_text'],
                            ['name' => 'header_nav_partners_text'],
                            ['name' => 'header_nav_news_events_text'],
                            ['name' => 'header_nav_contact_text'],
                            [
                                'name' => 'header_menu_items_text',
                                'col' => 'col-12',
                                'default' => $headerMenuDefault,
                                'note' => 'Editable main menu. Main item format: Label | URL. Submenu item format: - Label | URL. Hide any item by deleting its line or starting the line with #. Reorder by moving lines.',
                            ],
                            ['name' => 'header_top_bar_color'],
                            ['name' => 'header_top_text_color'],
                            ['name' => 'header_background_color'],
                            ['name' => 'header_tagline_color'],
                            ['name' => 'header_nav_background_color'],
                            ['name' => 'header_nav_text_color'],
                            ['name' => 'header_nav_active_color'],
                        ],
                    ],

                    [
                        'key' => 998,
                        'title' => 'Hero Section',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'banner_image'],
                        ],
                    ],

                    [
                        'key' => 27,
                        'title' => 'Newsletter Banner',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'newslater_banner_image'],
                            ['name' => 'newslater_banner_top_image'],
                            ['name' => 'newslater_footer_image'],


                        ],
                    ],
                    [
                        'key' => 410,
                        'title' => 'Footer Settings',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'footer_background_image', 'col' => 'col-12', 'note' => 'Upload footer background image. If empty, the default blue worker background will be used.'],
                            ['name' => 'footer_background_position_text', 'col' => 'col-md-6', 'default' => 'center bottom', 'note' => 'Example: center bottom, center 70%, left center.'],
                            [
                                'name' => 'footer_background_size',
                                'col' => 'col-md-6',
                                'default' => 'cover',
                                'option' => ['cover', 'contain', 'auto'],
                            ],
                            ['name' => 'footer_overlay_start_color', 'col' => 'col-md-4', 'default' => '#2e4b98'],
                            ['name' => 'footer_overlay_end_color', 'col' => 'col-md-4', 'default' => '#4264b2'],
                            ['name' => 'footer_overlay_base_color', 'col' => 'col-md-4', 'default' => '#26418c'],
                            ['name' => 'footer_overlay_start_opacity', 'col' => 'col-md-4', 'default' => '0.62', 'note' => 'Use 0 to 1. Lower value shows more image.'],
                            ['name' => 'footer_overlay_end_opacity', 'col' => 'col-md-4', 'default' => '0.56', 'note' => 'Use 0 to 1. Lower value shows more image.'],
                            ['name' => 'footer_overlay_base_opacity', 'col' => 'col-md-4', 'default' => '0.50', 'note' => 'Use 0 to 1. Lower value shows more image.'],
                            ['name' => 'footer_text_color', 'col' => 'col-md-4', 'default' => '#ffffff'],
                            ['name' => 'footer_accent_color', 'col' => 'col-md-4', 'default' => '#ffd51f'],
                            ['name' => 'footer_separator_color', 'col' => 'col-md-4', 'default' => '#ffffff'],
                            ['name' => 'footer_main_height', 'col' => 'col-md-4', 'default' => '330', 'note' => 'Desktop footer content height in pixels.'],
                            ['name' => 'footer_bottom_height', 'col' => 'col-md-4', 'default' => '58', 'note' => 'Bottom copyright bar height in pixels.'],
                            ['name' => 'footer_heading_font_size', 'col' => 'col-md-4', 'default' => '21', 'note' => 'Desktop heading font size in pixels.'],
                            ['name' => 'footer_body_font_size', 'col' => 'col-md-4', 'default' => '17', 'note' => 'Desktop body/contact font size in pixels.'],
                            ['name' => 'footer_link_font_size', 'col' => 'col-md-4', 'default' => '18', 'note' => 'Desktop quick link font size in pixels.'],
                            ['name' => 'footer_bottom_font_size', 'col' => 'col-md-4', 'default' => '16', 'note' => 'Copyright font size in pixels.'],
                            ['name' => 'footer_social_icon_size', 'col' => 'col-md-4', 'default' => '40', 'note' => 'Social button size in pixels.'],
                            ['name' => 'footer_column_gap', 'col' => 'col-md-4', 'default' => '44', 'note' => 'Desktop spacing between footer columns in pixels.'],
                            ['name' => 'footer_quick_links_title_text', 'col' => 'col-md-6', 'default' => 'Quick Links'],
                            [
                                'name' => 'footer_quick_links_text',
                                'col' => 'col-12',
                                'default' => "About Us | /OrganizationProfile\nMedia | /news\nCareer | /career\nEvents | /Events",
                                'note' => 'One link per line. Format: Label | URL. Delete a line to remove it, add a line to add a new link.',
                            ],
                            ['name' => 'footer_contact_title_text', 'col' => 'col-md-6', 'default' => 'Contact Us'],
                            ['name' => 'footer_address_text', 'col' => 'col-12', 'default' => "House 15 (2nd Floor), Road 3/1,\nBlock-A, Sector-7, Mirpur,\nDhaka-1216, Bangladesh"],
                            ['name' => 'footer_phone_text', 'col' => 'col-md-6', 'default' => '+880 2 5501 1768'],
                            ['name' => 'footer_email_text', 'col' => 'col-md-6', 'default' => 'info@oshefoundation.org'],
                            ['name' => 'footer_website_text', 'col' => 'col-md-6', 'default' => 'www.oshefoundation.org'],
                            ['name' => 'footer_subscription_title_text', 'col' => 'col-md-6', 'default' => 'Email Subscription'],
                            ['name' => 'footer_subscription_description_text', 'col' => 'col-12', 'default' => 'Stay updated with our latest news and initiatives.'],
                            ['name' => 'footer_subscribe_button_text', 'col' => 'col-md-6', 'default' => 'Subscribe'],
                            ['name' => 'footer_follow_title_text', 'col' => 'col-md-6', 'default' => 'Follow Us'],
                            [
                                'name' => 'footer_social_links_text',
                                'col' => 'col-12',
                                'default' => "facebook | Facebook | https://www.facebook.com/OSHEfoundationBangladesh\ntwitter | X | #\nyoutube | YouTube | https://www.youtube.com/results?search_query=oshe+foundation\ninstagram | Instagram | #\nlinkedin | LinkedIn | #",
                                'note' => 'One social link per line. Format: type | Label | URL. Supported types: facebook, twitter, youtube, instagram, linkedin.',
                            ],
                            ['name' => 'footer_copyright_text', 'col' => 'col-12', 'default' => '© 2025 OSHE Foundation. All Rights Reserved.'],
                        ],
                    ],
                    [
                        'key' => 171,
                        'title' => 'Newsletter About project',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'about_project_image_1'],
                            ['name' => 'about_project_image_2'],
                        ],
                    ],
                    [
                        'key' => 9,
                        'title' => 'Settings & Privacy',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'is_demo_mode_status'],
                        ],
                    ],

                    [
                        'title' => 'MRAM SMS SETTINGS',
                        'permission' => 'mram_sms',
                        'referance' => 'https://sms.mram.com.bd/',
                        'key' => 87,
                        'data' => [
                            [
                                'name' => 'maram_api_key',
                                'key' => '87',
                            ],
                            [
                                'name' => 'mariam_api_serder_id',
                                'key' => '87',
                            ],
                            [
                                'name' => 'mariam_api_status',
                                'key' => '87',
                            ],
                        ],
                    ],


                    [
                        'key' => 171,
                        'title' => 'Mission Vision Summary',
                        'label' => '',
                        'referance' => '',
                        'permission' => 'mission-vision-summary',
                        'data' => [
                            ['name' => 'mission_title','col' => 'col-12', ],
                            ['name' => 'mission_description','col' => 'col-12',  'class' => 'summernote'],
                            ['name' => 'vision_title','col' => 'col-12'],
                            ['name' => 'vision_description','col' => 'col-12',  'class' => 'summernote'],
                            ['name' => 'mission_vision_image'],


                        ],
                    ],


                    [
                        'key' => 9,
                        'title' => ' Strength',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'strength_description', 'class' => 'summernote', 'col' => 'col-12'],
                        ],
                    ],
                    [
                        'key' => 201,
                        'title' => 'Partner',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'partner_title'],
                        ],
                    ],
                    [
                        'key' => 402,
                        'title' => 'Strategic Partners & Donors',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'strategic_partners_background_image', 'col' => 'col-12'],
                            ['name' => 'strategic_partners_oshe_logo_image'],
                            ['name' => 'strategic_partners_title_text', 'col' => 'col-12', 'default' => 'STRATEGIC PARTNERS & DONORS'],
                            ['name' => 'strategic_partners_subtitle_text', 'col' => 'col-12', 'default' => 'Building change through collaboration'],
                            ['name' => 'strategic_partners_tree_text', 'col' => 'col-12', 'default' => 'Stronger Partnerships for a Better Tomorrow'],
                            ['name' => 'strategic_partners_root_label_text', 'col' => 'col-12', 'default' => 'Together for Change'],
                            ['name' => 'strategic_partners_cta_title_text', 'col' => 'col-12', 'default' => 'Together We Can Create Safe & Sustainable Workplaces'],
                            ['name' => 'strategic_partners_cta_description_text', 'col' => 'col-12', 'default' => "Partner with OSHE and be part of our mission to promote workers' rights, safety, health and social justice."],
                            ['name' => 'strategic_partners_cta_button_text', 'col' => 'col-md-6', 'default' => 'Become a Partner'],
                            ['name' => 'strategic_partners_cta_button_link', 'col' => 'col-md-6', 'default' => '/partner-with-us'],
                            [
                                'name' => 'strategic_partners_items_text',
                                'col' => 'col-12',
                                'default' => "International Organizations | WSM Belgium | Belgium | WSM\nInternational Organizations | SOLIDAR Suisse | Switzerland | SOLIDAR\nInternational Organizations | Solidarity Center (AFL-CIO) | United States | SC\nUnited Nations & Development Partners | ILO | Global | ILO\nUnited Nations & Development Partners | WHO | Global | WHO\nWorker & Community Networks | StreetNet International | Global | StreetNet\nNational Partners | Ministry of Labour and Employment | Bangladesh | MoLE",
                                'note' => 'Add/remove/change partners here. One partner per line. Format: Category | Partner Name | Country/Region | Short name or logo text. For a logo URL, paste the URL as the 4th value.',
                            ],
                        ],
                    ],
                    [
                        'key' => 221,
                        'title' => 'Organization Profile',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'org_prof_ile_title', 'col' => 'col-12'],
                            ['name' => 'org_prof_ile_description', 'class' => 'summernote', 'col' => 'col-12'],
                        ],
                    ],
                    [
                        'key' => 303,
                        'title' => 'Our Impact',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'impact_image'],
                            ['name' => 'impact_home_status', 'col' => 'col-md-6', 'default' => '1'],
                            ['name' => 'impact_home_text', 'col' => 'col-md-6', 'default' => 'Our Impact'],
                            ['name' => 'impact_home_link', 'col' => 'col-12', 'default' => '/OurImpact'],
                        ],
                    ],
                    [
                        'key' => 304,
                        'title' => 'Career Public Jobs',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'career_public_jobs_status', 'col' => 'col-md-6', 'default' => '1'],
                            ['name' => 'career_public_jobs_title', 'col' => 'col-md-6', 'default' => 'Public Jobs'],
                            [
                                'name' => 'career_public_jobs_description',
                                'col' => 'col-12',
                                'default' => 'Browse current public job openings and apply through the OSHE HRM portal.',
                            ],
                            [
                                'name' => 'career_public_jobs_link',
                                'col' => 'col-12',
                                'default' => 'https://hrm.oshefoundation.com/jobs/openings',
                                'note' => 'Paste the public HRM jobs link here. Example: https://hrm.oshefoundation.com/jobs/openings',
                            ],
                            [
                                'name' => 'career_public_jobs_links_text',
                                'col' => 'col-12',
                                'default' => "OSHE HRM | https://hrm.oshefoundation.com/jobs/openings\nBDJobs | https://bdjobs.com/h/details/1495038?ln=1",
                                'note' => 'Add one public job source per line. Format: Label | URL',
                            ],
                        ],
                    ],
                    [
                        'key' => 401,
                        'title' => 'OSHE’s Journey',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'journey_kicker_text', 'col' => 'col-12', 'default' => 'Strategic Progress'],
                            ['name' => 'journey_title_text', 'col' => 'col-12', 'default' => "OSHE's Journey: Footprint & Strategic Progress"],
                            [
                                'name' => 'journey_timeline_items_text',
                                'col' => 'col-12',
                                'default' => $journeyTimelineDefault,
                                'note' => 'Add one milestone per line: 2026 | Your new text. Icons are assigned automatically. Use / inside text for a second line.',
                            ],
                        ],
                    ],
                    [
                        'key' => 403,
                        'title' => 'OSHE’s Core Values',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'core_values_background_image', 'col' => 'col-12', 'note' => 'Upload the full Core Values section image. It will replace the entire /oshes-core-values page interface. Recommended ratio: 3:2 or 16:9, high resolution.'],
                        ],
                    ],
                    [
                        'key' => 404,
                        'title' => 'National Policy Contributions',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'national_policy_background_image', 'col' => 'col-12', 'note' => 'Upload the full National Policy Contributions page image. It will replace the entire /national-policy-contributions page interface. Recommended ratio: 3:2 or 16:9, high resolution.'],
                        ],
                    ],
                    [
                        'key' => 405,
                        'title' => 'What We Do Pages',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'what_we_do_osh_image', 'col' => 'col-12', 'note' => 'Upload the full Occupational Safety & Health (OSH) page image.'],
                            ['name' => 'what_we_do_labour_rights_decent_work_image', 'col' => 'col-12', 'note' => 'Upload the full Labour Rights & Decent Work page image.'],
                            ['name' => 'what_we_do_social_protection_image', 'col' => 'col-12', 'note' => 'Upload the full Social Protection page image.'],
                            ['name' => 'what_we_do_environmental_sustainability_image', 'col' => 'col-12', 'note' => 'Upload the full Environmental Sustainability page image.'],
                            ['name' => 'what_we_do_climate_change_just_transition_image', 'col' => 'col-12', 'note' => 'Upload the full Climate Change & Just Transition page image.'],
                            ['name' => 'what_we_do_trade_union_strengthening_image', 'col' => 'col-12', 'note' => 'Upload the full Trade Union Strengthening page image.'],
                            ['name' => 'what_we_do_research_advocacy_image', 'col' => 'col-12', 'note' => 'Upload the full Research & Advocacy page image.'],
                            ['name' => 'what_we_do_capacity_building_training_image', 'col' => 'col-12', 'note' => 'Upload the full Capacity Building & Training page image.'],
                        ],
                    ],
                    [
                        'key' => 408,
                        'title' => 'Thematic Priorities Pages',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'thematic_priorities_hero_image', 'col' => 'col-12', 'note' => 'Upload one hero image for the main Thematic Priorities button/pages. This image will show on all Thematic Priorities pages.'],
                        ],
                    ],
                    [
                        'key' => 409,
                        'title' => 'Partner With Us Form',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'partner_inquiry_admin_email', 'col' => 'col-md-6', 'default' => settings('app_email', 9), 'note' => 'Admin email address that receives Partner With Us inquiry notifications.'],
                            [
                                'name' => 'partner_inquiry_acknowledgement_status',
                                'col' => 'col-md-6',
                                'default' => '1',
                                'option' => ['1', '0'],
                                'note' => 'Set 1 to send acknowledgment email to the submitter, 0 to disable.',
                            ],
                        ],
                    ],
                    [
                        'key' => 406,
                        'title' => 'RMG Sector',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'rmg_sector_kicker_text', 'col' => 'col-12', 'default' => 'Sectoral Coverage'],
                            ['name' => 'rmg_sector_title_text', 'col' => 'col-12', 'default' => 'RMG Sector'],
                            [
                                'name' => 'rmg_sector_timeline_items_text',
                                'col' => 'col-12',
                                'default' => '',
                                'note' => 'Add one item per line: 2026 | Your text. Icons are assigned automatically. Use / inside text for a second line.',
                            ],
                        ],
                    ],
                    [
                        'key' => 407,
                        'title' => 'Sector-Wide Footprint',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'sector_wide_status', 'col' => 'col-md-6', 'default' => '1'],
                            ['name' => 'sector_wide_title_text', 'col' => 'col-12', 'default' => 'Sector-Wide Footprint'],
                            [
                                'name' => 'sector_wide_intro_text',
                                'col' => 'col-12',
                                'default' => 'OSHE Foundation works across both formal and informal sectors in Bangladesh, addressing the safety, health, rights, dignity, and livelihood needs of workers in diverse labour markets. OSHE’s sector-wide engagement focuses on high-risk industries, vulnerable worker groups, informal economy workers, and communities exposed to occupational hazards, unsafe working conditions, climate risks, and social protection gaps.',
                            ],
                            [
                                'name' => 'sector_wide_sub_intro_text',
                                'col' => 'col-12',
                                'default' => 'Through training, awareness raising, research, policy advocacy, social dialogue, community engagement, and rights-based support, OSHE promotes safer workplaces, decent work, labour rights, social protection, environmental justice, and sustainable livelihoods across multiple sectors.',
                            ],
                            [
                                'name' => 'sector_wide_items_text',
                                'col' => 'col-12',
                                'default' => $sectorWideItemsDefault,
                                'note' => 'One sector per line. Format: slug | active(1/0) | order | title | description | focus 1; focus 2; focus 3. Reorder by changing order number. Delete a line to remove a sector.',
                            ],
                            ['name' => 'sector_wide_cross_cutting_title_text', 'col' => 'col-12', 'default' => 'Cross-Cutting Sector Priorities'],
                            [
                                'name' => 'sector_wide_cross_cutting_points_text',
                                'col' => 'col-12',
                                'default' => $sectorWideCrossCuttingDefault,
                                'note' => 'Add one priority point per line.',
                            ],
                            ['name' => 'sector_wide_closing_text', 'col' => 'col-12', 'default' => 'Through its wide sectoral presence, OSHE continues to build safer, fairer, healthier, and more sustainable workplaces for workers across Bangladesh.'],
                        ],
                    ],
                    [
                        'key' => 201,
                        'title' => 'Project',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'projects_title', 'col' => 'col-12'],

                        ],
                    ],
                    [
                        'key' => 9,
                        'title' => 'WorkersPower',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'workpower_title'],
                            ['name' => 'workpower_description', 'class' => 'summernote'],
                        ],
                    ],
                    [
                        'key' => 9,
                        'title' => 'EoshVictims',
                        'label' => '',
                        'referance' => '',
                        'data' => [
                            ['name' => 'eosh_victims_title'],
                            ['name' => 'eosh_victims_description', 'class' => 'summernote'],
                            ['name' => 'eosh_victims_image'],
                        ],
                    ],


                ];



                $data = json_decode(json_encode($data));
                $settingsSectionCount = count($data);
                $settingsFieldCount = collect($data)->sum(function ($section) {
                    return isset($section->data) && is_array($section->data) ? count($section->data) : 0;
                });

            @endphp
            <div class="card border-0 shadow-sm settings-hero mb-2">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-25 text-white">Main Settings Center</h3>
                        <p class="mb-0 text-white-75">Manage website identity, sections, contact, and display settings in one place.</p>
                    </div>
                    <div class="d-flex flex-wrap">
                        <div class="settings-pill mr-1 mb-1">
                            <span class="settings-pill-label">Sections</span>
                            <span class="settings-pill-value">{{ $settingsSectionCount }}</span>
                        </div>
                        <div class="settings-pill mb-1">
                            <span class="settings-pill-label">Fields</span>
                            <span class="settings-pill-value">{{ $settingsFieldCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.settings.partials.main-setting-helper', ['data' => $data])

        </div>
    </div>
@endsection

@push('style')
<style>
    .settings-hero {
        background: linear-gradient(135deg, #0f2f45 0%, #1d5b83 55%, #2c7fb0 100%);
        border-radius: 14px;
    }
    .text-white-75 {
        color: rgba(255,255,255,.78);
    }
    .settings-pill {
        min-width: 120px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.32);
        padding: 8px 10px;
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .settings-pill-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: rgba(255,255,255,.82);
    }
    .settings-pill-value {
        font-size: 20px;
        line-height: 1.1;
        font-weight: 800;
        color: #fff;
    }
</style>
@endpush
