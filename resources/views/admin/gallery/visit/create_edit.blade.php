<form action="{{ $visitgallery ? route('admin.gallery.visit.update', $visitgallery->id) : route('admin.gallery.visit.store') }}" method="POST" class="" enctype="multipart/form-data">
    @csrf
    @if ($visitgallery)
        @method('put')
    @endif
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                    value="{{ $visitgallery ? $visitgallery->name : ''}}">

            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id" value="{{ $visitgallery ? $visitgallery->upload_id : 0 }}"
                        class="form-control mb-2" hidden>
                    <img style="max-height: 60px" src="{{ dynamic_asset($visitgallery ? $visitgallery->upload_id : 0) }}" alt="">
                </label>
            </div>
        </div>
         <div class="col-lg-6">
            Image Slides <span class="text-danger">Size : 600px x 600px</span>
            <div class="items_container_image">
                <div class="items_filed_iamge">
                    {{-- items --}}
                    @if($visitgallery)
                        @foreach (dynamic_assets($visitgallery->upload_ids) as $key => $item)
                            <div class="image_items_removeable">
                                <label type="button" class="multiple" onclick="upload_select(this, 600, 600)">
                                    <input type="text" hidden name="uploads_id[]" value="{{ $key }}" id="image"
                                        class="form-control mb-2" />
                                    @php
                                        $video_extension = pathinfo($item, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(in_array($video_extension, ['mp4', 'webm', 'ogg', 'mp3']))
                                        <video style="max-height: 60px" src="{{ $item }}" alt=""></video>
                                    @else
                                        <img style="max-height: 60px" src="{{ $item }}" alt="" />
                                    @endif

                                </label>
                                <span onclick="remove_element_image(this)">x</span>
                            </div>
                        @endforeach
                    @endif
                    {{-- items --}}

                </div>
                <button type="button" class="add_image_filed btn btn-primary" onclick="add_more_filed_image(600, 600)">
                    +
                </button>
            </div>
        </div>
    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">{{ $visitgallery ? 'Update' : 'Create' }}</button>
    </div>
</form>