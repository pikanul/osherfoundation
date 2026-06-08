@extends('admin.layouts.app')

@section('title', 'System Information')

@section('content')
<div class="content-wrapper">
    @php
        $links = [
            'Home' => route('admin.dashboard'),
            'System Information' => route('admin.system-information.index'),
        ];
        $licenseReady = !empty($systemInfo->license_key) && preg_match('/^[A-Za-z0-9\-]{10,}$/', (string) $systemInfo->license_key);
    @endphp

    <x-bread-crumb-component title="System Information" :links="$links" />

    <div class="content-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->has('license_key'))
            <div class="alert alert-danger">{{ $errors->first('license_key') }}</div>
        @endif

        <div class="card border-0 shadow-sm settings-hero mb-2">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h3 class="mb-25 text-white">System Information</h3>

                </div>
                <div class="d-flex flex-wrap">
                    <div class="settings-pill mr-1 mb-1">
                        <span class="settings-pill-label">Editable Fields</span>
                        <span class="settings-pill-value">1</span>
                    </div>
                    <div class="settings-pill mb-1">
                        <span class="settings-pill-label">Current Status</span>
                        <span class="settings-pill-value">{{ $systemInfo->license_status ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h4 class="mb-0">License And Version</h4>
            </div>
            <div class="card-body">
                <form id="save-license-form" action="{{ route('admin.system-information.update') }}" method="POST" class="mb-2">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9 mb-1">
                            <label for="license_key" class="font-weight-bold">License Key</label>
                            <input type="text" id="license_key" name="license_key" class="form-control"
                                   value="{{ old('license_key', $systemInfo->license_key ?? '') }}">
                            <small class="text-muted d-block mt-50">Format: at least 10 characters (letters, numbers, and hyphen).</small>
                        </div>
                        <div class="col-md-3 mb-1 d-flex align-items-end">
                            <button id="save-license-btn" type="submit" class="btn btn-primary btn-block">Save License</button>
                        </div>
                    </div>
                </form>
                <div id="system-updater-response" class="alert d-none"></div>
                <div class="d-flex flex-wrap mt-1">
                    <form id="check-update-form" action="{{ route('admin.system-information.check-update') }}" method="POST" class="mr-1 mb-1">
                        @csrf
                        <button id="check-update-btn" type="submit" class="btn btn-info" {{ $licenseReady ? '' : 'disabled' }} title="{{ $licenseReady ? '' : 'Save a valid license key first' }}">Check Update Instantly</button>
                    </form>
                    <form id="confirm-update-form" action="{{ route('admin.system-information.confirm-update') }}" method="POST" class="mb-1">
                        @csrf
                        <button id="confirm-update-btn" type="submit" class="btn btn-warning" {{ $licenseReady ? '' : 'disabled' }} title="{{ $licenseReady ? '' : 'Save a valid license key first' }}">Confirm Update</button>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">License Key</div>
                            <div class="info-value">{{ $systemInfo->license_key ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Latest Version</div>
                            <div class="info-value">{{ $systemInfo->latest_version ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Update Available</div>
                            <div class="info-value">{{ !empty($systemInfo->update_available) ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Last Checked</div>
                            <div class="info-value">{{ !empty($systemInfo->last_checked_at) ? \Carbon\Carbon::parse($systemInfo->last_checked_at)->format('d M Y, h:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-2">
            <div class="card-header bg-transparent">
                <h4 class="mb-0">Last Version Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">App Version</div>
                            <div class="info-value">{{ $systemInfo->app_version ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Build Number</div>
                            <div class="info-value">{{ $systemInfo->system_build_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Release Channel</div>
                            <div class="info-value">{{ $systemInfo->release_channel ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="info-box-lite">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ isset($systemInfo->updated_at) ? \Carbon\Carbon::parse($systemInfo->updated_at)->format('d M Y, h:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box-lite">
                            <div class="info-label">Release Note</div>
                            <div class="info-value text-wrap">{!! $systemInfo->last_update_note ?? 'No release note available.' !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($systemInfo->system_video_url))
        <div class="card border-0 shadow-sm mt-2">
            <div class="card-header bg-transparent">
                <h4 class="mb-0">System Info Video</h4>
            </div>
            <div class="card-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item"
                            src="{{ $systemInfo->system_video_url }}"
                            title="System Information Video"
                            allowfullscreen
                            referrerpolicy="no-referrer"></iframe>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
    .settings-hero {
        background: linear-gradient(135deg, #0f2f45 0%, #1d5b83 55%, #2c7fb0 100%);
        border-radius: 14px;
    }
    .text-white-75 {
        color: rgba(255,255,255,.78);
    }
    .settings-pill {
        min-width: 150px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.32);
        padding: 8px 10px;
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .settings-pill-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: rgba(255,255,255,.82);
    }
    .settings-pill-value {
        font-size: 15px;
        line-height: 1.1;
        font-weight: 800;
        color: #fff;
    }
    .info-box-lite {
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fafcff;
        height: 100%;
    }
    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
    }
</style>
@endpush

@push('script')
<script>
    (function () {
        const $form = $('#save-license-form');
        if (!$form.length) {
            return;
        }

        const $button = $('#save-license-btn');
        const $response = $('#system-updater-response');

        function showResponse(message, type) {
            const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
            $response
                .removeClass('d-none alert-success alert-danger')
                .addClass(alertType)
                .text(message || 'Request processed.');
        }

        function handleAjaxSubmit($targetForm, $targetButton, waitingText, confirmMessage = '') {
            $targetForm.on('submit', function (e) {
                e.preventDefault();

                if (confirmMessage && !window.confirm(confirmMessage)) {
                    return;
                }

                const url = $targetForm.attr('action');
                const data = $targetForm.serialize();
                const originalText = $targetButton.text();

                $targetButton.prop('disabled', true).text(waitingText);
                $response.addClass('d-none').text('');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (res) {
                        showResponse(res.title || 'Request completed.', res.type || 'success');

                        if (typeof flasher !== 'undefined') {
                            if (res.type === 'success') {
                                flasher.success(res.title || 'Request completed.');
                            } else {
                                flasher.error(res.title || 'Request failed.');
                            }
                        }

                        if (res.refresh === 'true') {
                            setTimeout(function () {
                                window.location.reload();
                            }, 700);
                        }
                    },
                    error: function (xhr) {
                        const res = xhr.responseJSON || {};
                        const message = res.title || (res.message || 'Request failed.');
                        showResponse(message, 'error');

                        if (typeof flasher !== 'undefined') {
                            flasher.error(message);
                        }
                    },
                    complete: function () {
                        $targetButton.prop('disabled', false).text(originalText);
                    }
                });
            });
        }

        $form.on('submit', function (e) {
            e.preventDefault();

            const url = $form.attr('action');
            const data = $form.serialize();
            const originalText = $button.text();

            $button.prop('disabled', true).text('Saving...');
            $response.addClass('d-none').text('');

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (res) {
                    showResponse(res.title || 'License saved.', res.type || 'success');

                    if (typeof flasher !== 'undefined') {
                        if (res.type === 'success') {
                            flasher.success(res.title || 'License saved.');
                        } else {
                            flasher.error(res.title || 'License save failed.');
                        }
                    }

                    if (res.refresh === 'true') {
                        setTimeout(function () {
                            window.location.reload();
                        }, 700);
                    }
                },
                error: function (xhr) {
                    const res = xhr.responseJSON || {};
                    const message = res.title || (res.message || 'License save failed.');
                    showResponse(message, 'error');

                    if (typeof flasher !== 'undefined') {
                        flasher.error(message);
                    }
                },
                complete: function () {
                    $button.prop('disabled', false).text(originalText);
                }
            });
        });

        handleAjaxSubmit($('#check-update-form'), $('#check-update-btn'), 'Checking...');
        handleAjaxSubmit(
            $('#confirm-update-form'),
            $('#confirm-update-btn'),
            'Confirming...',
            'Confirm update and set app version to latest version?'
        );
    })();
</script>
@endpush
