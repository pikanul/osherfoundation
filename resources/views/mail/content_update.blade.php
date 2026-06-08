<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Update</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:10px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f2f45;color:#ffffff;padding:18px 24px;font-size:18px;font-weight:700;">
                            OSHE Foundation Update
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px;font-size:14px;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:.04em;">
                                {{ strtoupper($payload['type'] ?? 'update') }}
                            </p>
                            @if(!empty($payload['category']))
                                <p style="margin:0 0 8px;font-size:12px;color:#0f2f45;font-weight:700;text-transform:uppercase;">
                                    {{ $payload['category'] }}
                                </p>
                            @endif
                            <h1 style="margin:0 0 12px;font-size:24px;line-height:1.3;color:#0f172a;">
                                {{ $payload['title'] ?? 'New Update' }}
                            </h1>
                            @if(!empty($payload['publish_date']))
                                <p style="margin:0 0 10px;font-size:12px;line-height:1.4;color:#64748b;">
                                    Publish Date: {{ $payload['publish_date'] }}
                                </p>
                            @endif
                            @if(!empty($payload['image_url']))
                                <div style="margin:0 0 14px;">
                                    <img src="{{ $payload['image_url'] }}" alt="Update image" style="width:100%;max-height:240px;object-fit:cover;border-radius:8px;display:block;">
                                </div>
                            @endif
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#334155;">
                                {{ $payload['summary'] ?? 'A new update is available on OSHE Foundation.' }}
                            </p>
                            <a href="{{ $payload['url'] ?? url('/') }}" style="display:inline-block;background:#0f2f45;color:#ffffff;text-decoration:none;padding:11px 18px;border-radius:999px;font-size:14px;font-weight:700;">
                                Read Full Update
                            </a>

                            @if(!empty($payload['sub_items']) && is_array($payload['sub_items']))
                                <div style="margin-top:22px;padding-top:16px;border-top:1px solid #e5e7eb;">
                                    <h3 style="margin:0 0 10px;font-size:16px;color:#0f172a;">More Updates</h3>

                                    @foreach($payload['sub_items'] as $subItem)
                                        <div style="padding:10px 0;border-bottom:1px solid #eef2f7;">
                                            <a href="{{ $subItem['url'] ?? url('/') }}" style="font-size:14px;line-height:1.4;font-weight:700;color:#0f2f45;text-decoration:none;">
                                                {{ $subItem['title'] ?? '' }}
                                            </a>
                                            @if(!empty($subItem['publish_date']))
                                                <div style="margin-top:4px;font-size:11px;color:#64748b;">
                                                    Publish Date: {{ $subItem['publish_date'] }}
                                                </div>
                                            @endif
                                            @if(!empty($subItem['summary']))
                                                <p style="margin:6px 0 0;font-size:13px;line-height:1.6;color:#475569;">
                                                    {{ $subItem['summary'] }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 24px 24px;font-size:12px;line-height:1.6;color:#64748b;">
                            You are receiving this email because you subscribed to OSHE Foundation updates.
                            <br>
                            <a href="{{ route('subscribe.remove', $md5email) }}" style="color:#0f2f45;">Unsubscribe</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
