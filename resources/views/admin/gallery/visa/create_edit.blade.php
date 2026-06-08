<form action="{{ $visa ? route('admin.gallery.visa.update', $visa->id) : route('admin.gallery.visa.store') }}" method="POST" class="" enctype="multipart/form-data">
    @csrf
    @if ($visa)
        @method('put')
    @endif
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                    value="{{ $visa ? $visa->name : ''}}">

            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id" value="{{ $visa ? $visa->upload_id : 0 }}"
                        class="form-control mb-2" hidden>
                    <img style="max-height: 60px" src="{{ dynamic_asset($visa ? $visa->upload_id : 0) }}" alt="">
                </label>
            </div>
        </div>
        
    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">{{ $visa ? 'Update' : 'Create' }}</button>
    </div>
</form>