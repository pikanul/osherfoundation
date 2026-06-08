<form action="{{$admin ?  route('admin.admin.update', $admin->id)  : route('admin.admin.store')}}" method="POST" class="" enctype="multipart/form-data">
    @csrf
    @if ($admin)
    @method('put')
    @endif
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $admin ? $admin->name : '' }}" name="name"
                    placeholder="Enter Name">

            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $admin ? $admin->email : ''}}" name="email"
                    placeholder="Enter Email">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile"
                    value="{{$admin ? $admin->mobile : ''}}">

            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="degination">Designation</label>
                <input type="text" class="form-control" id="degination" name="degination" placeholder="Enter Designation"
                    value="{{$admin ? $admin->degination : ''}}">

            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="active">Active</label>
                <select name="is_active" class="form-control select2">
                    <option value="">Select User Status</option>
                    <option value="1" {{ $admin && $admin->is_active == 1 ? 'selected' : ''}}>Active</option>
                    <option value="0" {{ $admin && $admin->is_active == 0 ? 'selected' : ''}}>Inactive</option>
                </select>

            </div>
        </div>


        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="text" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Enter Password Confirmation">
            </div>
        </div>


    </div>
    <button class="btn btn-primary waves-effect waves-float waves-light" type="submit">Submit</button>
</form>
