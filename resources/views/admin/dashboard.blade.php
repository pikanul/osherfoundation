@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Dashboard</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Index</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif

        @php
            $moduleCards = [
                ['title' => 'News', 'db' => 'news', 'route' => 'admin.news.newses.index', 'permission' => 'news.newses'],
                ['title' => 'Blogs', 'db' => 'blogs', 'route' => 'admin.blogs.index', 'permission' => 'blogs'],
                ['title' => 'Contacts', 'db' => 'contacts', 'route' => 'admin.contacts.index', 'permission' => 'contact'],
                ['title' => 'Subscribers', 'db' => 'subscribers', 'route' => 'admin.mail.index', 'permission' => 'mail.index'],
                ['title' => 'YouTube Gallery', 'db' => 'youtube', 'route' => 'admin.youtubes.index', 'permission' => 'youtubes'],
                ['title' => 'Event Types', 'db' => 'event_types', 'route' => 'admin.eventtypes.index', 'permission' => 'eventtypes'],
                ['title' => 'Team', 'db' => 'teams', 'route' => 'admin.teams.index', 'permission' => 'teams'],
                ['title' => 'Photo Gallery', 'db' => 'galleries', 'route' => 'admin.gallery.photo.index', 'permission' => 'gallery.photo'],
            ];

            $statNews = DB::table('news')->count();
            $statBlogs = DB::table('blogs')->count();
            $statSubscribers = DB::table('subscribers')->where('status', 1)->count();
            $statContacts = DB::table('contacts')->count();

            $todayNews = DB::table('news')->whereDate('publish_date', now()->toDateString())->count();
            $todayBlogs = DB::table('blogs')->whereDate('publish_date', now()->toDateString())->count();
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card dashboard-hero border-0 shadow-sm mb-2">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <h3 class="text-white mb-50">Hello, {{ Auth::user()->name }}</h3>
                            <p class="mb-0 hero-subtitle">{{ settings('app_title', 9) }} admin summary for {{ now()->format('d M Y') }}</p>
                        </div>
                        <div class="d-flex flex-wrap mt-1 mt-md-0">
                            <a href="{{ route('admin.news.newses.index') }}" class="btn btn-light btn-sm mr-1 mb-1">Create News</a>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-light btn-sm mb-1">Create Blog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-md-6 col-xl-3 mb-1">
                <div class="card border-0 shadow-sm kpi-card">
                    <div class="card-body">
                        <small class="text-muted">Total News</small>
                        <h2 class="mb-0">{{ $statNews }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-1">
                <div class="card border-0 shadow-sm kpi-card">
                    <div class="card-body">
                        <small class="text-muted">Total Blogs</small>
                        <h2 class="mb-0">{{ $statBlogs }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-1">
                <div class="card border-0 shadow-sm kpi-card">
                    <div class="card-body">
                        <small class="text-muted">Active Subscribers</small>
                        <h2 class="mb-0">{{ $statSubscribers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-1">
                <div class="card border-0 shadow-sm kpi-card">
                    <div class="card-body">
                        <small class="text-muted">Total Contacts</small>
                        <h2 class="mb-0">{{ $statContacts }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h4 class="mb-0">Quick Access</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            @foreach ($moduleCards as $module)
                                @if (Auth::hasP($module['permission']))
                                    @php $count = DB::table($module['db'])->count(); @endphp
                                    <div class="col-md-6 col-lg-4 mb-1">
                                        <a href="{{ route($module['route']) }}" class="module-tile d-block">
                                            <div class="module-title">{{ $module['title'] }}</div>
                                            <div class="module-count">{{ $count }}</div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-transparent">
                        <h4 class="mb-0">Queue Worker</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">
                            Status:
                            <span id="queue-worker-badge" class="badge {{ $queueWorkerStatus['running'] ? 'badge-success' : 'badge-secondary' }}">
                                {{ $queueWorkerStatus['running'] ? 'Running' : 'Stopped' }}
                            </span>
                        </p>
                        <div class="small text-muted mb-50">Processes: <span id="queue-worker-process">{{ $queueWorkerStatus['process_count'] }}</span></div>
                        <div class="small text-muted mb-50">Pending Jobs: <span id="queue-worker-pending">{{ $queueWorkerStatus['pending_jobs'] ?? 'N/A' }}</span></div>
                        <div class="small text-muted mb-1">Failed Jobs: <span id="queue-worker-failed">{{ $queueWorkerStatus['failed_jobs'] ?? 'N/A' }}</span></div>

                        <div class="d-flex flex-wrap">
                            <form action="{{ route('admin.dashboard.queue_worker.start') }}" method="POST" class="mr-1 mb-1">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Run</button>
                            </form>
                            <form action="{{ route('admin.dashboard.queue_worker.stop') }}" method="POST" class="mr-1 mb-1">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Stop</button>
                            </form>
                            <button type="button" id="queue-worker-refresh" class="btn btn-outline-primary btn-sm mb-1">Refresh</button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h4 class="mb-0">Today</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>News Published</span>
                            <span class="badge badge-primary">{{ $todayNews }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Blogs Published</span>
                            <span class="badge badge-info">{{ $todayBlogs }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-hero {
    background: linear-gradient(135deg, #0f2f45 0%, #1d5b83 55%, #2c7fb0 100%);
}

.hero-subtitle {
    color: rgba(255, 255, 255, 0.78);
}

.kpi-card {
    transition: transform .2s ease, box-shadow .2s ease;
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15, 47, 69, .14) !important;
}

.module-tile {
    border: 1px solid #e6edf5;
    border-radius: 12px;
    padding: 14px;
    background: #fff;
    transition: all .2s ease;
    text-decoration: none !important;
}

.module-tile:hover {
    border-color: #b7d4eb;
    background: #f7fbff;
    transform: translateY(-2px);
}

.module-title {
    color: #475569;
    font-size: 13px;
    font-weight: 600;
}

.module-count {
    margin-top: 6px;
    color: #0f2f45;
    font-size: 24px;
    font-weight: 800;
    line-height: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('queue-worker-refresh');
    if (!refreshBtn) return;

    const statusUrl = "{{ route('admin.dashboard.queue_worker.status') }}";
    const badge = document.getElementById('queue-worker-badge');
    const processEl = document.getElementById('queue-worker-process');
    const pendingEl = document.getElementById('queue-worker-pending');
    const failedEl = document.getElementById('queue-worker-failed');

    const render = (data) => {
        if (!badge) return;
        const running = Boolean(data && data.running);
        badge.textContent = running ? 'Running' : 'Stopped';
        badge.classList.remove('badge-success', 'badge-secondary');
        badge.classList.add(running ? 'badge-success' : 'badge-secondary');
        if (processEl) processEl.textContent = data?.process_count ?? '0';
        if (pendingEl) pendingEl.textContent = data?.pending_jobs ?? 'N/A';
        if (failedEl) failedEl.textContent = data?.failed_jobs ?? 'N/A';
    };

    refreshBtn.addEventListener('click', function() {
        refreshBtn.disabled = true;
        fetch(statusUrl, { headers: { Accept: 'application/json' } })
            .then((res) => res.json())
            .then((data) => render(data))
            .catch(() => {})
            .finally(() => {
                refreshBtn.disabled = false;
            });
    });
});
</script>
@endsection

