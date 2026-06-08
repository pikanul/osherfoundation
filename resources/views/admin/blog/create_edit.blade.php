<form action="{{ $blog ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @if ($blog)
        @method('PUT')

    @endif

    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Title</label>
                <input type="text" name="title" value="{{ $blog ? $blog->title : old('title', '') }}"
                    oninput="generateSlugMake(this, '#slug')" class="form-control" id="inputblog">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" value="{{ $blog ? $blog->slug : old('slug') }}"
                    name="slug" placeholder="Enter slug">
            </div>
        </div>

        <div class="col-lg-6">
            <label type="button" onclick="upload_select(this, 1200, 600)"> Image <span class="text-danger">Size : 600px
                    x
                    600px</span><br>
                <input type="text" name="upload_id" id="image" class="form-control mb-2"
                    value="{{ $blog ? $blog->upload_id : 0 }}" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($blog ? $blog->upload_id : 0) }}" alt="">
            </label>
        </div>

        <div class="col-lg-6">
            <label type="button" onclick="upload_select(this, 1200, 600)"> Attachment <span class="text-danger"></span><br>
                <input type="text" name="attachment_id" id="attachment" class="form-control mb-2"
                    value="{{ $blog ? $blog->attachment_id : 0 }}" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($blog ? $blog->attachment_id : 0) }}" alt="">
            </label>
        </div>


        <div class="col-xl-12 col-12 mb-1">
            <label for="categoryName">Category Name</label>
            <div class="btn-group w-100">
                <select type="text" name="category_id" data-url="{{ route('admin.blog-categories.select') }}"
                    data-ajax="true" class="form-control input-group-prepend select2 category_select"
                    placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    <option selected value="{{ $blog ? $blog?->category_id : 0 }}">
                        {{ $blog ? $blog?->category?->name : 'Select Category' }}
                    </option>
                </select>


                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" onclick="button_ajax(this)"
                        data-dialog=" modal-dialog-scrollable modal-lg" data-title="Add New  Category"
                        data-href="{{ route('admin.blog-categories.create') }}" data-target="#ajax_modal_dialog_add">+
                    </button>
                </div>


            </div>
        </div>


        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Publish Date</label>
                <input type="date" name="publish_date"
                    value="{{ $blog ? $blog->publish_date : old('publish_date', '') }}" class="form-control"
                    id="inputblog">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Status</label>
                <select name="status" class="form-control" id="">
                    <option value="active" {{ $blog ? ($blog->status == 'active' ? 'selected' : '') : '' }}>Active
                    </option>
                    <option value="inactive" {{ $blog ? ($blog->status == 'inactive' ? 'selected' : '') : '' }}>Inactive
                    </option>
                </select>
            </div>
        </div>

        <div class=" col-12 mb-1">
            <div class="form-group">
                <label for="short_description">Short Description</label>
                <textarea name="short_description" class="form-control" id="editor" cols="5"
                    rows="3">{{  $blog ? $blog->short_description : old('short_description', '') }}</textarea>
            </div>
        </div>

        <div class=" col-12 mb-1">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control summernote" id="editor1" cols="5"
                rows="3">{{ $blog ? $blog->description : old('description', '') }}</textarea>
            </div>
        </div>

    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">{{ $blog ? 'Update' : 'Create' }}</button>

    </div>
</form>
