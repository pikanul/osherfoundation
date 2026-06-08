@extends('admin.layouts.app')
@section('title', 'Query List')
@section('content')
<div class="content-wrapper">
    @include('components.bread-crumb-component', [
    'title'=>'Query list',
    'links'=>[
    'Home'=>route('admin.dashboard'),
    'Query list'=>''
    ]
    ])
    {{-- <x-bread-crumb-component title='Query list' :links="$links" />--}}
    <div class="content-body">
        <!-- Responsive tables start -->
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Query List</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTable" class="datatables-basic table table-striped table-secondary table-bordered">
                            <thead class="table-light">
                                <tr>
                                  <th>Id</th>
                                  <th>Name</th>
                                </tr>
                              </thead>
                              <tbody class="table-border-bottom-0">
                               @foreach ($inquiries as $key => $query )
                               <tr>
                                 <td>{{ ++$key }}</td>
                                 <td>{{ $query->name }}</td>
                               </tr>
                               @endforeach
                              </tbody>
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
@endpush