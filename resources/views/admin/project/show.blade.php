@extends('admin.layouts.app')

@section('title', 'ProjectList')
@section('content')
    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'ProjectList' => route('admin.projects.index'),
                'ProjectDetails' => '',
            ];
        @endphp
        <div class="content-body">
            <!-- Basic Inputs start -->
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">ProjectDetails</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th><strong>Name :</strong></th>
                                            <td>{{ $project->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Category :</strong></th>
                                            <td>{{ $project->subCategory->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Price :</strong></th>
                                            <td>{{ $project->price }}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Specification :</strong></th>
                                            <td>{!! $project->specification !!}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Description :</strong></th>
                                            <td>{!! $project->description !!}</td>
                                        </tr>
                                        @foreach ($projectImages as $row)
                                        <tr>
                                            <th><strong>Image :</strong></th>
                                            <td><img src="{{ asset('/upload/'.$row->image) }}" alt="" style="height:80px;width:120px; padding:5px"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Inputs end -->
        </div>
    </div>

@endsection
@section('css')

@endsection
@section('js')

@endsection
@push('script')
@endpush
