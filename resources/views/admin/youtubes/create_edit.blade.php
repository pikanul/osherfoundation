<form action="{{ $youtubes ? route('admin.youtubes.update', $youtubes->id) : route('admin.youtubes.store') }}"
  method="POST" class="" enctype="multipart/form-data">
  @csrf

  @if ($youtubes)
    @method('put')
  @endif

  <div class="row">
    <div class="col-xl-6 col-md-6 col-12 mb-1">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title"
          value="{{ $youtubes ? $youtubes->title : '' }}">

      </div>
    </div>


    <div class="col-xl-6 col-md-6 col-12 mb-1">
      <div class="">
        <label type="button" onclick="upload_select(this)"> Image <br>
          <input type="text" name="upload_id" id="upload_id" value="{{ $youtubes ? $youtubes->upload_id : 0 }}"
            class="form-control mb-2" hidden>
          <img style="max-height: 60px" src="{{ dynamic_asset($youtubes ? $youtubes->upload_id : 0) }}" alt="">
        </label>
      </div>
    </div>

    <div class="col-xl-6 col-md-6 col-12 mb-1">
      <div class="form-group">
        <label for="video_url">Video ID</label>
        <input type="text" class="form-control" id="video_url" name="video_url" oninput="changeVideoUrl(this)"
          placeholder="Enter YouTube URL" value="{{ $youtubes ? $youtubes->video_url : '' }}">
      </div>
       <div class="form-group">
        <label for="name">Status</label>
        <select name="status" class="form-control">
          <option value="1" {{$youtubes && $youtubes->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{$youtubes && $youtubes->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
    </div>

    <div class="col-xl-6 col-md-6 col-12 mb-1">
      <div class="form-group">
        <iframe width="100%" height="200"
          src="{{ $youtubes && $youtubes->youtube_embed_url ? $youtubes->youtube_embed_url . '?rel=0' : '' }}"
          title="YouTube video player" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen></iframe>
      </div>
    </div>

    <div class=" col-12 mb-1">
      <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control summernote" id="editor1" cols="5"
          rows="3">{{ $youtubes ? $youtubes->description : old('description', '') }}</textarea>
      </div>
    </div>

  </div>
  <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">Update</button>

  </div>
</form>

<script>
  function extractYouTubeId(value) {
    if (!value) return null;
    const v = value.trim();

    if (/^[a-zA-Z0-9_-]{11}$/.test(v)) return v;

    try {
      const url = new URL(v);
      const host = url.hostname.toLowerCase();

      if (host.includes('youtu.be')) {
        const id = url.pathname.replace('/', '');
        return /^[a-zA-Z0-9_-]{11}$/.test(id) ? id : null;
      }

      if (host.includes('youtube.com') || host.includes('youtube-nocookie.com')) {
        const fromQuery = url.searchParams.get('v');
        if (fromQuery && /^[a-zA-Z0-9_-]{11}$/.test(fromQuery)) return fromQuery;

        const match = url.pathname.match(/\/(embed|shorts|live)\/([a-zA-Z0-9_-]{11})/);
        if (match) return match[2];
      }
    } catch (e) {
      return null;
    }

    return null;
  }

  function changeVideoUrl(input) {
    var videoUrl = input.value;
    var iframe = document.querySelector('iframe');
    var id = extractYouTubeId(videoUrl);
    iframe.src = id ? ('https://www.youtube.com/embed/' + id + '?rel=0') : '';

  }

</script>
