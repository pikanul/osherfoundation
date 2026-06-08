<form action="{{route('admin.admin.update.permission', $admin->id) }}" method="POST" class="" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" value="{{ $admin ? $admin->name : '' }}" name="name" disabled
            placeholder="Enter Name">
      
    </div>



    <table class="table table-bordered table-striped table-hover">
        <tr>
            <td>
                <div>
                    <label for="selectAll_permission">Select All
                        <input type="checkbox" onchange="selectPermission(this)" id="selectAll_permission">
                    </label>
                </div>
            </td>
            <td></td>
        </tr>
        @foreach ($permissions as $key => $items)
        <tr>
            <td>
                <label for="{{ $key }}">
                    {{ Str::title($key) }}
                    <input type="checkbox" onchange="target_base(this)" id="{{ $key }}">
                </label>
            </td>
            <td>

                @foreach ($items as $item)
                    @if(Auth::hasP($item) || Auth::user()->id == 1)
                    <label for="target_id_{{ str_replace(' ', '_', $item) }}" class="badge badge-primary">
                        <input id="target_id_{{str_replace(' ', '_', $item)}}" {{ in_array($item, $current_permission) ? 'checked' : '' }}  name="permission[]" type="checkbox" value="{{ $item }}">&nbsp;  {{ $item }}
                    </label>
                    @endif
                @endforeach
            </td>
        </tr>
        {{-- @endcan --}}
        @endforeach
    </table>


    </div>
    <button class="btn btn-primary waves-effect waves-float waves-light" type="submit">Submit</button>
</form>