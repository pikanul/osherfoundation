<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm Subscription</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            background: #2563eb;
            color: #ffffff;
            padding: 18px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            padding: 25px;
            color: #333;
            line-height: 1.7;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff !important;
            padding: 12px 22px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-danger {
            background: #dc2626;
        }
        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        Confirm Your Subscription
    </div>

    <div class="content">
        <p>Hello,</p>

        <p>
            Thanks for subscribing to <strong>{{ settings('app_title', 9) }}</strong>.
            Please confirm your email address by clicking the button below:
        </p>

        <p style="text-align:center;">
            <a href="{{ route('subscribe.verify', $md5email) }}" class="btn">
                Verify Subscription
            </a>
        </p>

        <p>
            If you did not request this, you can ignore this email or unsubscribe:
        </p>

        <p style="text-align:center;">
            <a href="{{ route('subscribe.remove', $md5email) }}" class="btn btn-danger">
                Unsubscribe
            </a>
        </p>

        <p>
            Or copy this verification link:
            <br>
            <a href="{{ route('subscribe.verify', $md5email) }}">
                {{ route('subscribe.verify', $md5email) }}
            </a>
        </p>

        <p>
            Thanks,<br>
            <strong>{{ settings('app_title', 9) }}</strong>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ settings('app_title', 9) }}
    </div>
</div>

</body>
</html>
