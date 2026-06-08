@extends('admin.layouts.app')

@section('title', 'Department List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Department' => route('admin.departments.index'),
                'Department list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Department list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Department List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                        {!! button_g(['create' => route('admin.departments.create')], 'Department', true, 'departments') !!}
                                        <a href="{{ route('admin.departments.import.template') }}" class="btn btn-outline-info ml-1">Template</a>
                                        <button type="button" class="btn btn-success ml-1" data-toggle="modal" data-target="#departmentImportModal">Bulk Import</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            @if(session('import_report'))
                                <div class="alert alert-{{ session('import_report.type') ?? 'info' }}">
                                    {{ session('import_report.message') }}
                                </div>
                            @endif
                              <hr/>
                            <form class="filter_form_for_datatable row mb-2">


                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="class_id">Class</label> </br>
                                        <select name="class_id"  onchange="reRenderUpdate('filter')"
                                            class="form-control select2 input-group-prepend " data-ajax="true"
                                            data-url="{{ route('admin.sm_classes.select') }}" id="class_id">
                                            <option value="">-- Select Class -- </option>
                                        </select>

                                    </div>
                                </div>


                            <div class="mb-3 text-right col-lg-8 col-md-6">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" onclick="reRenderUpdate('filter')">Search</button>
                                    <button type="button" class="btn btn-secondary" onclick="reRenderUpdate('reset')">Reset</button>
                                    <button type="button" class="btn btn-success" onclick="exportDepartments()">Export</button>
                                </div>
                            </div>
                        </form>
                        <hr/>
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

    <div class="modal fade" id="departmentImportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('admin.departments.import.bulk') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Import Departments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Default Class (optional)</label>
                        <select name="default_class_id"
                            class="form-control select2 input-group-prepend"
                            data-ajax="true"
                            data-url="{{ route('admin.sm_classes.select') }}"
                            id="import_department_class_id">
                            <option value="">-- Select Class --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload CSV file</label>
                        <input type="file" name="import_file" class="form-control" accept=".csv,.txt" required>
                        <small class="text-muted">
                            Required: <code>name</code>. For class use <code>class_id</code>/<code>class_name</code> or default class.
                        </small>
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
          function exportDepartments() {
              const query = $('.filter_form_for_datatable').serialize();
              window.open("{{ route('admin.departments.export') }}" + '?' + query, '_blank');
          }

          let datatableM =  $('#dataTable').DataTable({
                stateSave: true,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('admin.departments.index') }}",
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
                    data: "class_name",
                    title: "Class Name",
                    name: "sm_classes.name",
                    searchable: true
                },

                {
                    data: "description",
                    title: "description",
                    searchable: true
                },

                {
                    data: "status",
                    title: "Status",
                    searchable: true
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
