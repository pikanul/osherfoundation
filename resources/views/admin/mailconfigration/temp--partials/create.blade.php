<form action="{{ route('admin.Translation.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form-group mb-2">
        <label for="key">Key</label>
        <input type="text" name="key" class="form-control" id="key" placeholder="Enter key">
    </div>
    <div class="form-group mb-2">
        <label for="type">Type</label>
        <input type="text" name="type" class="form-control" id="type" placeholder="Enter Type">
    </div>
    @foreach ($language_all as $language)
        <div class="form-group mb-2">
            <label for="{{ $language->name }}">Langyage {{ $language->name }}</label>
            <input type="text" name="languages[{{ $language->id }}]" class="form-control" id="{{ $language->name }}"
                placeholder="Language {{ $language->name }}">
        </div>
    @endforeach


    <div class="d-flex justify-content-end">
        <button class="btn btn-warning" type="submit">Save</button>
    </div>

</form>
