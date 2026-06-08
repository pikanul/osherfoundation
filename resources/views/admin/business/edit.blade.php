@extends('admin.layouts.app')
@section('title', 'Business Partner Edit')
@section('content')
    <div class="content-wrapper">
        @include('components.bread-crumb-component', [
            'title' => 'Business Partner Edit',
            'links' => [
                'Home' => route('admin.dashboard'),
                'Business Partner' => route('admin.businesses.index'),
                'Business Partner Edit' => '',
            ],
        ])
        <div class="content-body">
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.businesses.update', $business->id) }}" method="POST"
                            class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Business Partner Edit</h4>
                                </div>
                                <hr style="margin: 0">
                                <div class="card-body">
                                    @method('put')
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name"
                                                    value="{{ $business->name }}" name="name"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="link">Link</label>
                                                <input type="text" class="form-control" id="link"
                                                    value="{{ $business->link }}" name="link"
                                                    placeholder="Enter Link">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6 col-12 mb-1">
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" id="image" class="form-control" name="image">
                                                <img class="p-4" height="300"
                                                    width="400" id="showImage"
                                                    src="{{ asset('upload') }}/{{ $business->image }}"
                                                    alt="Card image cap">
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
