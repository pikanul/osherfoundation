<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Partner Inquiry</title>
</head>
<body style="margin:0;background:#f3f6f5;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
<div style="max-width:720px;margin:0 auto;padding:24px;">
    <div style="background:#007760;color:#fff;padding:18px 22px;border-radius:8px 8px 0 0;">
        <h1 style="margin:0;font-size:22px;">New Partner With Us Inquiry</h1>
    </div>
    <div style="background:#fff;padding:22px;border:1px solid #dce8e4;border-top:0;border-radius:0 0 8px 8px;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            @foreach ([
                'Organization / Institution Name' => $inquiry->organization_name,
                'Type of Organization' => $inquiry->organization_type,
                'Country' => $inquiry->country,
                'Contact Person Name' => $inquiry->contact_name,
                'Designation' => $inquiry->designation,
                'Email' => $inquiry->email,
                'Phone / WhatsApp' => $inquiry->phone,
                'Area of Partnership Interest' => implode(', ', $inquiry->partnership_interests ?? []),
                'Proposed Partnership Idea' => $inquiry->partnership_idea,
                'Type of Collaboration' => implode(', ', $inquiry->collaboration_types ?? []),
                'Target Sector / Worker Group' => $inquiry->target_sector,
                'Preferred Geographic Area' => $inquiry->geographic_area,
                'Expected Timeline' => $inquiry->expected_timeline,
                'Submission Date and Time' => optional($inquiry->created_at)->format('d M Y, h:i A'),
            ] as $label => $value)
                <tr>
                    <td style="width:38%;padding:9px;border-bottom:1px solid #eef2f1;font-weight:bold;color:#064e3b;">{{ $label }}</td>
                    <td style="padding:9px;border-bottom:1px solid #eef2f1;">{{ $value ?: 'N/A' }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="width:38%;padding:9px;border-bottom:1px solid #eef2f1;font-weight:bold;color:#064e3b;">Uploaded File</td>
                <td style="padding:9px;border-bottom:1px solid #eef2f1;">
                    @if($inquiry->document_url)
                        <a href="{{ $inquiry->document_url }}">{{ $inquiry->document_url }}</a>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>

        <p style="margin-top:22px;text-align:center;">
            <a href="{{ $adminUrl }}" style="display:inline-block;background:#007760;color:#fff;text-decoration:none;padding:12px 18px;border-radius:6px;font-weight:bold;">
                View Full Submission
            </a>
        </p>
    </div>
</div>
</body>
</html>
