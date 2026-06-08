<form action="{{ $news ? route('admin.news.newses.update', $news->id) : route('admin.news.newses.store') }}"
    method="POST" class="" enctype="multipart/form-data">
    @csrf

    @if($news)
    @method('put')
    @endif
    <div class="row">
        <div class=" col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title"
                    value="{{ $news && $news->title ? $news->title : '' }}" name="title" placeholder="Enter Title"
                    oninput="generateSlugMake(this, '#slug')">
            </div>
        </div>

        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" value="{{ $news ? $news->slug : old('slug') }}"
                    name="slug" placeholder="Enter slug">
            </div>
        </div>
        <div class=" col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="publish_date">Publish Date</label>
                <input type="date" name="publish_date" class="form-control flatpickr-basic flatpickr-input active"
                    id="date" value="{{ $news ? $news->publish_date : old('publish_date', date('Y-m-d')) }}">
            </div>
            @if($request->news_category_id != null && $request->has('news_category_id'))
                <input type="hidden" name="news_category_id" value="{{ $request->news_category_id }}">
            @endif
        </div>
        @if(  $request->news_category_id == null && !$request->has('news_category_id'))
        <div class=" col-md-6 col-12 mb-1">

            <div class="form-group">
                <label for="news_category_id">News Category</label>
                <select class="select2 form-control" name="news_category_id"
                    data-url="{{ route('admin.news.categories.select') }}" data-ajax="true">
                    <option value="{{ $news && $news->news_category_id ? $news->news_category_id : ''}}"
                        {{ $news && $news->news_category_id == 0 ? 'selected' : '' }}>
                        {{ $news && $news->category ? $news->category->name : 'Select Category'}}
                    </option>
                </select>
            </div>
        </div>
        @endif
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="">
                <label type="button" onclick="upload_select(this)">Cover Image <br>
                    <input type="text" name="news_image" id="news_image" value="{{ $news ? $news->news_image : 0 }}"
                        class="form-control mb-2" hidden>
                    <img style="max-height: 60px" src="{{ dynamic_asset($news ? $news->news_image : 0) }}" alt="">
                </label>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="">
                <label type="button" onclick="upload_select(this)">PDF File <br>
                    <input type="text" name="pdf_file_id" id="pdf_file_id" value="{{ $news ? ($news->pdf_file_id ?? 0) : 0 }}"
                        class="form-control mb-2" hidden>
                    @php
                        $pdfFileId = $news ? ((int) ($news->pdf_file_id ?? 0)) : 0;
                        $pdfUrl = $pdfFileId > 0 ? dynamic_asset($pdfFileId) : '';
                    @endphp
                    <img style="max-height: 60px" src="{{ $pdfFileId > 0 ? asset('preset/pdf.png') : dynamic_asset(0) }}" alt="">
                </label>
                @if($pdfFileId > 0)
                    <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-eye"></i></a>
                @endif
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="use_pdf_after_cover" name="use_pdf_after_cover" value="1"
                    {{ (int) ($news->use_pdf_after_cover ?? 0) === 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="use_pdf_after_cover">Use PDF after cover image</label>
            </div>
        </div>


        <div class="col-md-12 col-12 mb-1">
            <div class="form-group">
                <label for="short_descripiton">Short Description</label>
                <textarea name="short_descripiton" class="form-control" cols="30" rows="5"
                    placeholder="Enter Short Descripiton">{{ $news && $news->short_descripiton ? $news->short_descripiton : '' }}</textarea>
            </div>
        </div>
        <div class=" col-12 mb-1">
            <div class="form-group">
                <label for="long_description">Long Description</label>
                <textarea name="long_description" class="summernote form-control" cols="30" rows="10"
                    placeholder="Enter Long Descripiton">{{ $news && $news->long_description ? $news->long_description : '' }}</textarea>
            </div>
        </div>

    </div>

    <div class="text-right">
        <button class="btn btn-primary waves-effect waves-float waves-light float-right"
            type="submit">{{ $news ? 'Update' : 'Create' }}</button>
    </div>

</form>
