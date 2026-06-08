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
        <x-bread-crumb-component :title='$course->course_name' :links="$links" />
        <div class="content-body">


            <div class="row">
                <div class="col-md-4">
                    <div class="card overflow-hidden">
                        <div class="card-header course_banner" style="background: url('{{ dynamic_asset($course->attachment) }}');">
                            <h3 class="p-1">  {{ $course->course_name }}</h6>

                        </div>
                        <div class="card-body">

                          <br/>
                            <div class="text-group mb-2">
                                Course Price :
                                {{ $course->price  }}
                            </div>
                            <div class="text-group mb-2">
                                Discount Price :
                                {{ $course->discount  }}
                            </div>
                            <div class="text-group mb-2">
                                Duration :
                                {{ $course->duration  }} Days
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('Lession of') .' ' . $course->course_name}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    {!! button_g(['create' => route('admin.course.lession.create', ['course_id' => $course->id])], 'Lession') !!}
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table id="dataTable"  class="datatables-basic table table-bordered table-secondary table-striped"> </table>
                        </div>
                    </div>


                </div>
            </div>
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
                    url: "{{ route('admin.course.lession.index') }}",
                },
                columns: [
                    {
                        data: false,
                        title: "SL",
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "cover_img",
                        title: "Cover Image",
                        name: "cover_image",
                        searchable: false,
                        orderable: false,

                    },
                    {
                        data: "name",
                        title: "Title",
                        name: "name",
                        searchable: true
                    },
                    {
                        data: "lession_type",
                        title: "Lession Type",
                        name: "lession_type",
                        searchable: true
                    },
                    {
                        data: "status",
                        title: "Status",
                        name: "status",
                        searchable: true,
                        render: function (data, type, row, meta) {
                            return data ;//== 1 ? 'Active' : 'Inactive';
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


    @push('css')
        <style>
            .course_banner.card-header {
                background-size: cover !important;
                background-position: center !important;
                z-index: 1;
                min-height: 150px;
            }
            .course_banner.card-header::after {
                width: 100%;
                height: 100%;
                background: #06000063;
                content: '';
                position: absolute;
                left: 0;
                z-index: 2;
            }
            .course_banner.card-header h3 {
                position: relative;
                z-index: 1111;
                color: white;

            }
        </style>
    @endpush
@endsection
