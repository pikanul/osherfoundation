@extends('admin.layouts.app')

@section('title', 'Category List')

@section('content')

<div class="content-wrapper">
    @php
    $links = [
    'Home'=>route('admin.dashboard'),
    'Category' => route('admin.course.category.index'),
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
                            <h4 class="mb-0"> {{__('All Category List')}}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                                {!! button_g(['create' => route('admin.course.category.create'), 'manage' => '#Category'], 'Category', true, 'course_categories') !!}
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
            stateSave: true,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('admin.course.category.index') }}",
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
                    title: "name",
                    searchable: true,
                    orderable: true
                },
                {
                    data: "image",
                    title: "image",
                    searchable: false,
                    orderable: false
                },
                {
                    data: "status",
                    title: "Status",
                    searchable: true,
                    orderable: true,
                     render: function(data, type, row, meta) {
                        return data == 1 ? 'Active' : 'Inactive';
                    }
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
