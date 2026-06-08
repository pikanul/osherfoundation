@extends('admin.layouts.app')
@section('title', 'Working Progess')
@section('content')

    <div class="content-wrapper">
        @php
            $links = [
            'Home'=>route('admin.dashboard'),
            'Working Progess' => route('admin.processes.index',['process_type'=>'process']),
            'Working Progess list'=>''
            ]
        @endphp
        <x-bread-crumb-component title='Working Progess' :links="$links"/>
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Working Progess List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    <a href="{{route('admin.processes.create', ['process_type'=>'process'])}}"
                                       class="btn btn-primary ml-1">{{__('Add New')}}</a>
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
        $(document).ready(function () {
            $('#dataTable').dataTable({
                stateSave: true,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('admin.processes.index',request()->all()) }}",
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
                        data: "icon",
                        title: "icon",
                        searchable: true
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
        })
    </script>

@endpush
