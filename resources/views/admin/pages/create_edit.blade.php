@extends('admin.layouts.app')

@section('title', 'Page List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Page' => route('admin.pages.index'),
                'Page list' => ''
            ]
        @endphp
        <x-bread-crumb-component title='Page list' :links="$links" />
        <div class="content-body">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ $pages ? route('admin.pages.update', $pages->id) : route('admin.pages.store') }}"
                        method="POST" class="form_ajax_submit" enctype="multipart/form-data" data-success="suevent()">
                        @csrf
                        @if ($pages)
                            @method('PUT')
                        @endif




                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12 mb-1">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name"
                                        value="{{ $pages ? $pages->name : old('name') }}" name="name"
                                        oninput="generateSlugMake(this, '#slug')" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug"
                                        value="{{ $pages ? $pages->slug : old('slug') }}" name="slug"
                                        placeholder="Enter slug">
                                </div>
                            </div>




                            <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label for="name">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{$pages && $pages->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{$pages && $pages->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12 col-12 mb-1">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control summernote" id="description" name="description" rows="3"
                                        placeholder="Enter Description">{{ $pages ? $pages->description : old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary waves-effect waves-float waves-light float-right"
                            type="submit">{{ $pages ? 'Update' : 'Create' }}</button>


                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function suevent() {
            window.location.href = "{{ route('admin.pages.index') }}";
        }
    </script>
@endsection