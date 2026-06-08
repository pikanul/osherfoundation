@extends('admin.layouts.app')

@section('title', 'Contact List')
@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-pickadate.css')}}">
@endpush
@section('content')

<div class="content-wrapper">
    @php
    $links = [
    'Home'=>route('admin.dashboard'),
    'Contact' => route('admin.contacts.index'),
    'Contact list'=>''
    ]
    @endphp
    <x-bread-crumb-component title='Contact list' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{__('All Contact List')}}</h4>
                        </div>
                         <a href=" {{ route('admin.setting.index', ['page' => 'main']) }}#Contact" class="btn btn-primary" target="_blank"><i class="fa fa-cogs" aria-hidden="true"></i> Manage</a>
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
                url: "{{ route('admin.contacts.index') }}",
            },
            order: [[6, 'desc']],
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
                    data: "subject",
                    title: "subject",
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
                    name: "created_at",
                    searchable: true,
                    render: function (data, type, row) {
                        return moment(data).format('DD-MMM-YYYY');
                    }
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
            url: "{{ route('admin.contacts.mark_as_read') }}",
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
