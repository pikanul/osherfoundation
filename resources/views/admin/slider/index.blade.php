@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    @php
    $links = [
        'Home'=>route('admin.dashboard'),
        'Slider' => route('admin.sliders.index'),
        'Slider list'=>''
    ]
    @endphp
    <x-bread-crumb-component title='Slider list' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{__('All Slider List')}}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                               {!! button_g(['create' => route('admin.sliders.create')], 'Slider') !!}
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
    
       let datatableM = $('#dataTable').DataTable({
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.sliders.index') }}",
            },
            columns: [{
                    data: "DT_RowIndex",
                    title: "SL",
                    name: "DT_RowIndex",
                    searchable: false,
                    orderable: false
                },
                {
                    data: "image",
                    title: "image",
                    searchable: true
                },
                {
                    data: "title",
                    title: "title",
                    searchable: true
                },
                {
                    data: "sub_title",
                    title: "sub title",
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
    
</script>

@endpush
