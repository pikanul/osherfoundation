@extends('admin.layouts.app')

@section('title', 'Partner Inquiries')

@section('content')
<div class="content-wrapper">
    @php
        $links = [
            'Home' => route('admin.dashboard'),
            'Partner Inquiries' => route('admin.partner-inquiries.index'),
            'Inquiry list' => '',
        ];
    @endphp
    <x-bread-crumb-component title="Partner Inquiry list" :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="head-label">
                            <h4 class="mb-0">All Partner With Us Inquiries</h4>
                        </div>
                        <a href="{{ route('admin.setting.index', ['page' => 'main']) }}#Partner_With_Us_Form" class="btn btn-primary" target="_blank">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Settings
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="dataTable" class="datatables-basic table table-bordered table-secondary table-striped"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let datatableM = $('#dataTable').DataTable({
        stateSave: true,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ route('admin.partner-inquiries.index') }}",
        },
        order: [[8, 'desc']],
        columns: [
            { data: "DT_RowIndex", title: "SL", name: "DT_RowIndex", searchable: false, orderable: false },
            { data: "organization_name", title: "Organization", searchable: true },
            { data: "organization_type", title: "Type", searchable: true },
            { data: "country", title: "Country", searchable: true },
            { data: "contact_name", title: "Contact Person", searchable: true },
            { data: "email", title: "Email", searchable: true },
            { data: "phone", title: "Phone", searchable: true },
            { data: "status", title: "Status", searchable: false, orderable: false },
            {
                data: "created_at",
                title: "Submitted",
                name: "created_at",
                searchable: true,
                render: function (data) {
                    return moment(data).format('DD-MMM-YYYY hh:mm A');
                }
            },
            { data: "action", title: "Action", searchable: false, orderable: false },
        ],
    });

    function mark_as_read(thi) {
        $.ajax({
            url: "{{ route('admin.partner-inquiries.mark_as_read') }}",
            type: "POST",
            data: {
                id: $(thi).data('id'),
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status == 'success') {
                    datatableM.ajax.reload();
                    flasher.success(response.message);
                } else {
                    flasher.error(response.message);
                }
            }
        });
    }
</script>
@endpush
