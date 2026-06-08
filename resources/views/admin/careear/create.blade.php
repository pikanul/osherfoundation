@extends('admin.layouts.app')
@section('title', 'Home Carrear Setting')
@section('content')
<div class="content-wrapper">
    @include('components.bread-crumb-component', [
    'title'=>'Carrear Setting',
    'links'=>[
    'Home'=>route('admin.dashboard'),
    'Carrear' => route('admin.careears.index'),
    'Home Carrear Setting'=>''
    ]
    ])
    <div class="content-body">
      <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.careears.store') }}" method="POST" class=""
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Carrear Setting</h4>
                        </div>
                        <hr style="margin: 0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="carrear_title">Carrear Title</label>
                                        <input type="text" class="form-control" name="carrears[carrear_title]"
                                            placeholder="Enter Carrear Title" value="{{ getSettingValue('carrear_title') }}">
                                        @if ($errors->has('carrear_title'))
                                            <small class="text-danger">{{ $errors->first('carrear_title') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="carrear_banner">Carrear Banner</label>
                                        <input type="file" class="form-control" id="image" name="carrears[carrear_banner]" value=""/>
                                        <p class="text-danger">banner image must be 1920X350px</p>
                                        <img class="p-4" height="200" width="300" id="showImage"
                                            src="{{ asset('admin-assets/app-assets/dummy/dammy.jpg') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="carrear_image">Carrear Image</label>
                                        <input type="file" class="form-control" id="image1" name="carrears[carrear_image]" value=""/>
                                        <p class="text-danger">image must be 570X483px</p>
                                        <img class="p-4" height="200" width="300" id="showImage1"
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#image1').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImage1').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>

@endpush
