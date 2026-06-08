<form
    action="{{ $newsCategory ? route('admin.news.categories.update', $newsCategory->id) : route('admin.news.categories.store') }}"
    method="POST" class="" enctype="multipart/form-data">
    @csrf

    @if($newsCategory)
    @method('PUT')
    @endif


    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name"
                    value="{{ $newsCategory && $newsCategory->name ? $newsCategory->name : old('name') }}"
                    name="name" placeholder="Enter Name" oninput="generateSlugMake(this, '#slug')">
            </div>
        </div>


        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug"
                    value="{{ $newsCategory ? $newsCategory->slug : old('slug') }}" name="slug"
                    placeholder="Enter slug">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id"
                        value="{{ $newsCategory ? $newsCategory->upload_id : 0 }}" class="form-control mb-2"
                        hidden>
                    <img style="max-height: 60px"
                        src="{{ dynamic_asset($newsCategory ? $newsCategory->upload_id : 0) }}" alt="">
                </label>
            </div>
        </div>
    </div>

    <div class="text-right">
        <button class="btn btn-primary waves-effect waves-float waves-light float-right"
            type="submit">{{  $newsCategory ? 'Update' : 'Create' }}</button>
    </div>

</form>
