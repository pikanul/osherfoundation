<div class="row">
    <div class="d-none d-lg-block col-4 scroll_items_sticky">

        <ul>
            @foreach ($data as $key => $items)
            @php
            $id_generate = str_replace('/', '', str_replace('&', 'and', str_replace(' ', '_', $items->title)));
            if(isset($items->permission)){
            if(Auth::hasP($items->permission) == false){
            continue;
            }


            }
            @endphp
            <li>
                <a href="javascript:void(0)" data-id="#{{ $id_generate }}" onclick="scroll_top_tab(this)">
                    {{ $items->title }}
                </a>
            </li>
            @endforeach
        </ul>

    </div>
    <div class="col-lg-8">

        <div class="row">
            @foreach ($data as $key => $items)
            @php
            $id_generate = str_replace('/', '', str_replace('&', 'and', str_replace(' ', '_', $items->title)));

            if(isset($items->permission)){
            if(Auth::hasP($items->permission) == false){
            continue;
            }
            }

            @endphp
            <div class="tab_content_modal  {{ isset($items->col) ? $items->col : 'col-12' }}" id="{{ $id_generate }}"
                style="display:none;">
                <div class="card">
                    <div class="card-header">
                        {{ isset($items->title) ? $items->title : '' }}

                        @if (isset($items->label) || isset($items->referance))
                        <a @if (isset($items->referance)) href="{{ $items->referance }}" target="_blank" @endif>
                            {{ isset($items->label) ? $items->label : 'Reference' }}
                        </a>
                        @endif


                    </div>
                    <div class="card-body">

                        {{-- update Sms Settings --}}
                        <form class=" form_ajax_submit"
                            action="{{ isset($items->action) ? $items->action : route('admin.setting.store.update') }}"
                            enctype="multipart/form-data"
                            method="{{ isset($items->method) ? ($items->method == 'GET' ? 'GET' : 'POST') : 'POST' }}">
                            @csrf
                            @isset($items->method)
                            @if ($items->method == 'put')
                            @method('put')
                            @endif
                            @endisset

                            <input hidden type="text" name="key_code" value="{{ $items->key }}">
                            <div class="row">

                                @foreach ($items->data as $setting)
                                @php
                                $settings_value = isset($setting->value)
                                ? $setting->value
                                : settings($setting->name, $items->key);

                                if(isset($setting->theme)){
                                if(in_array($theme, $setting->theme) == false){
                                continue;
                                }
                                }
                                @endphp

                                <div class="mb-3  {{ isset($setting->col) ? $setting->col : 'col-md-6 col-xl-4' }}">

                                    {{-- Label name --}}
                                    <label for="{{ $setting->name }}">



                                        {{ Str::title(Str::replace('_', ' ', $setting->name)) }}

                                        @if (isset($setting->reference) || isset($setting->label))
                                        <a @if (isset($setting->reference)) href="{{ $setting->reference }}"
                                            target="_blank" @endif>
                                            {{ isset($setting->label) ? $setting->label : 'Reference' }}
                                        </a>

                                        @endif
                                        &nbsp;
                                        &nbsp;

                                        {{-- For Image Preview --}}
                                        @if (isset($setting->image))
                                        <a href="{{ asset('preset/' . $setting->image) }}" title="Preview"
                                            data-title="Title: {{ Str::title(Str::replace('_', ' ', $setting->name)) }}"
                                            class="glightbox" data-gallery="{{ $id_generate }}"><i
                                                class="fas fa-images"></i></a>
                                        @endif
                                    </label>
                                    <br />

                                    {{-- For Image --}}
                                    @if ($setting->name === 'banner_image')
                                    @php
                                    [$settings_value, $actual_value] = settings(
                                    $setting->name,
                                    $items->key,
                                    1,
                                    );
                                    $heroImageIds = collect(explode(',', (string) $actual_value))
                                    ->filter(fn($id) => trim($id) !== '')
                                    ->values();
                                    @endphp

                                    <div class="items_container_image hero-image-settings">
                                        <div class="items_filed_iamge hero-image-fields">
                                            @if($heroImageIds->isNotEmpty())
                                            @foreach (dynamic_assets($heroImageIds->implode(',')) as $imageId => $imageUrl)
                                            <div class="image_items_removeable">
                                                <label type="button" class="multiple" onclick="upload_select(this)">
                                                    <input type="hidden" name="multiple_settings[{{ $setting->name }}][]"
                                                        value="{{ $imageId }}" class="form-control mb-2">
                                                    <img style="max-height: 60px; min-height: 40px; min-width: 80px; background: #eee;"
                                                        src="{{ $imageUrl }}" alt="">
                                                </label>
                                                <span onclick="remove_element_image(this)">x</span>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="image_items_removeable">
                                                <label type="button" class="multiple" onclick="upload_select(this)">
                                                    <input type="hidden" name="multiple_settings[{{ $setting->name }}][]"
                                                        value="{{ $actual_value }}" class="form-control mb-2">
                                                    <img style="max-height: 60px; min-height: 40px; min-width: 80px; background: #eee;"
                                                        src="{{ $settings_value }}" alt="">
                                                </label>
                                                <span onclick="remove_element_image(this)">x</span>
                                            </div>
                                            @endif
                                        </div>
                                        <button type="button" class="add_image_filed btn btn-primary"
                                            onclick="add_more_setting_image(this, '{{ $setting->name }}')">+</button>
                                    </div>

                                    {{-- For Image --}}
                                    @elseif (collect(['image', 'logo', 'icon'])->contains(fn($word) =>
                                    str_contains($setting->name, $word)))
                                    @php
                                    [$settings_value, $actual_value] = settings(
                                    $setting->name,
                                    $items->key,
                                    1,
                                    );
                                    @endphp



                                    <div class="">
                                     <label type="button" onclick="upload_select(this)">
                                            <input type="hidden" name="multiple_settings[{{ $setting->name }}]"
                                                value="{{ $actual_value }}" id="{{ $setting->name.'_id' }}"
                                                class="form-control mb-2">
                                            <img style="max-height: 60px; min-height: 40px; min-width: 80px; background: #eee;"
                                                src="{{ $settings_value }}" alt="">
                                        </label>
                                    </div>

                                    {{-- For Color Picker --}}
                                    @elseif(str_contains($setting->name, 'color'))
                                    <input type="color" class="form-control"
                                        name="multiple_settings[{{ $setting->name }}]" id="{{ $setting->name }}"
                                        value="{{ $settings_value }}">

                                    @elseif(isset($setting->option))

                                    <select class="form-control select2 "
                                        {{ isset($setting->type) && $setting->type == 'multiple' ? 'multiple': '' }}
                                        name="multiple_settings[{{ $setting->name }}]{{ isset($setting->type) && $setting->type == 'multiple' ? '[]': '' }}">
                                        @php
                                        if(isset($setting->type) && $setting->type == 'multiple'){
                                        $settings_value = explode(',', $settings_value);
                                        }else{
                                        $settings_value = [$settings_value];
                                        }
                                        @endphp
                                        @foreach ($setting->option as $option)
                                        <option {{ in_array($option, $settings_value) ? 'selected' : '' }}
                                            value="{{ str_replace(' ', '+', $option) }}">{{ $option }}</option>

                                        @endforeach
                                    </select>

                                    {{-- text Editor --}}
                                    @elseif(str_contains($setting->name, 'editor'))
                                    <textarea class="form-control summernote"
                                        name="multiple_settings[{{ $setting->name }}]"
                                        id="{{ $setting->name }}">{{ $settings_value }}</textarea>



                                    {{-- text text --}}
                                    @elseif(collect(['text',
                                    'description'])->contains(fn($word)=>str_contains($setting->name, $word)))
                                    <textarea class="form-control {{ isset($setting->class) ? $setting->class : '' }}"
                                        name="multiple_settings[{{ $setting->name }}]"
                                        id="{{ $setting->name }}">{{ $settings_value }}</textarea>



                                    {{-- Markup Code for --}}
                                    @elseif(str_contains($setting->name, 'code'))
                                    <textarea class="form-control" name="multiple_settings[{{ $setting->name }}]"
                                        id="{{ $setting->name }}">{{ $settings_value }}</textarea>




                                    {{--  Status --}}
                                    @elseif(str_contains($setting->name, 'status'))
                                    <div>
                                        <input type="checkbox" checked class="" hidden=""
                                            name="multiple_settings[{{ $setting->name }}]" value="0">
                                        <input type="checkbox" class="toggle" @if ($settings_value==1) checked @endif
                                            name="multiple_settings[{{ $setting->name }}]" id="{{ $setting->name }}"
                                            value="1">
                                    </div>




                                    {{--  Default Input Box --}}
                                    @else
                                    <input type="text" class="form-control" placeholder="Enter text ..."
                                        name="multiple_settings[{{ $setting->name }}]" id="{{ $setting->name }}"
                                        value="{{ $settings_value }}"
                                        @if(isset($setting->required) && $setting->required === false) data-rule-required="false" aria-required="false" @endif
                                        @if(isset($setting->required) && $setting->required === true) required @endif>
                                    @endif

                                </div>
                                @endforeach
                            </div>

                            <div class="pt-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        <br />



                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function add_more_setting_image(button, settingName) {
        const container = $(button).closest('.items_container_image').find('.items_filed_iamge').first();
        const placeholder = @json(dynamic_asset(0));
        const field = `<div class="image_items_removeable">
            <label type="button" class="multiple" onclick="upload_select(this)">
                <input type="hidden" name="multiple_settings[${settingName}][]" class="form-control mb-2">
                <img style="max-height: 60px; min-height: 40px; min-width: 80px; background: #eee;" src="${placeholder}" alt="">
            </label>
            <span onclick="remove_element_image(this)">x</span>
        </div>`;

        container.append(field);
    }
