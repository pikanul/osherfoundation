<form class="row g-3"
    action="{{ $lession ? route('admin.course.lession.update', $lession->id) : route('admin.course.lession.store') }}" method="POST"
    enctype="multipart/form-data">

    <div class="col-12">
        @csrf
        @if ($lession)
            @method('PUT')
        @endif
        <input type="number" name="course_id" hidden value="{{ $course_id }}" id="">
    </div>


    <div class="col-md-12">
        <div class="mb-3">
            <label for="inputlession" class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $lession->name ?? '') }}" class="form-control"
                id="inputlession">
        </div>
    </div>


    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="">
            <label type="button" onclick="upload_select(this)">Cover Image <br>
                <input type="text" name="upload_id" id="upload_id" value="{{ $lession ? $lession->cover_image : 0 }}"
                    class="form-control mb-2" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($lession ? $lession->cover_image : 0) }}" alt="">
            </label>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="">
            <label type="button" onclick="upload_select(this)">Attachment <br>
                <input type="text" name="attachment" id="attachment" value="{{ $lession ? $lession->attachment : 0 }}"
                    class="form-control mb-2" hidden>
                @php
                    $file =dynamic_asset($lession ? $lession->attachment : 0);
                    $type = pathinfo($file, PATHINFO_EXTENSION);
                    $type = strtolower($type);
                    if($type == 'pdf'){
                        $type = 'pdf';
                        $file_d = asset('preset/pdf.png');
                    }else{
                        $file_d = $file;
                    }
                @endphp
                <img style="max-height: 60px" src="{{ $file_d }}" alt="">
            </label>
            <a href="{{ $file }}" target="_blank"><i class="fas fa-eye"></i></a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="inputLessionType" class="form-label">Video Format</label>
            <select name="format_video" class="form-control" id="" required onchange="
                document.querySelectorAll('.video').forEach(e => e.style.display = 'none');
                document.querySelector('.video.'+this.value).style.display = 'block'
            ">
                <option value="" >None</option>
                <option value="upload" {{$lession && $lession->format_video == 'upload' ? 'selected' : '' }}>Upload</option>
                <option value="vimeo" {{$lession && $lession->format_video == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                <option value="youtube" {{ $lession && $lession->format_video == 'youtube' ? 'selected' : '' }}>Youtube</option>
            </select>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-12 mb-1">
        <div class="video upload" style="{{ $lession && $lession->format_video == 'upload' ? '' : 'display:none' }}">
            <label type="button" onclick="upload_select(this)">Video <br>
                <input type="text" name="video[upload]" id="video1" value="{{ $lession && $lession->format_video == 'upload' ? $lession->video : 0 }}"
                    class="form-control mb-2" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($lession ? $lession->video : 0) }}" alt="">
            </label>
        </div>
        <div class="video youtube" style="{{ $lession && $lession->format_video == 'youtube' ? '' : 'display:none' }}">
            <label type="button" >Video <br>
                <input type="text" name="video[youtube]" id="video2" value="{{ $lession && $lession->format_video == 'youtube' ? $lession->video : '' }}" oninput="document.querySelector('#youtube_frame').src= 'https://www.youtube.com/embed/'+this.value"
                    class="form-control mb-2" placeholder="Enter Youtube Video Id">
                <iframe src="https://www.youtube.com/embed/{{ $lession ? $lession->video : 0 }}" id="youtube_frame"
                    allowfullscreen></iframe>
            </label>
        </div>
        <div class="video vimeo" style="{{ $lession && $lession->format_video == 'vimeo' ? '' : 'display:none' }}">
            <label type="button" >Video <br>
              <input type="text" name="video[vimeo]" id="video3" value="{{ $lession && $lession->format_video == 'vimeo' ? $lession->video : '' }}" oninput="document.querySelector('#vimeo_frame').src= 'https://player.vimeo.com/video/'+this.value"
                  class="form-control mb-2" placeholder="Enter Vimeo Video Id">

                  <iframe title="vimeo-player" src="https://player.vimeo.com/video/{{ $lession ? $lession->video : 0 }}" id="vimeo_frame" frameborder="0" referrerpolicy="strict-origin-when-cross-origin" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"   allowfullscreen></iframe>
            </label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="inputLessionType" class="form-label">Lession
                Type</label>
            <select name="lession_type" class="form-control" id="">
                <option value="lock" {{ $lession && $lession->lession_type == 'lock' ? 'selected' : '' }}>Lock</option>
                <option value="unlock" {{ $lession && $lession->lession_type == 'unlock' ? 'selected' : '' }}>Unlock</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="inputStatus" class="form-label">Status</label>
            <select name="status" class="form-control" id="">
                <option value="1" {{ $lession && $lession->status == '1' ? 'selected' : '' }}>
                    Active</option>
                <option value="0" {{  $lession && $lession->status == '0' ? 'selected' : '' }}>
                    Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="mb-3">
            <label for="inputDescription" class="form-label">Description</label>
            <textarea name="description" class="form-control editor" cols="5"
                rows="3">{{ old('description', $lession->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-12">
        <div class="mb-3">
            <button type="submit" class="btn btn-info btn-sm">Update</button>
        </div>

    </div>
</form>


