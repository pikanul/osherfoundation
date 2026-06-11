@extends('admin.layouts.app')

@section('title', 'Partner Inquiry Details')

@section('content')
<div class="content-wrapper">
    @php
        $links = [
            'Home' => route('admin.dashboard'),
            'Partner Inquiries' => route('admin.partner-inquiries.index'),
            'Details' => '',
        ];
        $rows = [
            'Organization / Institution Name' => $partnerInquiry->organization_name,
            'Type of Organization' => $partnerInquiry->organization_type,
            'Country' => $partnerInquiry->country,
            'Address' => $partnerInquiry->address,
            'Website / Social Media Link' => $partnerInquiry->website_url,
            'Full Name' => $partnerInquiry->contact_name,
            'Designation' => $partnerInquiry->designation,
            'Email Address' => $partnerInquiry->email,
            'Phone / WhatsApp Number' => $partnerInquiry->phone,
            'Area of Partnership Interest' => implode(', ', $partnerInquiry->partnership_interests ?? []),
            'Partnership Idea' => $partnerInquiry->partnership_idea,
            'Support / Collaboration Type' => implode(', ', $partnerInquiry->collaboration_types ?? []),
            'Target Sector or Worker Group' => $partnerInquiry->target_sector,
            'Preferred Geographic Area' => $partnerInquiry->geographic_area,
            'Expected Timeline' => $partnerInquiry->expected_timeline,
            'Submitted At' => optional($partnerInquiry->created_at)->format('d M Y, h:i A'),
        ];
    @endphp
    <x-bread-crumb-component title="Partner Inquiry Details" :links="$links" />
    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ $partnerInquiry->organization_name }}</h4>
                <a href="{{ route('admin.partner-inquiries.index') }}" class="btn btn-outline-primary">Back to List</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                    @foreach($rows as $label => $value)
                        <tr>
                            <th style="width: 280px;">{{ $label }}</th>
                            <td>{!! nl2br(e($value ?: 'N/A')) !!}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Uploaded Document</th>
                        <td>
                            @if($partnerInquiry->document_url)
                                <a href="{{ $partnerInquiry->document_url }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                                    Open Document
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Consent</th>
                        <td>
                            <span class="badge badge-success">Information accuracy confirmed</span>
                            <span class="badge badge-success">Processing consent confirmed</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
