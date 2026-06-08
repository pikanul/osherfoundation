@extends('admin.layouts.app')
@section('title', 'Business Partner Create')
@section('content')
<div class="content-wrapper">
    @include('components.bread-crumb-component', [
    'title'=>'Business Partner Create',
    'links'=>[
    'Home'=>route('admin.dashboard'),
    'Business Partner' => route('admin.businesses.index'),
    'Business Partner Create'=>''
    ]
    ])
    <div class="content-body">
        <section id="basic-input">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('admin.businesses.store') }}" method="POST" class=""
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Business Partner Create</h4>
                            </div>
                            <hr style="margin: 0;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Name" value="{{ old('name') }}">
                                            @if ($errors->has('name'))
                                            <small class="text-danger">{{ $errors->first('name') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="link">Link</label>
                                            <input type="text" class="form-control" id="link" name="link"
                                                placeholder="Enter Link" value="{{ old('link') }}">
                                            @if ($errors->has('link'))
                                            <small class="text-danger">{{ $errors->first('link') }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <div class="form-group">
                                            <label for="title">Image</label>
                                            <input type="file" class="form-control" id="image" name="image" />
                                            <p class="text-danger">Banner size must be 476X300px</p>
                                            <img class="p-4" height="300" width="400" id="showImage"
                                                src="{{ asset('admin-assets/app-assets/dummy/dammy.jpg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary waves-effect waves-float waves-light float-right"
                                    type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
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
<script>
    $(document).ready(function(){
          $('#image').change(function(){
              let reader = new FileReader();
              reader.onload = (e) => {
                  $('#showImage').attr('src', e.target.result);
              }
              reader.readAsDataURL(this.files[0]);
          });
      });
</script>
@endpush