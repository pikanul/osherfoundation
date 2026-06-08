
@extends('admin.layouts.app')

@section('title', 'Category List')

@section('content')

<div class="content-wrapper">
    @php
    $links = [
    'Home'=>route('admin.dashboard'),
    'Category' => route('admin.categories.index'),
    'Category list'=>''
    ]
    @endphp
    <x-bread-crumb-component title='Category list' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{__('All FAQ List')}}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                                {!! button_g(['create' => route('admin.faq.create')], 'FAQ') !!}
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="dataTable" class="datatables-basic table table-bordered table-secondary table-striped">
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
    var datatableM =  $('#dataTable').DataTable({
        serverSide:true,
        responsive: true,
        processing:true,
        ajax:'',
        columns:[
             { data: null, name: null, orderable: false, searchable: false, title: 'SI', render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},

            {data:'title', name:'title', title:'Title'},
            {data:'status', name:'status', title:'status', render: function (data, type, row, meta) {
                return  data == 1 ? 'Active' : 'Inactive';
            }},
            {data:'view', name:'view', title:'View', searchable:false, orderable:false},
            {data:'action', name:'action', title:'Action', searchable:false, orderable:false}
        ],
        buttons: true,
        dom:"<'row'<'col-lg-3 text-center text-lg-left mb-2'l><'col-lg-5 text-center mb-2'B><'col-lg-4 text-center text-lg-right mb-2'f>><'row'<'col-sm-12 overflow-auto'tr>><'row'<'col-sm-6'i><'col-sm-6 text-center text-md-right d-md-flex justify-content-md-end'p>>",

    })
</script>

@endpush
