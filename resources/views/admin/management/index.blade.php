@extends('admin.layouts.app')

@section('title', 'Management List')
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
    'Management' => route('admin.managements.index'),
    'Management list'=>''
    ]
    @endphp
    <x-bread-crumb-component title='Management list' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{__('All Management List')}}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                                    <a href="{{ route('admin.managements.create') }}" class="btn btn-primary ml-1">{{ __('Add New') }}</a>
                                {{-- @if (count(array_intersect(['chairman', 'director', 'advisor'], $types)) < 3)
                                    <a href="{{ route('admin.managements.create') }}" class="btn btn-primary ml-1">{{ __('Add New') }}</a>
                                @endif --}}
                            </div>
                        </div>
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


<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').dataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.managements.index') }}",
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
                    title: "Name",
                    searchable: true
                },
                {
                    data: "designation",
                    title: "designation",
                    searchable: true
                },
                {
                    data: "email",
                    title: "email",
                    searchable: true,
                },
                {
                    data: "phone",
                    title: "phone",
                    searchable: true,
                },
                {
                    data: "type",
                    title: "type",
                    searchable: true,
                },
                {
                    data: "address",
                    title: "address",
                    searchable: true,
                },
                {
                    data: "action",
                    title: "Action",
                    orderable: false,
                    searchable: false
                },
            ],
        });
    })
</script>

@endpush
