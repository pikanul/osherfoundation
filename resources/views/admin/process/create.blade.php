@extends('admin.layouts.app')
@section('title', 'Working Progess')
@push('style')
@endpush
@section('content')
    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Working Progess' => route('admin.processes.index',['process_type'=>'process']),
                'Working Progess Create' => '',
            ];
        @endphp
        <x-bread-crumb-component title='Working Progess Create' :links="$links"/>
        <div class="content-body">
            <!-- Basic Inputs start -->
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.processes.store') }}" method="POST" class=""
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="process_type" value="process">
                            <input type="hidden" name="type" value="process">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Product Create</h4>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                      <div class="col-xl-6 col-md-6 col-12">
                                          <div class="form-group">
                                              <label for="title">Title</label>
                                              <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title">
                                          </div>
                                      </div>
                                      <div class="col-xl-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="icon">Icon</label>
                                            <input type="text" name="icon" id="icon" class="form-control" placeholder="Enter Icon">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="editor" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary waves-effect waves-float waves-light float-right"
                                            type="submit">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
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
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );
</script>
@endpush
