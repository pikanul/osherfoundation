@extends('admin.layouts.app')

@section('title', 'Youtube Video List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Youtube Video' => route('admin.youtubes.index'),
                'Youtube Video list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Youtube Video list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Youtube Video List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    {!! button_g(['create' => route('admin.youtubes.create'), 'manage' => '#Youtube_Gallery'], 'Youtube Video') !!}

                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table id="dataTable"
                                class="datatables-basic table table-bordered table-secondary table-striped">
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
                url: "{{ route('admin.youtubes.index') }}",
            },
            columns: [{
                data: "DT_RowIndex",
                title: "SL",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false
            },
            {
                data: "title",
                title: "title",
                searchable: true
            },
            {
                data: "image",
                title: "image",
                searchable: false
            },
            {
                data: "status",
                title: "status",
                searchable: false,
                orderable:true,
                render: function (data, type, row) {
                    if (data == 1) {
                        return '<span class="badge badge-pill badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-pill badge-danger">Inactive</span>';
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
                orderable: false,
                searchable: false
            },
            ],
            buttons: true,
            dom: "<'row'<'col-lg-3 text-center text-lg-left mb-2'l><'col-lg-5 text-center mb-2'B><'col-lg-4 text-center text-lg-right mb-2'f>><'row'<'col-sm-12 overflow-auto'tr>><'row'<'col-sm-6'i><'col-sm-6 text-center text-md-right d-md-flex justify-content-md-end'p>>",
        });
    </script>
@endpush
