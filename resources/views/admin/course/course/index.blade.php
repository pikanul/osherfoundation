@extends('admin.layouts.app')
@section('title', 'Course List')
@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Course' => route('admin.course.course.index'),
                'List of Course' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Design Feature' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Course')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    {!! button_g(['create' => route('admin.course.course.create')], 'Course') !!}
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

    @push('script')


    <script>

        let datatableM = $('#dataTable').DataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.course.course.index') }}",
            },
            order: [[2, 'asc']],
            columns: [
                {
                    data:false,
                    title: "SL",
                    searchable: false,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
            {
                data: "image",
                title: "Cover Image",
                name: "upload_id",
                searchable: false,
                orderable: false,

            },
            {
                data: "course_name",
                title: "Course Name",
                name: "course_name",
                searchable: true
            },

            {
                data: "start_enroll",
                title: "Start Enroll",
                name: "start_enroll",
                searchable: true
            },
            {
                data: "end_enroll",
                title: "End Enroll",
                name: "end_enroll",
                searchable: true
            },
            {
                data: "duration",
                title: "Duration (Days)",
                name: "duration",
                searchable: true
            },
            {
                data: 'status',
                name: 'status',
                title: 'status',
                render: function(data, type, row, meta) {

                    return data == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                }
            },


            {
                data: "created_at",
                title: "created at",
                searchable: true,
                render: function (data, type, row, meta) {
                    return moment(row.created_at).format('DD-MM-YYYY');
                }
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
@endsection
