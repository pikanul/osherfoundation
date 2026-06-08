@extends('admin.layouts.app')
@section('title', 'Management Edit')
@push('style')
    <style>
    .image-area {
        position: relative;
        width: 100%;
        background: #333;
    }
    .image-area img{
        max-width: 100%;
        height: auto;
    }
    .remove-image {
    display: none;
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 10em;
    padding: 0 5px 2px;
    text-decoration: none;
    font: 700 21px/20px sans-serif;
    background: #555;
    border: 3px solid #fff;
    color: #FFF;
    box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        -webkit-transition: background 0.5s;
        transition: background 0.5s;
    }
    .remove-image:hover {
    background: #E54E4E;
        padding: 3px 7px 5px;
        top: -11px;
    right: -11px;
    }
    .remove-image:active {
    background: #E54E4E;
        top: -10px;
    right: -11px;
    }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        @include('components.bread-crumb-component', [
            'title' => 'Management Edit',
            'links' => [
                'Home' => route('admin.dashboard'),
                'Management' => route('admin.managements.index'),
                'Management Edit' => '',
            ],
        ])
        <div class="content-body">
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.managements.update', $management->id) }}" method="POST" class=""
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Management Edit</h4>
                                </div>
                                <hr style="margin: 0">
                                <div class="card-body">
                                    @method('put')
                                    <div class="row">
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name"
                                                    value="{{ $management->name }}" name="name" placeholder="Enter Name">
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter Phone" value="{{ $management->phone }}">
                                                @if ($errors->has('phone'))
                                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email" value="{{ $management->email }}">
                                                @if ($errors->has('email'))
                                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="designation">Designation</label>
                                                <input type="text" class="form-control" id="designation" name="designation"
                                                    placeholder="Enter Designation" value="{{ $management->designation }}">
                                                @if ($errors->has('designation'))
                                                    <small class="text-danger">{{ $errors->first('designation') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    placeholder="Enter Address" value="{{ $management->address }}">
                                                @if ($errors->has('address'))
                                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="type">Type</label>
                                                <select name="type" id="" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="chairman" {{ $management->type == 'chairman' ? 'selected' : '' }}>Chairman</option>
                                                    <option value="director" {{ $management->type == 'director' ? 'selected' : '' }}>Director</option>
                                                    <option value="advisor" {{ $management->type == 'advisor' ? 'selected' : '' }}>Advisor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" class="form-control" id="editor" cols="2" rows="2">{{ $management->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="title">Image</label>
                                                <input type="file" class="form-control" id="image" name="image" />
                                                <p class="text-danger">Image size must be 433X650px</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary waves-effect waves-float waves-light float-right"
                                        type="submit">Update</button>
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
@endpush
