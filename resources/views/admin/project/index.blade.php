@extends('admin.layouts.app')

@section('title', 'Project List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Project' => route('admin.projects.index'),
                'Project list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Projectlist' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All ProjectList')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                      {!! button_g(['create' => route('admin.projects.create' ), 'manage' => '#Project'], 'Project', true, 'projects') !!}
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

        var datatableM = $('#dataTable').DataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.projects.index') }}",
            },
            columns: [{
                data: "DT_RowIndex",
                title: "SL",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false
            },

            {
                title: "Name",
                data: "name",
                searchable: true
            },

            {
                title: "Stage Of Project",
                data: "project_category_name",
                name: "project_categories.name",
                searchable: true
            },
            {
                data: "status",
                title: "Status",
                searchable: true,
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                }

            },

            {
                title: "funded_by",
                data: "funded_by",
                searchable: true
            },
            {
                title: "duration",
                data: "duration",
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
