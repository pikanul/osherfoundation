@extends('admin.layouts.app')

@section('title', 'Subscriber List')

@push('css')
    <style>
        .subscriber-panel .card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 47, 69, 0.08);
        }
        .subscriber-toolbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .subscriber-title h4 {
            font-weight: 800;
            letter-spacing: 0.2px;
            margin-bottom: 3px;
            color: #0f2f45;
        }
        .subscriber-title p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }
        .subscriber-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
            width: 100%;
        }
        .subscriber-actions .btn,
        .subscriber-actions .form-control {
            height: 36px;
            border-radius: 10px;
        }
        .subscriber-selected-count {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            color: #0f2f45;
            background: #e8f3ff;
            border: 1px solid #cfe4fb;
            border-radius: 999px;
            padding: 6px 11px;
            height: 36px;
        }
        .subscriber-table-wrap {

            overflow: hidden;
        }
        #subscriberTable thead th {
            background: #f7f9fc;
            color: #4b5563;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            border-bottom: 1px solid #e5eaf1;
        }
        #subscriberTable tbody tr:hover {
            background: #f8fbff;
        }
        .subscriber-name {
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }
        .subscriber-email {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Subscribers' => route('admin.subscribers.index'),
                'Subscriber list' => ''
            ];
            $canBulkStatus = Auth::hasP('subscribers edit');
            $canBulkDelete = Auth::hasP('subscribers delete');
        @endphp
        <x-bread-crumb-component title='Subscriber list' :links="$links" />

        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card subscriber-panel">
                        <div class="card-header">
                            <div class="subscriber-toolbar w-100">
                                <div class="subscriber-title">
                                    <h4>{{ __('Subscriber Management') }}</h4>
                                    <p>Manage contacts, run bulk status updates, and keep your newsletter list clean.</p>
                                </div>
                                <div class="subscriber-actions">
                                    <span class="subscriber-selected-count" id="selectedSubscriberCount">0 selected</span>
                                    {!! button_g(['create' => route('admin.subscribers.create')], 'Subscriber', true, 'subscribers') !!}
                                    @if($canBulkStatus)
                                        <select id="bulkStatusValue" class="form-control">
                                            <option value="">Status</option>
                                            <option value="1">Active</option>
                                            <option value="2">Unsubscribed</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <button type="button" id="bulkStatusBtn" class="btn btn-warning" disabled>
                                            <i class="fas fa-sync-alt mr-50"></i>Change Status
                                        </button>
                                    @endif
                                    @if($canBulkDelete)
                                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger" disabled>
                                            <i class="fas fa-trash mr-50"></i>Delete Selected
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.subscribers.import.template') }}" class="btn btn-outline-info">
                                        <i class="fas fa-download mr-50"></i>Template
                                    </a>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#subscriberImportModal">
                                        <i class="fas fa-file-import mr-50"></i>Bulk Import
                                    </button>
                                    <a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary">
                                        <i class="fas fa-file-export mr-50"></i>Export CSV
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(session('import_report'))
                                <div class="alert alert-{{ session('import_report.type') ?? 'info' }}">
                                    {{ session('import_report.message') }}
                                </div>
                            @endif

                            <div class="table-responsive subscriber-table-wrap">
                                <table id="subscriberTable" class="datatables-basic table table-bordered table-striped mb-0"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subscriberImportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.subscribers.import.bulk') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Import Subscribers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>Upload CSV file</label>
                        <input type="file" name="import_file" class="form-control" accept=".csv,.txt" required>
                        <small class="text-muted">Required: <code>email</code>. Optional: <code>name,phone,status</code>.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const selectedSubscriberIds = new Set();
        const canBulkStatus = {{ $canBulkStatus ? 'true' : 'false' }};
        const canBulkDelete = {{ $canBulkDelete ? 'true' : 'false' }};

        function updateBulkActionButtonState() {
            const count = selectedSubscriberIds.size;
            $('#selectedSubscriberCount').text(`${count} selected`);
            if (canBulkStatus) {
                $('#bulkStatusBtn').prop('disabled', count === 0);
                $('#bulkStatusBtn').html(`<i class="fas fa-sync-alt mr-50"></i>${count > 0 ? `Change Status (${count})` : 'Change Status'}`);
            }
            if (canBulkDelete) {
                $('#bulkDeleteBtn').prop('disabled', count === 0);
                $('#bulkDeleteBtn').html(`<i class="fas fa-trash mr-50"></i>${count > 0 ? `Delete Selected (${count})` : 'Delete Selected'}`);
            }
        }

        function syncSelectAllSubscriberCheckbox() {
            const rowCheckboxes = $('.subscriber-row-checkbox');
            const checkedCount = rowCheckboxes.filter(':checked').length;
            const totalCount = rowCheckboxes.length;
            const selectAll = $('#selectAllSubscriberRows').get(0);

            if (!selectAll) {
                return;
            }

            selectAll.checked = totalCount > 0 && checkedCount === totalCount;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }

        let datatableM = $('#subscriberTable').DataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.subscribers.index') }}",
            },
            order: [[1, 'desc']],
            columns: [
                {
                    data: "id",
                    title: '<input type="checkbox" id="selectAllSubscriberRows">',
                    searchable: false,
                    orderable: false,
                    width: '40px',
                    className: 'text-center',
                    render: function (data) {
                        const checked = selectedSubscriberIds.has(String(data)) ? 'checked' : '';
                        return `<input type="checkbox" class="subscriber-row-checkbox" value="${data}" ${checked}>`;
                    }
                },
                {
                    data: "id",
                    title: "ID",
                    searchable: true
                },
                {
                    data: "name",
                    title: "Subscriber",
                    searchable: true,
                    render: function (data, type, row) {
                        const name = row.name ? row.name : 'Unnamed';
                        const email = row.email ? row.email : '-';
                        return `<div class="subscriber-name">${name}</div><p class="subscriber-email">${email}</p>`;
                    }
                },
                {
                    data: "phone",
                    title: "Phone",
                    searchable: true,
                    render: function (data) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "status",
                    title: "Status",
                    searchable: true,
                    render: function (data) {
                        if (parseInt(data) === 1) return '<span class="badge badge-success px-1 py-50">Active</span>';
                        if (parseInt(data) === 2) return '<span class="badge badge-warning px-1 py-50">Unsubscribed</span>';
                        return '<span class="badge badge-secondary px-1 py-50">Inactive</span>';
                    }
                },
                {
                    data: "subscribed_at",
                    title: "Subscribed At",
                    searchable: true,
                    render: function (data) {
                        return data ? moment(data).format('DD-MM-YYYY hh:mm:ss A') : '-';
                    }
                },
                {
                    data: "created_at",
                    title: "Created Time",
                    searchable: true,
                    render: function (data) {
                        return data ? moment(data).format('DD-MM-YYYY hh:mm:ss A') : '-';
                    }
                },
                {
                    data: "action",
                    title: "Action",
                    searchable: false,
                    orderable: false
                },
            ],
            drawCallback: function () {
                syncSelectAllSubscriberCheckbox();
                updateBulkActionButtonState();
            }
        });

        $(document).on('change', '.subscriber-row-checkbox', function () {
            const id = String($(this).val());
            if ($(this).is(':checked')) {
                selectedSubscriberIds.add(id);
            } else {
                selectedSubscriberIds.delete(id);
            }
            syncSelectAllSubscriberCheckbox();
            updateBulkActionButtonState();
        });

        $(document).on('change', '#selectAllSubscriberRows', function () {
            const checked = $(this).is(':checked');
            $('.subscriber-row-checkbox').each(function () {
                const id = String($(this).val());
                this.checked = checked;
                if (checked) {
                    selectedSubscriberIds.add(id);
                } else {
                    selectedSubscriberIds.delete(id);
                }
            });
            syncSelectAllSubscriberCheckbox();
            updateBulkActionButtonState();
        });

        $('#bulkStatusBtn').on('click', function () {
            if (!canBulkStatus) return;

            const ids = Array.from(selectedSubscriberIds);
            const status = $('#bulkStatusValue').val();

            if (ids.length === 0) {
                flasher.error('Please select at least one subscriber.');
                return;
            }

            if (status === '') {
                flasher.error('Please select a status first.');
                return;
            }

            Swal.fire({
                title: 'Change Subscriber Status?',
                text: `Selected ${ids.length} subscriber(s) will be updated.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (!result.value) {
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.subscribers.bulk.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        subscriber_ids: ids,
                        status: status
                    },
                    success: function (data) {
                        if (data.type === 'success') {
                            flasher.success(data.title);
                            selectedSubscriberIds.clear();
                            $('#bulkStatusValue').val('');
                            datatableM.ajax.reload();
                            return;
                        }

                        flasher.error(data.title || 'Failed to update status.');
                    },
                    error: function () {
                        flasher.error('Failed to update selected subscriber status.');
                    }
                });
            });
        });

        $('#bulkDeleteBtn').on('click', function () {
            if (!canBulkDelete) return;

            const ids = Array.from(selectedSubscriberIds);
            if (ids.length === 0) {
                flasher.error('Please select at least one subscriber.');
                return;
            }

            Swal.fire({
                title: 'Delete Subscribers?',
                text: `You are deleting ${ids.length} selected subscriber(s). This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (!result.value) {
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.subscribers.bulk.delete') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        subscriber_ids: ids
                    },
                    success: function (data) {
                        if (data.type === 'success') {
                            flasher.success(data.title);
                            selectedSubscriberIds.clear();
                            datatableM.ajax.reload();
                            return;
                        }

                        flasher.error(data.title || 'Failed to delete subscribers.');
                    },
                    error: function () {
                        flasher.error('Failed to delete selected subscribers.');
                    }
                });
            });
        });
    </script>
@endpush
