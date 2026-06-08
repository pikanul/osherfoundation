<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Alert</title>

</head>
<body style="margin:0;padding:0;background:#f2f4f7;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:18px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="700" cellspacing="0" cellpadding="0" style="max-width:700px;background:#ffffff;border:1px solid #e5e7eb;">
                    @php
                        $newslater_banner_top_image = $payload['newslater_banner_top_image'] ?? settings('newslater_banner_top_image', 27);
                    @endphp
                    @if(!empty($newslater_banner_top_image))
                        <tr>
                            <td style="padding:10px; padding-bottom: 0">
                                <img src="{{ $newslater_banner_top_image }}" alt="Newsletter banner" style="width:100%;max-width:700px;height:auto;object-fit:cover;display:block;">
                            </td>
                        </tr>
                    @endif
                    @php
                        $topBanner = $payload['newsletter_banner'] ?? settings('newslater_banner_image', 27);
                    @endphp
                    @if(!empty($topBanner))
                        <tr>
                            <td style="padding:10px; padding-top: 0">
                                <img src="{{ $topBanner }}" alt="Newsletter banner" style="width:100%;max-width:700px;height:auto;object-fit:cover;display:block;">
                            </td>
                        </tr>
                    @endif


                    <tr>
                        <td style="padding:0 00px 0;background:#efefef;">
                           <div style="height:2px;background:#ef2f46;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 0px 8px;">
                            <div style="font-size:12px;line-height:1.25;color:#23095a;font-weight:700;margin:0 0 8px; padding: 0 20px ">
                                {{ $payload['category'] ?? 'News' }}
                            </div>
                            <div style="height:2px;background:#ef2f46;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:12px 20px 8px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="65%" style="padding:0 14px 0 0;vertical-align:top;">
                                        <h1 style="margin:0 0 10px;color:#1d42c5;font-size:14px;line-height:1.2;font-weight:700;">
                                            {{ $payload['title'] ?? 'News Update' }}
                                        </h1>
                                        <div style="margin:0 0 8px;font-size:12px;color:#64748b;font-weight:600;">
                                            Publish Date:
                                            {{ !empty($payload['publish_date']) ? $payload['publish_date'] : now()->format('d M Y') }}
                                        </div>
                                        <p style="margin:0;color:#1f2937;font-size:10px;line-height:1.6;max-height:9.6em;overflow:hidden;">
                                            {{ \Illuminate\Support\Str::limit((string) ($payload['summary'] ?? 'A new update is available.'), 520) }}
                                        </p>
                                    </td>
                                    <td width="35%" style="vertical-align:top;">
                                        @if(!empty($payload['image_url']))
                                            <img src="{{ $payload['image_url'] }}" alt="News image" style="width:100%;height:180px;object-fit:cover;display:block;">
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:8px 15px 15px;">
                            <a href="{{ $payload['url'] ?? url('/') }}" style="display:inline-block;padding:7px 9px;background:#1d42c5;color:#fff;text-decoration:none;font-size:16px;font-weight:700;line-height:1.1;border-radius:2px;">
                                Read more
                            </a>
                        </td>
                    </tr>

                    @if(!empty($payload['previous_news_by_category']) && is_array($payload['previous_news_by_category']))
                        <tr>
                            <td style="padding:0 20px 18px; margin-top: 20px;">
                                <div style="height:1px;background:#e5e7eb;"></div>

                            </td>
                        </tr>

                        @foreach($payload['previous_news_by_category'] as $categoryName => $items)
                            <tr>
                                <td style="padding:0 20px 8px;">
                                    <div style="font-size:12px;line-height:1.25;color:#23095a;font-weight:700;margin:0 0 8px;">
                                        {{ $categoryName }}
                                    </div>
                                    <div style="height:2px;background:#ef2f46;"></div>
                                </td>
                            </tr>
                            @foreach($items as $item)
                                <tr>
                                    <td style="padding:10px 20px 14px;border-bottom:1px solid #e5e7eb;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="180" style="padding:0 12px 0 0;vertical-align:top;">
                                                    @if(!empty($item['image_url']))
                                                        <img src="{{ $item['image_url'] }}" alt="News thumbnail" style="width:170px;height:120px;object-fit:cover;display:block;">
                                                    @endif
                                                </td>
                                                <td style="padding:0;vertical-align:top;">
                                                    <a href="{{ $item['url'] ?? url('/') }}" style="font-size:12px;line-height:1.3;font-weight:700;color:#1d42c5;text-decoration:none;">
                                                        {{ $item['title'] ?? '' }}
                                                    </a>
                                                    @if(!empty($item['publish_date']))
                                                        <div style="margin:4px 0 0;font-size:10px;color:#64748b;font-weight:600;">
                                                            Publish Date: {{ $item['publish_date'] }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($item['summary']))
                                                        <p style="margin:8px 0 0;color:#1f2937;font-size:10px;line-height:1.6;max-height:9.6em;overflow:hidden;">
                                                            {{ \Illuminate\Support\Str::limit((string) ($item['summary'] ?? ''), 520) }}
                                                            <a href="{{ $item['url'] ?? url('/') }}" style="color:#1d42c5;text-decoration:underline;">Read more</a>
                                                        </p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif



                     @php
                        $newslater_footer_image = $payload['newslater_footer_image'] ?? settings('newslater_footer_image', 27);
                    @endphp
                    @if(!empty($newslater_footer_image))
                        <tr>
                            <td style="padding:10px; padding-top: 0">
                                <a href="{{ url('/about-project') }}" target="_blank" style="display:inline-block;">
                                  <img src="{{ $newslater_footer_image }}" alt="Newsletter banner" style="width:100%;max-width:700px;height:auto;object-fit:cover;display:block;">
                                </a>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding:10px 20px 18px;">
                            <div style="height:1px;background:#e5e7eb;"></div>
                            <p style="margin:12px 0 0;font-size:12px;line-height:1.6;color:#6b7280;">
                                You are receiving this email because you subscribed to updates from {{ settings('app_title', 9) }}.
                                <br>
                                <a href="{{ route('subscribe.remove', $md5email) }}" style="color:#0b3a75;">Unsubscribe</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
