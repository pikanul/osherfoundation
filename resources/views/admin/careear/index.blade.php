@extends('admin.layouts.app')

@section('title', 'Carrear List')

@section('content')

<div class="content-wrapper">
    @php
    $links = [
    'Home'=>route('admin.dashboard'),
    'Carrear' => route('admin.careears.index'),
    'Carrear list'=>''
    ]
    @endphp
    <x-bread-crumb-component title='Carrear list' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{__('All Carrear List')}}</h4>
                        </div>
                         <a href=" {{ route('admin.setting.index', ['page' => 'main']) }}#Carrear" class="btn btn-primary" target="_blank"><i class="fa fa-cogs" aria-hidden="true"></i> Manage</a>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="dataTable" class="datatables-basic table table-bordered table-secondary table-striped">
                            {{-- show from datatable--}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Responsive tables end -->
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
                url: "{{ route('admin.careears.index') }}",
            },
            columns: [{
                    data: "DT_RowIndex",
                    title: "SL",
                    name: "DT_RowIndex",
                    searchable: false,
                    orderable: false
                },
                {
                    data: "name",
                    title: "name",
                    searchable: true
                },
                {
                    data: "email",
                    title: "email",
                    searchable: true
                },
                {
                    data: "file_name",
                    title: "Attachment",
                    render: function(data, type, row) {
                        if (data) {
                            return '<a href="' + data + '" target="_blank">View</a>';
                        } else {
                            return 'No Attachment';
                        }
                    },
                    searchable: true
                },
                {
                    data: "description",
                    title: "description",
                    searchable: true
                },
                {
                    data: "read_status",
                    title: "Status",
                    searchable: true,
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<span class="badge badge-danger">Unread</span> <button class="btn btn-primary mark_as_read" data-id="' + row.id + '" onclick="mark_as_read(this)">Mark as read</button>';
                        } else {
                            return '<span class="badge badge-success">Read</span>';
                        }
                    }
                },
                {
                    data: "created_at",
                    title: "created at",
                    searchable: true
                },
                {
                    data: "action",
                    title: "Action",
                    searchable: false
                },
            ],
        });
    

    function mark_as_read(thi) {
        var id = $(thi).data('id');
        $.ajax({
            url: "{{ route('admin.careears.mark_as_read') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status == 'success') {
                    datatableM.ajax.reload();
                    flasher.success(response.message);
                }else{
                    flasher.error(response.message);
                }
            }
        });
    }
</script>

@endpush
