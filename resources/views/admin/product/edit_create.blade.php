<form action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
    method="POST" class="" enctype="multipart/form-data">
    @csrf

    @if ($product)
        @method('put')
    @endif
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $product ? $product->name : '' }}"
                    name="name" placeholder="Enter Name" oninput="generateSlugMake(this, '#slug')">
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" value="{{ $product ? $product->slug : old('slug') }}"
                    name="slug" placeholder="Enter slug">
            </div>
        </div>
        @if (auth()->hasP('category', true))


        <div class="col-xl-6 col-md-6 col-12 mb-1">
            <label for="categoryName">Category Name</label>
            <div class="btn-group w-100">
                <select type="text" name="category_id" data-url="{{ route('admin.categories.select') }}"
                    data-ajax="true" class="form-control input-group-prepend select2 category_select"
                    placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    <option selected value="{{ $product ? $product?->category_id : 0 }}">
                        {{ $product ? $product?->category?->name : 'Select Category' }}
                    </option>
                </select>


                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" onclick="button_ajax(this)"
                        data-dialog=" modal-dialog-scrollable modal-lg" data-title="Add New  Category"
                        data-href="{{ route('admin.categories.create') }}" data-target="#ajax_modal_dialog_add">+
                    </button>
                </div>

            </div>
        </div>
         @endif
        @if(auth()->hasP('subcategory', true))
        <div class="col-lg-6">
            <label for="">Sub Category</label>
            <div class="btn-group mb-3 w-100">
                <select type="text" name="sub_category_id" data-url="{{ route('admin.subcategories.select') }}"
                    data-ajax="true" class="form-control input-group-prepend select2 select_subcategory"
                    placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    @if($product)
                        <option value="{{ $product ? ($product->sub_category_id ?? 0) : 0 }}">
                            {{ $product ? ($product->sub_category->name ?? 'Select Subcategory') : 'Select Subcategory'}}
                        </option>
                    @else
                        <option value="0">subcategory</option>
                    @endif
                </select>
                <button type="button" class="btn btn-primary input-group-append"
                    data-dialog=" modal-dialog-scrollable modal-dialog-centered" data-target="#ajax_modal_dialog_add"
                    onclick="button_ajax(this)" data-title="Add New  subcategory"
                    data-href="{{ route('admin.subcategories.create') }}">+</button>
            </div>
        </div>
        @endif

        <div class="col-lg-6 col-12 mb-1">
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" value="{{ $product ? $product->price : '' }}"
                    name="price" placeholder="Enter Price">
            </div>
        </div>
        <div class="col-xl-12 col-12 mb-1">
            <div class="form-group">
                <label for="specification">Short Description</label>
                <textarea type="text" class="form-control "  name="short_description"
                    placeholder="Enter Summary">{{ $product ? $product->short_description : '' }}</textarea>
            </div>
        </div>
        <div class="col-xl-12 col-12 mb-1">
            <div class="form-group">
                <label for="specification">Specification</label>
                <textarea type="text" class="form-control summernote" id="editor" name="specification"
                    placeholder="Enter Specification">{{ $product ? $product->specification : '' }}</textarea>
            </div>
        </div>
        <div class="col-xl-12 col-12 mb-1">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea type="text" class="form-control summernote" id="editor1" name="description"
                    placeholder="Enter Description">{{ $product ? $product->description : '' }} </textarea>
            </div>
        </div>


        <div class="col-lg-6">
            <label type="button" onclick="upload_select(this, 600, 600)"> Image <span class="text-danger">Size : 600px x
                    600px</span><br>
                <input type="text" name="upload_id" id="image" class="form-control mb-2"
                    value="{{ $product ? $product->upload_id : 0 }}" hidden>
                <img style="max-height: 60px" src="{{ dynamic_asset($product ? $product->upload_id : 0) }}" alt="">
            </label>
        </div>
        <div class="col-lg-6">
            Image Slides <span class="text-danger">Size : 600px x 600px</span>
            <div class="items_container_image">
                <div class="items_filed_iamge">
                    {{-- items --}}
                    @if($product)
                        @foreach (dynamic_assets($product->uploads_id) as $key => $item)
                            <div class="image_items_removeable">
                                <label type="button" class="multiple" onclick="upload_select(this, 600, 600)">
                                    <input type="text" hidden name="uploads_id[]" value="{{ $key }}" id="image"
                                        class="form-control mb-2" />
                                    @php
                                        $video_extension = pathinfo($item, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(in_array($video_extension, ['mp4', 'webm', 'ogg', 'mp3']))
                                        <video style="max-height: 60px" src="{{ $item }}" alt=""></video>
                                    @else
                                        <img style="max-height: 60px" src="{{ $item }}" alt="" />
                                    @endif

                                </label>
                                <span onclick="remove_element_image(this)">x</span>
                            </div>
                        @endforeach
                    @endif
                    {{-- items --}}

                </div>
                <button type="button" class="add_image_filed btn btn-primary" onclick="add_more_filed_image(600, 600)">
                    +
                </button>
            </div>
        </div>
    </div>
    <button class="btn btn-primary waves-effect waves-float waves-light float-right" type="submit">Update</button>

</form>

<script>
    $('.category_select').select2().on('change', function () {
        const selectedValue = $(this).val(); // Get the selected value
        console.log(selectedValue); // Log the selected value

        // Get the current data-url
        var data_url = $('.select_subcategory').data('url');

        // Create a URL object to manage parameters easily
        const url = new URL(data_url, window.location.origin); // Ensure base URL is included

        // Check if cat_id already exists
        if (url.searchParams.has('cat_id')) {
            // If it exists, update the existing cat_id
            url.searchParams.set('cat_id', selectedValue);
        } else {
            // If it doesn't exist, add it
            url.searchParams.append('cat_id', selectedValue);
        }

        // Update the data-url attribute
        $('.select_subcategory').data('url', url.toString());

        select2_caller();
    });
</script>
