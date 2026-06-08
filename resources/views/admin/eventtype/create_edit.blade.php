<form
    action="{{ $eventtype ? route('admin.eventtypes.update', $eventtype->id) : route('admin.eventtypes.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if ($eventtype)
    @method('PUT')

    @endif

    <div class="row">
        <div class="col-12 mb-1">
            <div class="form-group">
                <label for="name">Name <span class="text-danger"> (Required)</span></label>
                <input type="text" name="name" value="{{ $eventtype ? $eventtype->name :'' }}" class="form-control"
                    id="inputeventtype">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="color">Color <span class="text-warning"> (Optional)</span></label>
                <input type="color" name="color" value="{{ $eventtype ? $eventtype->color : '' }}" class="form-control"
                    id="inputeventtype">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Status <span class="text-danger"> (Required)</span></label>
                <select name="status" class="form-control" id="">
                    <option value="1"
                        {{ $eventtype ? ($eventtype->status == 1 ? 'selected' : '') : '' }}>Active
                    </option>
                    <option value="0"
                        {{ $eventtype ? ($eventtype->status == 0 ? 'selected' : '') : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
        </div>
        <div class="col-xl-12 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Type <span class="text-danger"> (Required)</span></label>
                @php
                    $options = collect([
                        'General',
                       
                    ]);
                @endphp

               <select name="type" class="form-control">
                    @foreach ($options as $label => $value)
                        <option value="{{ $value }}"
                            {{ isset($eventtype) && $eventtype->type == $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>

            </div>
        </div>

    </div>


    <button class="btn btn-primary waves-effect waves-float waves-light float-right"
        type="submit">{{ $eventtype ? 'Update' : 'Create' }}</button>

    </div>
</form>
