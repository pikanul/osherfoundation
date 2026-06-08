<form action="{{ $slider ? route('admin.sliders.update', $slider->id) : route('admin.sliders.store') }}" method="POST"
    class="" enctype="multipart/form-data">
    @csrf

    @if ($slider)
        @method('put')

    @endif
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" value="{{ $slider ? $slider->title : '' }}"
                    name="title" placeholder="Enter Title">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="sub_title">Sub Title</label>
                <input type="text" class="form-control" id="sub_title" value="{{ $slider ? $slider->sub_title : '' }}"
                    name="sub_title" placeholder="Enter Sub Title">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">


            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id" value="{{ $slider ? $slider->upload_id : 0 }}"
                        class="form-control mb-2" hidden>
                    @if (\Str::endsWith( dynamic_asset($slider ? $slider->upload_id : 0), [ '.mp4', '.mov', '.avi', '.wmv', '.flv', '.mkv' ]))
                        <video width="320" height="240" controls preload="none" ontrols="false">
                            <source src="{{ dynamic_asset($slider ? $slider->upload_id : 0) }}" type="video/mp4">
                            Your browser does not support the video tag.
                          </video>  
                        @else

                            <img style="max-height: 60px" src="{{ dynamic_asset($slider ? $slider->upload_id : 0) }}" alt="">
                        @endif
                </label>
            </div>

        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="link">Link</label>
                <input type="text" class="form-control" id="link" value="{{ $slider ? $slider->link_text : '' }}"
                    name="link_text" placeholder="Enter Link">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{$slider && $slider->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{$slider && $slider->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">Update</button>

</form>