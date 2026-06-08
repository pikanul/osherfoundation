<form class="row" action="{{ $course ? route('admin.course.course.update', $course->id) : route('admin.course.course.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if ($course)
        @method('PUT')
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <label for="course_name">Course Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="course_name" name="course_name" placeholder="Enter course name"
                value="{{ $course ? $course->course_name : old('course_name') }}"  oninput="generateSlugMake(this, '#slug')" required>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="short_code">Course Short Code <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="short_code" name="short_code" placeholder="Enter course short code"
                value="{{ $course ? $course->short_code : old('short_code') }}"   required>

        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" class="form-control" id="slug"
                value="{{ $course ? $course->slug : old('slug') }}" name="slug" placeholder="Enter slug">
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="form-group">
            <label for="certificate_skills">Certificate Skills (Only show in certificate)</label>
            <input type="text" class="form-control" id="certificate_skills"
                value="{{ $course ? $course->certificate_skills : old('certificate_skills') }}" name="certificate_skills" placeholder="Enter certificate skills">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="price">Course Fee <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="price" name="price" placeholder="Enter price"
                value="{{ $course ? $course->price : old('price') }}" required>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="discount">Discount  <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="discount" name="discount" placeholder="Enter discount"
                value="{{ $course ? $course->discount : old('discount') }}" required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="session">Session <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="session" name="session" placeholder="Enter session"
                value="{{ $course ? $course->session : old('session') }}" required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="batch">Batch <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="batch" name="batch" placeholder="Enter batch"
                value="{{ $course ? $course->batch : old('batch') }}" required>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="shift">Shift <span class="text-danger">*</span></label>
            <select name="shift" class="form-control">
                <option value="M" {{ $course && $course->shift == 'Morning' ? 'selected' : '' }}>Morning</option>
                <option value="D" {{ $course && $course->shift == 'Day' ? 'selected' : '' }}>Day</option>
                <option value="E" {{ $course && $course->shift == 'Evening' ? 'selected' : '' }}>Evening</option>
            </select>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 col-12 mb-1">

            <label for="subcategory_name">Category Name</label>
            <div class="input-group flex-nowrap">
                <select type="text" name="category_id" data-url="{{ route('admin.course.categories.select') }}" data-ajax="true"
                    class="form-control input-group-prepend select2" placeholder="Username" aria-label="Username"
                    aria-describedby="basic-addon1">
                    <option selected value="{{ $course ? $course?->category_id : 0 }}">
                        {{ $course ? $course?->category?->name : 'Select Category' }}
                    </option>
                </select>

                @if(Auth::hasP('course_categories create'))
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="button_ajax(this)"
                            data-dialog=" modal-dialog-scrollable modal-lg" data-title="Add New  Category"
                            data-href="{{ route('admin.course.category.create') }}" data-target="#ajax_modal_dialog_add">+ </button>
                    </div>
                @endif
            </div>

        </div>


    <div class="col-xl-6 col-md-6 col-12 mb-1">

        <div class="">
            <label type="button" onclick="upload_select(this)">Cover Image <br>
                <input type="text" name="attachment" id="upload_id" value="{{ $course ? $course->attachment : 0 }}"
                    class="form-control mb-2" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($course ? $course->attachment : 0) }}" alt="">
            </label>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="yt_id">Course Overview (Yotube video id) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="yt_id" name="yt_id" placeholder="Enter yt_id"
                value="{{ $course ? $course->yt_id : old('yt_id') }}" required>

        </div>
    </div>


    <div class="col-md-12">
        <div class="form-group">
            <label for="description">Short Description <span class="text-danger">*</span></label>
            <textarea type="text" class="form-control " id="short_description" name="short_description"
                placeholder="Enter short_description"
                required>  {{ $course ? $course->short_description : old('short_description') }} </textarea>

        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="description">Description <span class="text-danger">*</span></label>
            <textarea type="text" class="form-control summernote" id="description" name="description"
                placeholder="Enter description"
                required>  {{ $course ? $course->description : old('description') }} </textarea>

        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="start_enroll">Start Enroll <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="start_enroll" name="start_enroll"
                placeholder="Enter start enroll" value="{{ $course ? $course->start_enroll : old('start_enroll') }}"
                required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="end_enroll">End Enroll <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="end_enroll" name="end_enroll" placeholder="Enter end enroll"
                value="{{ $course ? $course->end_enroll : old('end_enroll') }}" required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="course_start">Course Start <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="course_start" name="course_start"
                placeholder="Enter course start" value="{{ $course ? $course->course_start : old('course_start') }}"
                required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="course_end">Course End <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="course_end" name="course_end" placeholder="Enter course end"
                value="{{ $course ? $course->course_end : old('course_end') }}" required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="certificate_publish">Certificate Publish Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="certificate_publish" name="certificate_publish"
                placeholder="Enter certificate publish date"
                value="{{ $course ? $course->certificate_publish : old('certificate_publish') }}" required>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="avialable_seat">Avialable Seat <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="avialable_seat" name="avialable_seat"
                placeholder="Enter certificate publish date"
                value="{{ $course ? $course->avialable_seat : old('avialable_seat') }}" required>

        </div>
    </div>

    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="form-group">
            <label for="name">Status</label>
            <select name="status" class="form-control">
                <option value="1" {{$course && $course->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{$course && $course->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>


    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>

    </div>

</form>
