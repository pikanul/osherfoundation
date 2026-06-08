<div class="sidebar-tools">
    <div class="sidebar-search-wrap">
        <input type="search" class="form-control" id="search" placeholder="Find menu items..." />
        <button type="button" id="clearSidebarSearch" class="btn btn-sm btn-light sidebar-clear-btn" title="Clear search">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div id="sidebarSearchMeta" class="sidebar-search-meta d-none"></div>
</div>
<style>
    .main-menu {
        overflow: hidden !important;
    }

    .main-menu .main-menu-content::-webkit-scrollbar {
        width: 8px;
    }
    .main-menu .main-menu-content::-webkit-scrollbar-thumb {
        background: #c9d4e2;
        border-radius: 999px;
    }
    .main-menu .main-menu-content::-webkit-scrollbar-track {
        background: #f4f7fb;
    }
    .sidebar-tools {
        position: sticky;
        top: 0;
        z-index: 3;
        background: #fff;
        padding: 8px 8px 6px;
        border-bottom: 1px solid #edf0f5;
    }
    .sidebar-search-wrap {
        position: relative;
    }
    .sidebar-clear-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        height: 28px;
        width: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        padding: 0;
    }
    .sidebar-search-meta {
        margin-top: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        padding-left: 2px;
    }
    .sidebar-version-badge {
        margin-left: auto;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
        background: #5b9ce7;
        color: #0f2f45;
    }
    .sidebar-version-badge.warn {
        background: #ffe8b3;
        color: #8a4b00;
    }
    .sidebar-separator {
        margin: 10px 12px 6px;
        padding-top: 10px;
        pointer-events: none;
    }
    .sidebar-separator span {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #8a8f98;
        padding: 0 2px;
    }
    #main-menu-navigation > li > a {
        /* margin: 2px 8px; */
        padding: 9px 12px;
        transition: all .18s ease;
    }
    #main-menu-navigation > li > a:hover {
        background: #f2f8ff;
        color: #0f2f45 !important;
        transform: translateX(2px);
    }
    #main-menu-navigation > li.active > a {
        background: linear-gradient(135deg, #0f2f45, #1d5b83);
        color: #fff !important;
        box-shadow: 0 8px 18px rgba(15, 47, 69, .2);
    }
    #main-menu-navigation > li.active > a i,
    #main-menu-navigation > li.active > a span {
        color: #fff !important;
    }
    @media (max-width: 1199.98px) {
        .main-menu .main-menu-content {
            height: calc(100vh - 62px);
            max-height: calc(100vh - 62px);
        }
    }


</style>
@php
    $showWebsiteContent = Auth::hasP('slider')
        || Auth::hasP('projects');
    $showBlogNews = Auth::hasP('blog-category')
        || Auth::hasP('blogs')
        || Auth::hasP('news.categories')
        || Auth::hasP('news.newses');
    $showServicesCrm = Auth::hasP('service_categories')
        || Auth::hasP('contact')
        || Auth::hasP('careear')
        || Auth::hasP('carrearjob');
    $showMediaTeam = Auth::hasP('gallery.photo')
        || Auth::hasP('gallery-visit')
        || Auth::hasP('youtubes')
        || Auth::hasP('clients')
        || Auth::hasP('teams');
    $showAcademicSetup = Auth::hasP('sm_classes')
        || Auth::hasP('eventtypes')
        || Auth::hasP('timetable');
    $showAdminPanel = Auth::hasP('users')
        || Auth::hasP('pages')
        || Auth::hasP('setting')
        || Auth::hasP('mail')
        || Auth::hasP('subscribers');

    $showSystemTools = Auth::hasP('db')
        || Auth::hasP('clear');
    $sidebarAppVersion = $version ?? 'N/A';
    $sidebarUpdateAvailable = (bool) (is_array($systemInfo ?? null)
        ? ($systemInfo['update_available'] ?? false)
        : ($systemInfo->update_available ?? false));
