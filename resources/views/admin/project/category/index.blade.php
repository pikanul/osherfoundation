@extends('admin.layouts.app')

@section('title', 'project Category List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'project Category' => route('admin.project-categories.index'),
                'project Category list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='project Category list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All project Category List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                   {!! button_g(['create' => route('admin.project-categories.create')], 'project Category') !!}
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
                url: "{{ route('admin.project-categories.index') }}",
            },
            columns: [{
                data: "DT_RowIndex",
                title: "SL",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: "name",
                title: "name",
                searchable: true
            },
            {
                data: "slug",
                title: "slug",
                searchable: true
            },

            {
                data: "status",
                title: "status",
                searchable: true,
                render: function (data, type, row) {
                    if (data == 1) {
                        return '<span class="badge badge-pill badge-light-success">Active</span>';
                    } else {
                        return '<span class="badge badge-pill badge-light-danger">Inactive</span>';
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
        });

    </script>

@endpush
