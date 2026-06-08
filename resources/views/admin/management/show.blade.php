@extends('admin.layouts.app')

@section('title', 'Product List')
@section('content')
    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Product List' => route('admin.products.index'),
                'Product Details' => '',
            ];
        @endphp
        <div class="content-body">
            <!-- Basic Inputs start -->
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Details</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th><strong>Name :</strong></th>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Sub Category :</strong></th>
                                            <td>{{ $product->subCategory->name }}</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Description :</strong></th>
                                            <td>{!! $product->description !!}</td>
                                        </tr>
                                        @foreach ($productImages as $row)
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