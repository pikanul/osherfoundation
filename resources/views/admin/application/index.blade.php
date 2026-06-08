@extends('admin.layouts.app')
@section('title', 'Course List')
@section('content')

    <div class="content-wrapper">
        @php
            $links = [
            'Home'=>route('admin.dashboard'),
            'Course' => route('admin.course.course.index'),
            'List of Course'=>''
            ]
        @endphp
        <x-bread-crumb-component title='Design Feature' :links="$links"/>
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Course')}}</h4>
                            </div>
                            <!-- <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    <a href="{{route('admin.application.create', ['process_type'=>'design'])}}"
                                       class="btn btn-primary ml-1">{{__('Add New')}}</a>
                                </div>
                            </div> -->
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
        $(document).ready(function () {
            $('#dataTable').dataTable({
                stateSave: true,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "",
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
                        title: "Applicant Name",
                        name: "name",
                        searchable: true
                    },
                    {
                        data: "course_name",
                        title: "Course Name",
                        name: "course_name",
                        searchable: true
                    },
                    {
                        data: "father",
                        title: "Father",
                        name: "father",
                        searchable: true
                    },
                    {
                        data: "father",
                        title: "Mother",
                        name: "father",
                        searchable: true
                    },
                    {
                        data: "application_status",
                        title: "Application Status",
                        name: "application_status",
                        searchable: true
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
        })
    </script>

@endpush
