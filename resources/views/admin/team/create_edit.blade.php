<form action="{{ $team ? route('admin.teams.update', $team->id) : route('admin.teams.store') }}" method="POST" class="" enctype="multipart/form-data">
    @csrf
    @if ($team)
        @method('put')
    @endif

    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $team ? $team->name : '' }}" name="name"
                    placeholder="Enter Name" required>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" class="form-control" id="designation"
                    value="{{ $team ? $team->designation : '' }}" name="designation"
                    placeholder="Enter Designation" required>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="" {{ $team && $team->type ? '' : 'selected' }} disabled>Select Type</option>
                    <option value="leader" {{ $team && $team->type == 'leader' ? 'selected' : '' }}>Leader</option>
                    <option value="general" {{ $team && $team->type == 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
        </div>

        <div class=" col-12 mb-1">
            <div class="form-group">
                <label for="short_des">Short Description</label>
                <textarea name="short_des" id="short_des" class="form-control summernote" cols="2"
                    rows="1">{{ $team ? $team->short_des : '' }}</textarea>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $team ? $team->email : '' }}"
                    name="email" placeholder="Enter Email">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" value="{{ $team ? $team->phone : '' }}"
                    name="phone" placeholder="Enter Phone">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">

            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id"
                        value="{{ $team ? $team->upload_id : 0 }}" class="form-control mb-2" hidden required>
                    <img style="max-height: 60px" src="{{ dynamic_asset($team ? $team->upload_id : 0) }}"
                        alt="">
                </label>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $team && $team->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $team && $team->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">
        {{ $team ? 'Update' : 'Create' }}
    </button>

</form>
