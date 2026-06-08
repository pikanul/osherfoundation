<form
    action="{{ $depeartment ? route('admin.departments.update', $depeartment->id) : route('admin.departments.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if ($depeartment)
    @method('PUT')

    @endif

    <div class="row">
        <div class="col-md-4 mb-2">
            <label>Select Class</label>
            <select type="text" name="class_id" data-url="{{ route('admin.sm_classes.select') }}"
                data-ajax="true" class="form-control input-group-prepend select2 category_select"
                placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                <option selected value="{{ $depeartment ? $depeartment->class_id : 0 }}">
                    {{ $depeartment ? $depeartment?->class_info?->name : ' Select Class' }}
                </option>
            </select>
        </div>

        <div class="col-8 mb-1">
            <div class="form-group">
                <label for="name">Name <span class="text-danger"> (Required)</span></label>
                <input type="text" name="name" value="{{ $depeartment ? $depeartment->name :'' }}" class="form-control"
                    id="inputdepeartment">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Code <span class="text-warning"> (Optional)</span></label>
                <input type="text" name="code" value="{{ $depeartment ? $depeartment->name : '' }}" class="form-control"
                    id="inputdepeartment">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Status <span class="text-danger"> (Required)</span></label>
                <select name="status" class="form-control" id="">
                    <option value="active"
                        {{ $depeartment ? ($depeartment->status == 'active' ? 'selected' : '') : '' }}>Active
                    </option>
                    <option value="inactive"
                        {{ $depeartment ? ($depeartment->status == 'inactive' ? 'selected' : '') : '' }}>
                        Inactive
                    </option>
                </select>
            </div>
        </div>


        <div class=" col-12 mb-1">
            <div class="form-group">
                <label for="description">Description <span class="text-warning"> (Optional)</span></label>
                <textarea name="description" class="form-control" id="description" cols="5"
                    rows="3">{{  $depeartment ? $depeartment->description : old('description', '') }}</textarea>
            </div>
        </div>



    </div>


    <button class="btn btn-primary waves-effect waves-float waves-light float-right"
        type="submit">{{ $depeartment ? 'Update' : 'Create' }}</button>

    </div>
</form>
