<div style="padding:40px 10px; background:#f1f5f9; font-family:Arial,Helvetica,sans-serif;">
    <div style="
        background:#ffffff;
        border-radius:12px;
        max-width:600px;
        margin:0 auto;
        box-shadow:0 4px 12px rgba(0,0,0,0.08);
        overflow:hidden;
    ">

        <!-- Header -->
        <div style="background:#2563eb; padding:20px 25px; color:#ffffff;">
            <h2 style="margin:0; font-size:20px;">
                {{ settings('app_title', 9) }}
            </h2>
            <p style="margin:5px 0 0; font-size:13px; opacity:0.9;">
                System Notification
            </p>
        </div>

        <!-- Body -->
        <div style="padding:25px; font-size:14px; color:#111827;">

            <p style="margin:0 0 10px;">Dear Sir,</p>

            <p style="margin:0 0 15px;">
                <strong>{{ auth()->user()->name }}</strong>
            </p>

            <p style="margin:0 0 5px; color:#374151;">
                <strong>Date:</strong> {{ date('Y-m-d h:i:s A') }}
            </p>

            <p style="margin:0 0 20px; color:#374151;">
                <strong>To:</strong> {{ $mailInfo_data['user'] }}
            </p>

            <!-- Success Message -->
            <div style="
                margin:20px 0;
                background:#dcfce7;
                color:#166534;
                border-left:5px solid #22c55e;
                padding:12px 15px;
                border-radius:6px;
                font-weight:bold;
            ">
                ✔ Success
            </div>

            <!-- Message Body -->
            <div style="
                margin:15px 0;
                background:#eff6ff;
                color:#1e40af;
                border-left:5px solid #3b82f6;
                padding:15px;
                border-radius:6px;
                line-height:1.6;
            ">
                {{ $mailInfo_data['body'] }}
            </div>

            <p style="margin-top:30px;">
                Best regards,<br>
                <strong>{{ settings('app_title', 9) }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="
            background:#f8fafc;
            text-align:center;
            padding:12px;
            font-size:12px;
            color:#6b7280;
        ">
            © {{ date('Y') }} {{ settings('app_title', 9) }}. All rights reserved.
        </div>

    </div>
</div>
