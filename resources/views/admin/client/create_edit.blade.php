<form action="{{ $client ? route('admin.clients.update', $client->id) : route('admin.clients.store') }}" method="POST"
    class="" enctype="multipart/form-data">
    @csrf

    @if ($client)
        @method('put')

    @endif

    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $client ? $client->name : '' }}" name="name"
                    placeholder="Enter Name">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name"
                    placeholder="Enter Company Name" value="{{ $client ? $client->company_name : '' }}">

            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">

            <div class="">
                <label type="button" onclick="upload_select(this)"> Image <br>
                    <input type="text" name="upload_id" id="upload_id"
                        value="{{ $client ? $client->upload_id : 0 }}" class="form-control mb-2" hidden>
                    <img style="max-height: 60px" src="{{ dynamic_asset($client ? $client->upload_id : 0) }}"
                        alt="">
                </label>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $client && $client->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $client && $client->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">Update</button>

</form>