<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="SOURCES">
    <title>Login | {{ settings('app_title', 9) }}</title>
    <link rel="apple-touch-icon" href="{{ settings('app_fav_image', 9) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ settings('app_fav_image', 9) }}">

    @include('admin.layouts.css')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg-deep: #071923;
            --bg-mid: #0f2f45;
            --bg-teal: #157f74;
            --accent: #f4b740;
            --card: #ffffff;
            --text-main: #0e2a3d;
            --text-soft: #4e6878;
        }

        body {
            font-family: "Manrope", sans-serif;
            background: var(--bg-deep);
        }

        .login-shell {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 15% 20%, rgba(244, 183, 64, 0.25), transparent 35%),
                radial-gradient(circle at 80% 10%, rgba(21, 127, 116, 0.4), transparent 40%),
                linear-gradient(130deg, rgba(7, 25, 35, 0.96), rgba(9, 37, 54, 0.9)),
                url('{{ settings('login_admin_image', 9) }}');
            background-size: cover;
            background-position: center;
        }

        .shape {
            position: absolute;
            border-radius: 999px;
            filter: blur(2px);
            opacity: .45;
            animation: floatY 7s ease-in-out infinite;
        }

        .shape-a {
            width: 220px;
            height: 220px;
            background: linear-gradient(150deg, #f4b740, #d2852e);
            top: -70px;
            right: 14%;
        }

        .shape-b {
            width: 180px;
            height: 180px;
            background: linear-gradient(150deg, #26b9a6, #0a6d7f);
            bottom: -60px;
            left: 12%;
            animation-delay: .7s;
        }

        .login-grid {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            z-index: 1;
        }

        .brand-box {
            color: #ebf8ff;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            padding: 2rem;
            background: linear-gradient(150deg, rgba(13, 47, 71, 0.82), rgba(14, 83, 104, 0.58));
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(4px);
            animation: enterUp .6s ease both;
        }

        .brand-title {
            font-family: "Space Grotesk", sans-serif;
            font-weight: 700;
            font-size: 1.9rem;
            line-height: 1.2;
        }

        .brand-line {
            width: 70px;
            height: 4px;
            border-radius: 8px;
            background: linear-gradient(90deg, #f4b740, #ffe0a4);
            margin: .8rem 0 1rem;
        }

        .brand-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: .65rem .8rem;
            margin-bottom: .7rem;
            font-size: .92rem;
        }

        .login-box {
            background: var(--card);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 28px 65px rgba(7, 24, 38, 0.42);
            animation: enterUp .6s ease .12s both;
        }

        .login-title {
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: .4rem;
        }

        .login-sub {
            color: var(--text-soft);
            margin-bottom: 1.3rem;
        }

        .input-label {
            font-weight: 700;
            color: #1d3d50;
            margin-bottom: .45rem;
        }

        .login-box .form-control,
        .login-box .input-group-text {
            border: 1px solid #ccdae2;
            background: #fdfefe;
        }

        .login-box .form-control {
            height: 46px;
            border-left: 0;
        }

        .login-box .input-group-text {
            border-radius: 10px 0 0 10px;
            color: #2b556c;
        }

        .login-box .input-group .form-control:last-child {
            border-radius: 0 10px 10px 0;
        }

        .login-box .form-control:focus {
            border-color: #1a8a7d;
            box-shadow: 0 0 0 .2rem rgba(26, 138, 125, 0.14);
        }

        .remember-text {
            color: #27495e;
        }

        .btn-login {
            height: 46px;
            border: 0;
            border-radius: 10px;
            font-weight: 800;
            letter-spacing: .02em;
            background: linear-gradient(100deg, var(--bg-mid), var(--bg-teal));
        }

        .btn-login:hover {
            filter: brightness(1.06);
            transform: translateY(-1px);
        }

        @keyframes enterUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(14px); }
        }

        @media (max-width: 991px) {
            .login-grid {
                padding: 1.25rem 0;
            }

            .brand-box,
            .login-box {
                border-radius: 16px;
                padding: 1.25rem;
            }

            .brand-title {
                font-size: 1.45rem;
            }
        }
    </style>
</head>
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <div class="app-content content login-shell">
        <span class="shape shape-a"></span>
        <span class="shape shape-b"></span>

        <div class="content-wrapper">
            <div class="content-body login-grid px-2 px-md-4">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-lg-6 pr-lg-3 mb-2 mb-lg-0">
                            <div class="brand-box">
                                <h2 class="brand-title">OSHE Foundation Admin Workspace</h2>
                                <div class="brand-line"></div>
                                <p class="mb-2" style="opacity: .95;">
                                    One secure space to manage events, content, and platform operations.
                                </p>
                                <div class="brand-item"><i class="fa fa-check-circle mr-50"></i> Structured control panel for daily publishing</div>
                                <div class="brand-item"><i class="fa fa-check-circle mr-50"></i> Fast access to modules and admin tools</div>
                                <div class="brand-item mb-0"><i class="fa fa-check-circle mr-50"></i> Stable and secure authentication workflow</div>
                            </div>
                        </div>

                        <div class="col-lg-6 pl-lg-3">
                            <div class="login-box">
                                <h1 class="login-title">Sign In</h1>
                                <p class="login-sub">Use your admin credentials to continue.</p>

                                @if ($errors->any())
                                    <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-danger">
                                        <div class="alert-body font-small-2 mb-0">
                                            @foreach ($errors->all() as $error)
                                                <p class="mb-25"><small>{{ $error }}</small></p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <form class="auth-login-form mt-1" action="{{ route('admin.loginCheck') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label class="input-label" for="login-email">Email Address</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            </div>
                                            <input
                                                type="email"
                                                name="email"
                                                id="login-email"
                                                class="form-control"
                                                value="{{ old('email') }}"
                                                placeholder="admin@example.com"
                                                autofocus
                                                tabindex="1"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="input-label" for="login-password">Password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                                            </div>
                                            <input
                                                type="password"
                                                id="login-password"
                                                class="form-control form-control-merge"
                                                name="password"
                                                placeholder="Enter password"
                                                tabindex="2"
                                                required
                                            />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} tabindex="3" />
                                            <label class="custom-control-label remember-text" for="remember">Remember Me</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-login btn-block" tabindex="4">Continue To Dashboard</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.scripts')
</body>
</html>
