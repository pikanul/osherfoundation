<!DOCTYPE html>
<html lang="en"  data-theme="white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title inertia>{{ settings('app_title', 9) }}</title>
    <style>
        table.MsoNormalTable{
            width: 100%;
            margin: 0;
        }
    </style>

    @viteReactRefresh
    @vite('resources/js/app.jsx')
    @inertiaHead

    <link rel="stylesheet" href="{{ asset('vendor/flipjs/css/themify-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flipjs/css/dflip.min.css') }}">
</head>
<body>
    <!-- This is required! -->
    <input type="hidden" id="root_url" value="{{ url('') }}">
    @inertia

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/flipjs/js/dflip.min.js') }}"></script>
</body>

</html>
