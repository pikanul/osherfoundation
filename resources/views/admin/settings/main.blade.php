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
                            ['name' => 'header_partner_button_link'],
                            ['name' => 'header_nav_home_text'],
                            ['name' => 'header_nav_about_text'],
                            ['name' => 'header_nav_work_text'],
                            ['name' => 'header_nav_programs_text'],
                            ['name' => 'header_nav_research_text'],
                            ['name' => 'header_nav_media_text'],
                            ['name' => 'header_nav_partners_text'],
                            ['name' => 'header_nav_news_events_text'],
                            ['name' => 'header_nav_contact_text'],
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
