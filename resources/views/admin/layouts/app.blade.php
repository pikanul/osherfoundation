<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>@yield('title') | {{ settings('app_title', 9) }}</title>
    <link rel="icon" type="image/x-icon" href="{{ settings('app_fav_image',9) }}">

    @include('admin.layouts.css')
    @stack('style')
</head>

<body class="vertical-layout vertical-menu-modern footer-static  pace-done navbar-sticky menu_collapsed">
    @include('admin.layouts.topbar')
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row align-items-center">
                <li class="nav-item mr-auto" style=" margin-top: 12px;">
                    <a class="navbar-brand d-flex gap-2 align-items-center" href="{{route('admin.dashboard')}}">
                        <img style="height: 30px; width: 100%;"
                            src="{{ settings('app_image', 9) }}">
                         
                    </a>
                </li>
                <li class="nav-item nav-toggle" onclick="document.body.classList.toggle('menu-expanded')">
                    <a class="nav-link modern-nav-toggle pr-0" >
                        <i class="d-block d-xl-none text-primary toggle-icon font-medium-4 fa fa-times" ></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content"> @include('admin.layouts.sidebar')</div>
    </div>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        @yield('content')
    </div>


    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy;
                <?php echo date("Y"); ?><a class="ml-25" href="https://bdsofttechnology.com/" target="_blank"> BD SOFT
                    TECHNOLOGY </a><span class="d-none d-sm-inline-block"> All rights Reserved</span></span><span
                class="float-md-right d-none d-md-block">Version {{ $version }}<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>

    @include('admin.layouts.scripts')
    @include('admin.layouts.modal_ajax')

    <div class="modal fade" id="adminUpdateModal" tabindex="-1" role="dialog" aria-labelledby="adminUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="adminUpdateModalLabel">Update Available</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-1"><strong>Current Version:</strong> <span id="update-current-version">-</span></div>
                    <div class="mb-1"><strong>Latest Version:</strong> <span id="update-latest-version">-</span></div>
                    <div class="mb-1"><strong>License Key:</strong> <span id="update-license-key">-</span></div>
                    <div class="mb-1"><strong>License Status:</strong> <span id="update-license-status">-</span></div>
                    <div class="mb-1"><strong>Last Checked:</strong> <span id="update-last-checked">-</span></div>
                    <div class="mb-1"><strong>Release Note:</strong>
                        <div id="update-release-note" class="small text-muted mt-50">No release note.</div>
                    </div>
                    <div id="update-modal-message" class="alert alert-info mb-0 mt-1" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="update-check-now-btn">Check Now</button>
                    <button type="button" class="btn btn-light" id="update-later-btn" data-dismiss="modal">Update Later</button>
                    <button type="button" class="btn btn-primary" id="update-run-btn">Update Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="licenseRequiredModal" tabindex="-1" role="dialog" aria-labelledby="licenseRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="licenseRequiredModalLabel">License Key Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">No license key is configured. Please add your license key to continue update verification and system update features.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" id="license-later-btn" data-dismiss="modal">Later (10 min)</button>
                    <a href="{{ route('admin.system-information.index') }}" class="btn btn-primary">Add License Key</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        if (typeof window === 'undefined') return;
        if (typeof $ !== 'undefined' && $.fn && $.fn.modal && $.fn.modal.Constructor) {
            if ($.fn.modal.Constructor.Default) {
                $.fn.modal.Constructor.Default.backdrop = 'static';
                $.fn.modal.Constructor.Default.keyboard = false;
            }
            if ($.fn.modal.Constructor.DEFAULTS) {
                $.fn.modal.Constructor.DEFAULTS.backdrop = 'static';
                $.fn.modal.Constructor.DEFAULTS.keyboard = false;
            }
        }

        const CHECK_ENDPOINT = "{{ route('admin.system.check-update') }}";
        const RUN_ENDPOINT = "{{ route('admin.system.run-update') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const LATER_KEY = "admin_update_remind_later_until";
        const LICENSE_LATER_KEY = "admin_license_popup_later_until";
        const INTERVAL_MS = 2 * 60 * 60 * 1000; // 2 hours
        const LICENSE_LATER_MS = 10 * 60 * 1000; // 10 minutes
        const LICENSE_KEY = @json(data_get($systemInfo, 'license_key', ''));
        const IS_LICENSE_PAGE = @json(request()->routeIs('admin.system-information.index'));

        const $modal = $('#adminUpdateModal');
        const $message = $('#update-modal-message');
        const $runBtn = $('#update-run-btn');
        const $checkBtn = $('#update-check-now-btn');
        const $laterBtn = $('#update-later-btn');
        const $licenseLaterBtn = $('#license-later-btn');

        let latestPayload = null;

        function setBusyState(isBusy) {
            $runBtn.prop('disabled', isBusy);
            $checkBtn.prop('disabled', isBusy);
            $laterBtn.prop('disabled', isBusy);
            $runBtn.text(isBusy ? 'Updating...' : 'Update Now');
        }

        function maskLicenseKey(value) {
            const raw = (value || '').toString().trim();
            if (!raw) return 'Not set';
            if (raw.length <= 6) return raw;
            return raw.slice(0, 3) + '******' + raw.slice(-3);
        }

        function text(value, fallback = '-') {
            if (value === null || value === undefined || value === '') return fallback;
            return value;
        }

        function renderModal(payload) {
            latestPayload = payload || {};

            $('#update-current-version').text(text(payload.version, 'N/A'));
            $('#update-latest-version').text(text(payload.latest_version, 'N/A'));
            $('#update-license-key').text(maskLicenseKey(payload.license_key));
            $('#update-license-status').text(text(payload.license_status, 'unknown'));
            $('#update-last-checked').text(text(payload.last_checked_at, 'N/A'));
            $('#update-release-note').text(text(payload.release_note, 'No release note.'));

            const updateAvailable = !!(payload.update || payload.update_available);
            const hasDownload = !!(payload.download_url && payload.download_url.length);
            const licenseValid = String(payload.license_status || '').toLowerCase() === 'valid';

            $runBtn.prop('disabled', !(updateAvailable && hasDownload && licenseValid));
            if (!licenseValid) {
                showMessage('License is not valid. Save a valid license first.', 'danger');
            } else if (!updateAvailable) {
                showMessage('System is already up to date.', 'info');
            } else {
                showMessage('New update found. You can update now or later.', 'warning');
            }
        }

        function showMessage(message, type = 'info') {
            $message
                .removeClass('alert-info alert-success alert-danger alert-warning')
                .addClass('alert-' + type)
                .text(message || '')
                .show();
        }

        function shouldSkipModal() {
            const laterUntil = parseInt(localStorage.getItem(LATER_KEY) || '0', 10);
            return laterUntil > Date.now();
        }

        function setRemindLater() {
            localStorage.setItem(LATER_KEY, String(Date.now() + INTERVAL_MS));
        }        function shouldSkipLicenseModal() {
            const laterUntil = parseInt(sessionStorage.getItem(LICENSE_LATER_KEY) || '0', 10);
            return laterUntil > Date.now();
        }

        function setLicenseRemindLater() {
            sessionStorage.setItem(LICENSE_LATER_KEY, String(Date.now() + LICENSE_LATER_MS));
        }

        function hasValidLicenseKey() {
            return typeof LICENSE_KEY === 'string' && LICENSE_KEY.trim() !== '';
        }

        function maybeShowLicenseRequiredModal() {
            if (IS_LICENSE_PAGE || shouldSkipLicenseModal()) {
                return;
            }

            if (!hasValidLicenseKey()) {
                $('#licenseRequiredModal').modal('show');
            }
        }

        function checkUpdate(force = false, showModalIfUpdate = true) {
            const url = force ? (CHECK_ENDPOINT + '?force=1') : CHECK_ENDPOINT;
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(payload => {
                renderModal(payload);

                const updateAvailable = !!(payload.update || payload.update_available);
                if (showModalIfUpdate && updateAvailable && !shouldSkipModal()) {
                    $modal.modal('show');
                }
                return payload;
            })
            .catch(() => {
                showMessage('Could not check update right now.', 'danger');
                return null;
            });
        }

        function runUpdate() {
            if (!latestPayload) return;
            setBusyState(true);

            fetch(RUN_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    download_url: latestPayload.download_url || ''
                })
            })
            .then(res => res.json())
            .then(payload => {
                if (payload && payload.success) {
                    showMessage(payload.message || 'Update completed.', 'success');
                    localStorage.removeItem(LATER_KEY);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1800);
                    return;
                }
                showMessage((payload && payload.message) ? payload.message : 'Update failed.', 'danger');
            })
            .catch(() => {
                showMessage('Update request failed.', 'danger');
            })
            .finally(() => {
                setBusyState(false);
            });
        }

        $checkBtn.on('click', function () {
            setBusyState(true);
            checkUpdate(true, true).finally(() => setBusyState(false));
        });

        $runBtn.on('click', function () {
            runUpdate();
        });

        $laterBtn.on('click', function () {
            setRemindLater();
        });

        $licenseLaterBtn.on('click', function () {
            setLicenseRemindLater();
        });

        // Initial popup for missing license key.
        maybeShowLicenseRequiredModal();

        // Initial check after login page load.
        checkUpdate(false, true);

        // Long session check every 2 hours.
        setInterval(function () {
            checkUpdate(false, true);
        }, INTERVAL_MS);
    })();
    </script>





</body>
<!-- END: Body-->

</html>




 


