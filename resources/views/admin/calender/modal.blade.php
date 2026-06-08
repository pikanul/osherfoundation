<form id="eventForm"
    action="{{ isset($event) ? route('admin.calender.update', $event->id) : route('admin.calender.add') }}" method="POST">

    @csrf
    @if(isset($event)) @method('PUT') @endif
    <div class="row">
        <div class="col-md-3">
            <div class="md-2">
                Form Date <br>
                <input type="date" name="start_date" class="form-control" value="{{ $event?->start_date->format('Y-m-d') ?? $start_date }}" required>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mb-2">
                To Date <br>
                <input type="date" name="end_date" class="form-control" value="{{ $event?->end_date->format('Y-m-d') ?? $end_date }}"  required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-2">
                Form Time <br>
                <input type="time" name="start_time" class="form-control"  value="{{ $event?->start_time ?? $start_time }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-2">
                To Time <br>
                <input type="time" name="end_time" class="form-control" value="{{ $event?->end_time ?? $end_time }}" required>
            </div>
        </div>




        <div class="col-md-12">
            <div class="mb-2">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $event?->title ?? '' }}" required>
            </div>
        </div>
        <!-- for description -->

        <div class="col-md-12">
            <div class="mb-2">
                <label>Description</label>
                <textarea name="description" class="form-control summernote">{{ $event?->description ?? '' }}</textarea>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-2">
                <label>Event Type</label>
                <select type="text" name="type_id"
                    data-url="{{ route('admin.eventtypes.select') }}" data-ajax="true"
                    class="form-control input-group-prepend select2 category_select" placeholder="Username"
                    aria-label="Username" aria-describedby="basic-addon1">
                    <option selected value="{{ $event?->type_id ?? '' }}">
                        {{ $event->event_type_info?->name ?? 'Select Event' }}
                    </option>
                </select>
            </div>
        </div>
<!-- event visibility -->
        <div class="col-md-4">
            <div class="mb-2">
                <label>Visibility</label>
                <select name="visibility" class="form-control">
                    <option value="1" {{ (isset($event) && $event->visibility == '1') ? 'selected' : '' }}>Public</option>
                    <option value="0" {{ (isset($event) && $event->visibility == '0') ? 'selected' : '' }}>Private</option>
                </select>
            </div>
        </div>

    </div>

    <br/>
    <br/>
    <div class="text-right">
        <button type="submit" class="btn btn-primary">{{ isset($event) ? 'Update' : 'Add' }} Event</button>
    </div>
</form>

@if (isset($event) && $event)
<form action="{{ route('admin.calender.delete', $event->id) }}" method="POST" class="d-inline-block position-absolute" style="bottom: 73px; left: 22px;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete Event</button>
</form>

@endif


