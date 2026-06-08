<form action="{{ $faq ? route('admin.faq.update', $faq->id) : route('admin.faq.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if ($faq)
        @method('put')
    @endif

    <div class="form-group mb-2">
        <label for="faq">Title</label>
        <input type="text" name="title" class="form-control mb-2" value="{{ $faq ? $faq->title : '' }}" id="faq" placeholder="Enter faq">
    </div>

    <div class="form-group mb-2">
        <label for="answer">faq answer</label>
        <textarea type="text" name="answer" class="form-control mb-2"  id="answer" placeholder="Enter faq answer">{{ $faq ? $faq->answer : '' }}</textarea>
    </div>



    <label for="status"> faq Status <br/>
        <input type="checkbox" checked class="" hidden  name="status"   value="0">
        <input type="checkbox" class="toggle"  placeholder="faq Status" name="status" {{ $faq && $faq->status == 1 ? 'checked' : '' }} id="status" value="1">
    </label>



    <div class="d-flex justify-content-end">
        <button class="btn btn-warning" type="submit">Save</button>
    </div>

</form>
