@extends('admin.layouts.app')

@section('title', 'Product List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Product' => route('admin.products.index'),
                'Product list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Product list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Product List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                      {!! button_g(['create' => route('admin.products.create' ), 'manage' => '#Product'], 'Product', true, 'product') !!}
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
                url: "{{ route('admin.products.index') }}",
            },
            columns: [{
                data: "DT_RowIndex",
                title: "SL",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false
            },
            {
                title: "Image",
                data: "image",
                searchable: false
            },
            {
                title: "Name",
                data: "name",
                searchable: true
            },
            {
                data: "slug",
                title: "Slug",
                searchable: true
            },
            {
                data: "cat_name",
                name: "categories.name",
                title: "category",
                searchable: true,
                "defaultContent": "No Set"
            },
            {
                data: "sub_cat_name",
                name: "sub_categories.name",
                title: "Sub category",
                searchable: true,
                "defaultContent": "No Set"
            },
            {
                data: "price",
                title: "Price",
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