</script>


<style>
.scroll_items_sticky {
    position: sticky;
    height: max-content;
    top: 0;
}

.scroll_items_sticky ul {
    list-style: none;
    padding: 0;
    border-radius: 5px;
    overflow: hidden;
}

.d-none.d-lg-block.col-4.scroll_items_sticky {}

.scroll_items_sticky ul li a {
    padding: 13px;
    display: block;
    background: #ffffff;
    color: #000000;
    border-bottom: 1px dashed #c1c1c1;
}

.scroll_items_sticky ul li:last-child a {
    border: none;
}

.scroll_items_sticky ul li a.active,
.scroll_items_sticky ul li a:hover {
    background: #007bff;
    color: white;
    font-weight: 500;
}

@media (max-width:991px) {
    .tab_content_modal {
        display: block !important;
    }
}

.gdesc-inner {
    background: #be9393;
}

.gdesc-inner .gslide-title {
    margin-bottom: 0 !important;
}
</style>

<script>
function scroll_top_tab(thi) {
    document.querySelectorAll('.scroll_items_sticky a').forEach(function(e) {
        e.classList.remove('active');
    })

    document.querySelectorAll('.tab_content_modal').forEach(function(e) {
        e.style.display = 'none';
    });


    // Active First Items if not target_id
    if (thi) {
        // change url
        let target_items = thi.getAttribute('data-id');

        if (thi) {
            history.pushState(null, null, target_items);
            thi.classList.add('active');
            $(target_items).css('display', '');
        }


    } else {
        document.querySelectorAll('.scroll_items_sticky a')[0].classList.add('active');
        document.querySelectorAll('.tab_content_modal')[0].style.display = '';
    }


}
// get hash value
if (window.location.hash) {
    let data_hash = window.location.hash;
    let target = document.querySelector('.scroll_items_sticky a[data-id="' + data_hash + '"]');

    setTimeout(function() {
        target.click();
    }, 1000);

} else {
    scroll_top_tab();
}
</script>