@endphp
<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    <li class="nav-item"><a class="d-flex align-items-center" href="{{ url('/') }}" target="_blank">
            <i class="fas fa-mouse-pointer"></i>
            <span class="menu-title text-truncate">View Site</span></a>
    </li>
    
    <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-title text-truncate">Dashboard</span></a>
    </li>

    @if($showWebsiteContent)
        <li class="sidebar-separator"><span>Website Content</span></li>
    @endif


    @if (Auth::hasP('slider'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.sliders.index') }}"><i
                    class="fa fa-sliders-h" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Slider</span></a>
        </li>
    @endif



    @if (Auth::hasP('project.categories'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.project-categories.index') }}"><i
                    class="fas fa-project-diagram" aria-hidden="true"></i><span class="menu-item text-truncate"
                   >Project Stage</span></a>
        </li>
    @endif


    @if (Auth::hasP('projects'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.projects.index') }}"><i
                    class="fas fa-project-diagram" aria-hidden="true"></i><span class="menu-item text-truncate"
                   >Project</span></a>
        </li>
    @endif


    @if($showBlogNews)
        <li class="sidebar-separator"><span>Blog And News</span></li>
    @endif

    <!-- Blog and News -->
    @if (Auth::hasP('blog-category'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.blog-categories.index') }}">
                <i class="fas fa-tags" aria-hidden="true"></i>
                <span class="menu-item text-truncate">Blog Category</span></a>
        </li>
    @endif

    @if (Auth::hasP('blogs'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.blogs.index') }}"><i
                    class="fa fa-blog" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Blog</span></a>
        </li>
    @endif

    @if (Auth::hasP('news.categories'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.news.categories.index') }}">
                <i class="far fa-newspaper" aria-hidden="true"></i>
                <span class="menu-item text-truncate">News Category</span></a>
        </li>
    @endif

    @if (Auth::hasP('news.newses'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.news.newses.index' && request('news_category_id') == null ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.news.newses.index') }}">
                <i class="fas fa-bullhorn" aria-hidden="true"></i>
                <span class="menu-title text-truncate" data-i18n="Home">All News</span>
            </a>
        </li>
        @foreach (newsCategory() as $newsCategory)
            <li class="nav-item {{ Route::currentRouteName() == 'admin.news.newses.index' && (string) request('news_category_id') === (string) $newsCategory->id ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.news.newses.index', ['news_category_id' => $newsCategory->id]) }}"><i
                        class="fas fa-bullhorn" aria-hidden="true"></i><span class="menu-title text-truncate"
                        data-i18n="Home">{{ $newsCategory->name }}</span></a>
            </li>
        @endforeach
    @endif

    @if($showServicesCrm)
        <li class="sidebar-separator"><span>Services And Engagement</span></li>
    @endif






    @if (Auth::hasP('contact'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.contacts.index') }}"><i
                    class="fas fa-inbox" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Contact</span>
                @if(unread_message() > 0)
                    <span class="badge badge-danger badge-pill ml-auto mr-1">{{ unread_message() }}</span>
                @endif
            </a>
        </li>
    @endif
    @if (Auth::hasP('careear'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.careears.index') }}"><i
                    class="fas fa-briefcase" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Career</span>
                @if(unread_careear() > 0)
                    <span class="badge badge-danger badge-pill ml-auto mr-1">{{ unread_careear() }}</span>
                @endif
            </a>
        </li>
    @endif



    @if($showMediaTeam)
        <li class="sidebar-separator"><span>Media And Team</span></li>
    @endif


    <!-- Gallery ================= Start ================== Gallery -->
    @if (Auth::hasP('gallery.photo'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.gallery.photo.index') }}"><i
                    class="fas fa-camera-retro" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Photo Gallery</span></a>
        </li>
    @endif


    @if (Auth::hasP('youtubes'))
        <li class="nav-item">
            <a class="d-flex align-items-center" href="{{ route('admin.youtubes.index') }}">
                <i class="fab fa-youtube" aria-hidden="true"></i>
                <span class="menu-item text-truncate"> Youtube Video Gallery</span>
            </a>
        </li>
    @endif

    @if (Auth::hasP('clients'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.clients.index') }}"><i
                    class="fas fa-handshake" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Clients</span></a>
        </li>
    @endif

    @if (Auth::hasP('teams'))
        <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('admin.teams.index') }}"><i
                    class="fas fa-user-friends" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Team</span></a>
        </li>
    @endif






    @if(Auth::hasP('eventtypes'))
        <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('admin.eventtypes.index') }}">
                <i class="far fa-calendar-alt"></i>
                <span class="menu-title text-truncate"   data-i18n="Home">Event Types</span></a>
        </li>
    @endif
    @if (Auth::hasP('timetable'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.calender.index' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.calender.index') }}">
                <i class="far fa-clock"></i>
                <span class="menu-title text-truncate" data-i18n="Home">Manage Event</span>
            </a>
        </li>
    @endif

    @if($showAdminPanel)
        <li class="sidebar-separator"><span>Admin Panel</span></li>
    @endif


    @if (Auth::hasP('users'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.admin.index') }}"><i
            class="fas fa-user-shield" aria-hidden="true"></i><span class="menu-title text-truncate"
            data-i18n="Home">Users</span></a>
        </li>
    @endif

    @if (Auth::hasP('pages'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.pages.*' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.pages.index') }}">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
                <span class="menu-title text-truncate" data-i18n="Home">Pages</span>
            </a>
        </li>
    @endif


    @if (Auth::hasP('setting'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.setting.index' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.setting.index', ['page' => 'main']) }}">
                <i class="fas fa-cog" aria-hidden="true"></i>
                <span class="menu-title text-truncate" data-i18n="Home">Settings</span>
            </a>
        </li>

    @endif
    @if (Auth::hasP('mail'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.mail.index' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.mail.index', ['page' => 'main']) }}">
                <i class="fas fa-envelope-open-text" aria-hidden="true"></i>
                <span class="menu-title text-truncate" data-i18n="Home">Email Setting</span>
            </a>
        </li>
    @endif

    @if (Auth::hasP('subscribers'))
        <li class="nav-item {{ Route::currentRouteName() == 'admin.subscribers.index' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.subscribers.index') }}">
                <i class="fas fa-user-check" aria-hidden="true"></i>
                <span class="menu-title text-truncate" data-i18n="Home">Subscribers</span>
            </a>
        </li>
    @endif



    @if($showSystemTools)
        <li class="sidebar-separator"><span>System Tools</span></li>
    @endif
     <li class="nav-item {{ Route::currentRouteName() == 'admin.system-information.index' ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('admin.system-information.index') }}">
                <i class="fas fa-microchip" aria-hidden="true"></i>
                <span class="menu-title text-truncate">System Information</span>
                <span class="sidebar-version-badge {{ $sidebarUpdateAvailable ? 'warn' : '' }}">
                    {{ $sidebarUpdateAvailable ? 'Update' : 'v' . $sidebarAppVersion }}
                </span>
            </a>
        </li>
    @if (Auth::hasP('db'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.database.backup') }}"><i
                    class="fa fa-database" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Database Backup</span></a>
        </li>

    @endif
    @if (Auth::hasP('clear'))
        <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('admin.clear') }}"><i
                    class="fas fa-broom" aria-hidden="true"></i><span class="menu-title text-truncate"
                    data-i18n="Home">Clear</span></a>
        </li>

    @endif




</ul>


<script>
window.addEventListener('load', function () {
    const currentPath = window.location.pathname.replace(/\/+$/, '');
    const currentSearch = window.location.search || '';
    const navLinks = document.querySelectorAll('.navigation li a');
    navLinks.forEach(function (link) {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('javascript:') || href.startsWith('#')) return;
        try {
            const linkUrl = new URL(href, window.location.origin);
            const linkPath = linkUrl.pathname.replace(/\/+$/, '');
            const linkSearch = linkUrl.search || '';
            const isExactMatch = linkPath && linkPath === currentPath && linkSearch === currentSearch;

            if (isExactMatch) {
                link.parentElement.classList.add('active');
                link.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } catch (e) {}
        const nextSib = link.nextElementSibling;
        if (nextSib && nextSib.tagName === 'UL') {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                link.parentElement.classList.toggle('open');
            });
        }
    });
});
</script>



<script>
const searchInput = document.getElementById('search');
const sidebarList = document.getElementById('main-menu-navigation');
const clearSearchBtn = document.getElementById('clearSidebarSearch');
const sidebarSearchMeta = document.getElementById('sidebarSearchMeta');
if (searchInput && sidebarList) {
  searchInput.addEventListener('input', function() {
    const filter = searchInput.value.toLowerCase();
    const allItems = Array.from(sidebarList.children).filter(item => item.tagName === 'LI');
    const mainItems = allItems.filter(item => !item.classList.contains('sidebar-separator'));
    let firstMatch = null;
    let visibleCount = 0;
    mainItems.forEach(mainItem => {
      const nestedUl = Array.from(mainItem.children).find(child => child.tagName === 'UL');
      const mainLink = Array.from(mainItem.children).find(child => child.tagName === 'A');
      let mainText = (mainLink ? mainLink.textContent : mainItem.textContent).trim().toLowerCase();
      let matchFound = false;
      if (nestedUl) {
        const nestedItems = Array.from(nestedUl.children).filter(item => item.tagName === 'LI');
        nestedItems.forEach(nestedItem => {
          const text = nestedItem.textContent.toLowerCase();
          if (!filter || text.includes(filter)) {
            nestedItem.style.display = '';
            matchFound = true;
            if (!firstMatch) firstMatch = nestedItem;
          } else {
            nestedItem.style.display = 'none';
          }
        });
        if (filter && matchFound) {
          mainItem.classList.add('open');
        }
      }
      if (!filter || mainText.includes(filter) || matchFound) {
        mainItem.style.display = '';
        visibleCount += 1;
        if ((mainText.includes(filter) || !filter) && !firstMatch) firstMatch = mainItem;
      } else {
        mainItem.style.display = 'none';
      }
    });
    allItems.forEach((item, index) => {
      if (!item.classList.contains('sidebar-separator')) return;
      let hasVisibleItemUnderSeparator = false;
      for (let i = index + 1; i < allItems.length; i++) {
        const nextItem = allItems[i];
        if (nextItem.classList.contains('sidebar-separator')) break;
        if (nextItem.style.display !== 'none') {
          hasVisibleItemUnderSeparator = true;
          break;
        }
      }
      item.style.display = hasVisibleItemUnderSeparator ? '' : 'none';
    });
    if (firstMatch) firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
    if (sidebarSearchMeta) {
      if (!filter) {
        sidebarSearchMeta.classList.add('d-none');
      } else {
        sidebarSearchMeta.classList.remove('d-none');
        sidebarSearchMeta.textContent = `${visibleCount} result${visibleCount === 1 ? '' : 's'} found`;
      }
    }
  });
  if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function () {
      searchInput.value = '';
      searchInput.dispatchEvent(new Event('input'));
      searchInput.focus();
    });
  }
}
</script>
