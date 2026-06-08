<form
    action="{{ $projectCategory ? route('admin.project-categories.update', $projectCategory->id) : route('admin.project-categories.store') }}"
    method="POST" class="" enctype="multipart/form-data">
    @csrf

    @if($projectCategory)
    @method('PUT')
    @endif


    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name"
                    value="{{ $projectCategory && $projectCategory->name ? $projectCategory->name : old('name') }}" name="name"
                    placeholder="Enter Name" oninput="generateSlugMake(this, '#slug')">
            </div>
        </div>


        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug"
                    value="{{ $projectCategory ? $projectCategory->slug : old('slug') }}" name="slug"
                    placeholder="Enter slug">
            </div>
        </div>
        <!-- status -->
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $projectCategory && $projectCategory->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $projectCategory && $projectCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>


    </div>

    <div class="text-right">
        <button class="btn btn-primary waves-effect waves-float waves-light float-right"
            type="submit">{{  $projectCategory ? 'Update' : 'Create' }}</button>
    </div>

</form>
