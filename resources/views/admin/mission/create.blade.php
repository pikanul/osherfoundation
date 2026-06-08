@extends('admin.layouts.app')
@section('title', 'Mission/Vision Setting')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        @include('components.bread-crumb-component', [
            'title' => 'Mission/Vision Setting',
            'links' => [
                'Home' => route('admin.dashboard'),
                'Mission/Vision Setting' => '',
            ],
        ])
        <div class="content-body">
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.mission.setting.store') }}" method="POST" class=""
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Mission/Vision Setting</h4>
                                </div>
                                <hr style="margin: 0;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="title">Mission/Vision Banner</label>
                                                <input type="file" class="form-control" id="image" name="mission[mission_banner]" value=""/>
                                                <p class="text-danger">banner image must be 1920X663</p>
                                                <img class="p-4" height="200" width="300" id="showImage"
                                                    src="{{ asset('admin-assets/app-assets/dummy/dammy.jpg') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="mission_title">Title</label>
                                                <input type="text" class="form-control" name="mission[mission_title]"
                                                    placeholder="Enter Mission/Vision Title" value="{{ getSettingValue('mission_title') }}">
                                                @if ($errors->has('mission_title'))
                                                    <small class="text-danger">{{ $errors->first('mission_title') }}</small>
                                                @endif
                                            </div>
                                        </div>  
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="title">Mission/Vision Image</label>
                                                <input type="file" class="form-control" id="imageOne" name="mission[mission_image_one]" value=""/>
                                                <p class="text-danger">banner image must be 1920X663</p>
                                                <img class="p-4" height="200" width="300" id="showImageOne"
                                                    src="{{ asset('admin-assets/app-assets/dummy/dammy.jpg') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="title">Owner Image</label>
                                                <input type="file" class="form-control" id="imageTwo" name="mission[mission_image_two]" value=""/>
                                                <p class="text-danger">banner image must be 1920X663</p>
                                                <img class="p-4" height="200" width="300" id="showImageTwo"
                                                    src="{{ asset('admin-assets/app-assets/dummy/dammy.jpg') }}" alt="">
                                            </div>
                                        </div> 
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="owner_review">Owners Review</label>
                                                <input type="text" class="form-control" name="mission[owner_review]"
                                                    placeholder="Enter Owners Review" value="{{ getSettingValue('owner_review') }}">
                                                @if ($errors->has('owner_review'))
                                                    <small class="text-danger">{{ $errors->first('owner_review') }}</small>
                                                @endif
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
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
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
            $('#imageOne').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImageOne').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#imageTwo').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImageTwo').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
