@php
    $loggedAdmin = Auth::guard('admin')->user();
    $adminName = $loggedAdmin->name ?? 'Admin';
    $adminEmail = $loggedAdmin->email ?? '';
    $adminAvatar = ($loggedAdmin && $loggedAdmin->profile_image)
        ? asset('upload/' . $loggedAdmin->profile_image)
        : asset('assets/admin/design/pro.png');
    $unreadMessageCount = function_exists('unread_message') ? unread_message() : 0;
    $unreadCareerCount = function_exists('unread_careear') ? unread_careear() : 0;
    $totalAlerts = (int) $unreadMessageCount + (int) $unreadCareerCount;
@endphp

<nav class="header-navbar navbar navbar-expand-lg align-items-center navbar-light navbar-shadow fixed-top topbar-interactive">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item">
                    <a class="nav-link menu-toggle" href="javascript:void(0);" onclick="document.body.classList.toggle('menu-expanded')">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
            <div class="d-none d-lg-flex align-items-center topbar-title-wrap">
                <span class="topbar-pill">Admin Console</span>
                <small class="text-muted ml-1">{{ now()->format('D, d M Y') }}</small>
            </div>
        </div>

        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown mr-1">
                <a class="nav-link" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-th-large topbar-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right topbar-dropdown-card">
                    <h6 class="dropdown-header">Quick Actions</h6>
                    <a class="dropdown-item" href="{{ route('admin.news.newses.index') }}">
                        <i class="mr-50 far fa-newspaper"></i> News
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.blogs.index') }}">
                        <i class="mr-50 fas fa-pen-to-square"></i> Blog
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.contacts.index') }}">
                        <i class="mr-50 fas fa-inbox"></i> Contact
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        <i class="mr-50 fas fa-chart-line"></i> Dashboard
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown mr-1">
                <a class="nav-link position-relative" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell topbar-icon topbar-bell-icon"></i>
                    @if($totalAlerts > 0)
                        <span class="badge badge-danger badge-pill notify-badge">{{ $totalAlerts }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right topbar-dropdown-card">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('admin.contacts.index') }}">
                        <span><i class="mr-50 fas fa-envelope"></i> Unread Messages</span>
                        <span class="badge badge-light-primary">{{ $unreadMessageCount }}</span>
                    </a>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('admin.careears.index') }}">
                        <span><i class="mr-50 fas fa-briefcase"></i> Career Requests</span>
                        <span class="badge badge-light-warning">{{ $unreadCareerCount }}</span>
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-lg-flex d-none">
                        <span class="user-name font-weight-bolder">{{ $adminName }}</span>
                        @if($adminEmail)
                            <small class="user-email">{{ $adminEmail }}</small>
                        @endif
                    </div>
                    <span class="avatar">
                        <img class="round" src="{{ $adminAvatar }}" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="mr-50 fas fa-user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mr-50 fas fa-power-off"></i> {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
    .topbar-interactive {
        border-bottom: 1px solid #ecf1f6;
    }
    .topbar-interactive .nav-link {
        color: #1f2937 !important;
    }
    .topbar-interactive .topbar-icon {
        font-size: 18px;
        line-height: 1;
        color: #1f2937 !important;
    }
    .topbar-interactive .topbar-bell-icon {
        font-size: 18px;
        display: inline-block;
    }
    .topbar-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: linear-gradient(135deg, #0f2f45, #1d5b83);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        padding: 5px 10px;
    }
    .notify-badge {
        position: absolute;
        top: 8px;
        right: 5px;
        min-width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        font-size: 10px;
    }
    .topbar-dropdown-card {
        min-width: 250px;
        border-radius: 12px;
        border: 1px solid #e7edf4;
        box-shadow: 0 12px 28px rgba(15, 47, 69, .12);
    }
    .topbar-interactive .dropdown-user .dropdown-user-link {
        display: inline-flex;
        align-items: center;
        gap: 0px;
        padding: 6px 4px;
        /* border: 1px solid #edf1f6; */
        border-radius: 12px;
        max-width: 240px;
    }
    .topbar-interactive .dropdown-user .user-nav {
        min-width: 0;
        display: flex !important;
        flex-direction: column;
        line-height: 1.1;
        text-align: right;
    }
    .topbar-interactive .dropdown-user .user-name {
        display: block;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 15px;
    }
    .topbar-interactive .dropdown-user .user-email {
        display: block;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #6b7280;
        margin-top: 2px;
        font-size: 11px;
        font-weight: 600;
    }
    .topbar-interactive .dropdown-user .avatar {
        flex: 0 0 auto;
    }
    @media (max-width: 1199.98px) {
        .topbar-interactive .dropdown-user .dropdown-user-link {
            max-width: none;
            border: 0;
            padding: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
});
</script>
