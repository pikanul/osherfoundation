@extends('admin.layouts.app')

@section('title', 'Admin List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Admin list' => ''
            ]
        @endphp

        <x-bread-crumb-component title='Admin list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Admin List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    {!! button_g(['create' => route('admin.admin.create')], 'Admin') !!}
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

    <script>
        function target_base(thi) {
            var check_element = true;
            if (thi.checked == true) {
                check_element = true
            } else {
                check_element = false
            }

            $(thi).parents('tr').find('td:nth-child(2) input').each(function (key, element) {
                //console.log(element)

                if (check_element == true) {
                    element.checked = true;
                } else {
                    element.checked = false;
                }
            })
        }
        function selectPermission(thi) {
            var check_element = true;
            if (thi.checked == true) {
                check_element = true
            } else {
                check_element = false
            }

            $(thi).parents('table').find('tr td:nth-child(2) input').each(function (key, element) {
                //console.log(element)

                if (check_element == true) {
                    element.checked = true;
                } else {
                    element.checked = false;
                }
            })
        }
    </script>
@endsection
@push('script')

    <script>

        let datatableM = $('#dataTable').DataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.admin.index') }}",
            },
            columns: [{
                data: "SI",
                title: "SL",
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
                data: "email",
                title: "Email",
                searchable: true
            },
            {
                data: "mobile",
                title: "Mobile",
                searchable: true,
                "defaultContent": '<i class="text-danger">Not set</i>'
            },
            {
                data: "degination",
                title: "Degination",
            },

            {
                data: "status",
                title: "Status",
                orderable: false,
                searchable: false
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
