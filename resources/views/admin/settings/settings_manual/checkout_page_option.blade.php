{{-- Master Include --}}
@extends('layout.admin.master')

{{-- Define Site Title --}}
@section('title', 'Checkout Page Option')

{{-- Content Extends --}}
@section('content')
    <x-summary>

        {{-- <div class="row  mb-2">
          
            <x-dashboard.link_card column="col-lg-3 col-6" bg="bg-primary" count="Details Page" title="Details Page Option"
                icon="far fa-info" link="{{ route('admin.setting.view', 'details_page_option') }}" sort="sort_3" />
        </div> --}}
    </x-summary>


    @php
        $data = [
            [
                'key' => 56,
                'title' => 'Name',
                'label' => '',
                'referance' => '',
                'data' => [
                    ['name' => 'checkout_name_status', 'image' => 'details_page_option/product_variant_style3_status.png'],
                    ['name' => 'checkout_name_full_status'],
                ],
            ],
            [
                'key' => 56,
                'title' => 'Email',
                'label' => '',
                'referance' => '',
                'data' => [
                    ['name' => 'checkout_email_status'],
                    ['name' => 'checkout_email_full_status'],

                   
                ],
            ],
            [
                'key' => 56,
                'title' => 'Phone',
                'label' => '',
                'referance' => '',
                'data' => [
                  
                    ['name' => 'checkout_phone_status'],
                    ['name' => 'checkout_phone_full_column_status'],
                    ['name' => 'auto_fill_information_status'],
                ],
            ],
            [
                'key' => 56,
                'title' => 'Address',
                'label' => '',
                'referance' => '',
                'data' => [
                    ['name' => 'checkout_address_status'],
                ],
            ],
            [
                'key' => 56,
                'title' => 'Note',
                'label' => '',
                'referance' => '',
                'data' => [
                    ['name' => 'checkout_note_status'],
                ],
            ],
            [
                'key' => 56,
                'title' => 'Apartment',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'checkout_apartment_status'],
                ],
            ],
            [
                'key' => 56,
                'title' => 'Town/City',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'checkout_town_status'],
                    ['name' => 'checkout_town_full_status'],

                ],
            ],
            [
                'key' => 56,
                'title' => 'Country/Region',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'checkout_country_status'],
                    ['name' => 'checkout_country_full_status'],

                ],
            ],
            [
                'key' => 56,
                'title' => 'State',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'checkout_state_status'],
                    ['name' => 'checkout_state_full_status'],

                ],
            ],
            [
                'key' => 56,
                'title' => 'Zip/Postal Code',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'checkout_zip_status'],
                    ['name' => 'checkout_zip_full_status'],

                ],
            ],
            [
                'key' => 9,
                'title' => 'Checkout Terms & Conditions',
                'label' => '',
                'referance' => '',
                'data' => [
                   
                    ['name' => 'terms_and_condition_status'],
                    ['name' => 'checkout_note_text'],
                 

                ],
            ],

        ];

        $data = json_decode(json_encode($data));

    @endphp



    @include('admin.settings.partials.main-setting-helper', ['data' => $data])





@endsection
