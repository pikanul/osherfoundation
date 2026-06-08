@extends('admin.layouts.app')

@section('title', 'Eventtpe List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Eventtpe' => route('admin.eventtypes.index'),
                'Eventtpe list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Eventtpe list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Eventtpe List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                        {!! button_g(['create' => route('admin.eventtypes.create')], 'Event Type', true, 'eventtypes') !!}
                                        <a href="{{ route('admin.eventtypes.import.template') }}" class="btn btn-outline-info ml-1">Template</a>
                                        <button type="button" class="btn btn-success ml-1" data-toggle="modal" data-target="#eventTypeImportModal">Bulk Import</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            @if(session('import_report'))
                                <div class="alert alert-{{ session('import_report.type') ?? 'info' }}">
                                    {{ session('import_report.message') }}
                                </div>
                            @endif
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

    <div class="modal fade" id="eventTypeImportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.eventtypes.import.bulk') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Import Event Types</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Upload CSV file</label>
                        <input type="file" name="import_file" class="form-control" accept=".csv,.txt" required>
                        <small class="text-muted">Required: <code>name</code>. Optional: <code>type,color,status</code>.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')

    <script>

          let datatableM =  $('#dataTable').DataTable({
                stateSave: true,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('admin.eventtypes.index') }}",
                },
                columns: [{
                    title: "SL",
                    name: null,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {
                    data: "name",
                    title: "Name",
                    searchable: true
                },
                {
                    data: "type",
                    title: "Type",
                    searchable: true
                },

                {
                    data: "color",
                    title: "color",
                    searchable: true,
                    render: function (data, type, row, meta) {
                        return '<span class="badge badge-pill p-1 badge-light-' + data + '" style="background:' + data + '">' + data + '</span>';
                    }
                },

                {
                    data: "status",
                    title: "Status",
                    searchable: true,
                    render: function (data, type, row, meta) {
                        return  data == 1 ? 'Active' : 'Inactive';
                    }
                },
                {
                    data: "created_at",
                    title: "Created at",
                    searchable: true,
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY hh:mm:ss A');
                    }
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
