@extends('admin.layouts.app')
@section('title', 'Management Create')
@section('content')
    <div class="content-wrapper">
        @include('components.bread-crumb-component', [
            'title' => 'Management Create',
            'links' => [
                'Home' => route('admin.dashboard'),
                'Management' => route('admin.managements.index'),
                'Management Create' => '',
            ],
        ])
        <div class="content-body">
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.managements.store') }}" method="POST" class=""
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Management Create</h4>
                                </div>
                                <hr style="margin: 0;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter Name" value="{{ old('name') }}">
                                                @if ($errors->has('name'))
                                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter Phone" value="{{ old('phone') }}">
                                                @if ($errors->has('phone'))
                                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email" value="{{ old('email') }}">
                                                @if ($errors->has('email'))
                                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="designation">Designation</label>
                                                <input type="text" class="form-control" id="designation" name="designation"
                                                    placeholder="Enter Designation" value="{{ old('designation') }}">
                                                @if ($errors->has('designation'))
                                                    <small class="text-danger">{{ $errors->first('designation') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    placeholder="Enter Address" value="{{ old('address') }}">
                                                @if ($errors->has('address'))
                                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="type">Type</label>
                                                <input type="text" class="form-control" id="type" name="type"
                                                    placeholder="Enter Type" value="{{ old('type') }}">
                                                @if ($errors->has('type'))
                                                    <small class="text-danger">{{ $errors->first('type') }}</small>
                                                @endif

                                                {{-- <select name="type" id=""  class="form-control">
                                                    <option value="">Select One</option>
                                                    @if (!in_array('chairman',$types))
                                                    <option value="chairman">Chairman</option>
                                                    @endif
                                                    @if (!in_array('director',$types))
                                                    <option value="director">Director</option>
                                                    @endif
                                                    @if (!in_array('advisor',$types))
                                                    <option value="advisor">Advisor</option>
                                                    @endif
                                                </select> --}}

                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="editor1" cols="30" rows="10">{{ old('description') }}</textarea>
                                                @if ($errors->has('description'))
                                                    <small
                                                        class="text-danger">{{ $errors->first('description') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="title">Image</label>
                                                <input type="file" class="form-control" id="image" multiple name="image" />
                                                <p class="text-danger">Image size must be 433X650px</p>
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
        ClassicEditor
            .create(document.querySelector('#editor1'))
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
            $('#image_pro').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImage_pro').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#image_pro_banner').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showImage_pro_banner').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
