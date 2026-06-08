<form action="{{ $category ? route('admin.blog-categories.update', $category->id) : route('admin.blog-categories.store') }}"
    method="POST" class="" enctype="multipart/form-data">
    @csrf
    @if ($category)
        @method('PUT')
    @endif




    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name"
                    value="{{ $category ? $category->name : old('name') }}" name="name" oninput="generateSlugMake(this, '#slug')" placeholder="Enter Name">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug"
                    value="{{ $category ? $category->slug : old('slug') }}" name="slug" placeholder="Enter slug">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">

            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id" value="{{ $category ? $category->upload_id : 0 }}"
                        class="form-control mb-2" hidden>
                    <img style="max-height: 60px" src="{{ dynamic_asset($category ? $category->upload_id : 0) }}"
                        alt="">
                </label>
            </div>
        </div>


       <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{$category && $category->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{$category && $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

       <div class="col-xl-12 col-12 mb-1">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description">{{ $category ? $category->description : old('description') }}</textarea>
            </div>
        </div>
    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right"
        type="submit">{{ $category ? 'Update' : 'Create' }}</button>


</form>