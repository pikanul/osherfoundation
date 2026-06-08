<!-- Button trigger modal -->
<button type="button" hidden class="btn btn-primary upload_ajax_modalbtn" data-toggle="modal"
    data-target="#upload_ajax_modal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="upload_ajax_modal" tabindex="-1" role="dialog" aria-labelledby="upload_ajax_modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header ">

                <h5 class="modal-title" id="upload_ajax_modalLabel">File Manager</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">


                <div class="row mb-3">
                    {{-- Resize Panel --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-2 converter bg-secondary rounded">
                            <label for="converter_enabled" class="d-flex align-items-center gap-3">
                                <input type="checkbox" name="converter_enabled" class="form-check converter_enabled "
                                    id="converter_enabled">
                                Enabled to Resize
                            </label>

                            <div class="row">
                                <div class="col-12">
                                    <label for="resize-width">Resize Width:</label> <br>
                                    <input type="number" id="resize-width" name="resize-width" placeholder="Width"
                                        min="1" max="5000" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="resize-height">Resize Height:</label> <br>
                                    <input type="number" id="resize-height" name="resize-height" placeholder="Height"
                                        min="1" max="5000" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="quality">Quality (1-100):</label> <br>
                                    <input type="number" class="form-control" id="quality" name="quality"
                                        value="100" min="1" max="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end Resize Panel --}}

                    {{-- Drag and Drop --}}
                    <div class="col-md-6 mb-3"  id="drop-area_main">
                        <label for="file-input" class="d-block" id="drop-area">
                            Drag here to upload. Supported formats:</br>
                            png, jpg, jpeg, gif, webp, mmp, avif
                            <br />
                            <div class="text-center">
                                <span class="btn btn-primary">Browse... Multiple Files Support</span>
                            </div>
                        </label>
                        <input type="file" id="file-input" multiple hidden required
                             accept="image/png, image/jpeg, image/gif, image/webp, image/avif, application/pdf, video/mp4">
                    </div>
                    {{-- end Drag and Drop --}}
                </div>

                <div class="row class_for_sticky_top">
                    <div class="col-4">
                        <label for="enable_delete" style="display: inline-flex;gap: 5px; cursor: pointer; user-select: none;"><input type="checkbox" name="enable_delete" value="1" id="enable_delete" class="form-check " id="enable_delete"> Enabled to Delete</label>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="row" id="preview-container"></div>

                {{-- Previous Image --}}
                <div id="image-container" style="">{{-- load by ajax uploaded image --}}</div>
                <br>
               <!-- <a href="javascript:void(0)" class="btn btn-primary" id="upload_btn" onclick="load_image_ajax()">Load More</a> -->
            </div>

        </div>
    </div>
</div>





<script>
    const dropArea = document.getElementById('drop-area_main');
    const fileInput = document.getElementById('file-input');
    var previewContainer = document.querySelector('#preview-container');
    var imageContainer = document.querySelector('#image-container');

    // Utility function to prevent default browser behavior
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Preventing default browser behavior when dragging a file over the container
    dropArea.addEventListener('dragover', preventDefaults);
    dropArea.addEventListener('dragenter', preventDefaults);
    dropArea.addEventListener('dragleave', preventDefaults);

    // Handling dropping files into the area
    dropArea.addEventListener('drop', handleDrop);

    // We’ll discuss `handleDrop` function down the road
    function handleDrop(e) {
        e.preventDefault();

        // Getting the list of dragged files
        const files = e.dataTransfer.files;

        // Checking if there are any files
        if (files.length) {
            // Assigning the files to the hidden input from the first step
            fileInput.files = files;

            // Processing the files for previews (next step)
            //   console.log(files)
            //   handleFiles(files);
            file_upload(files);
            dropArea.classList.remove('drag-over');
        }
    }

    fileInput.addEventListener('change', function(e) {
        e.preventDefault();
        file_upload(this.files)
        // handleFiles(this.files);
    })

    // function generateUniqueFileName(originalName) {
    //     const timestamp = Date.now(); // Current timestamp
    //     const randomString = Math.random().toString(36).substring(2, 8); // Random string
    //     const fileExtension = originalName.substring(originalName.lastIndexOf('.')) || ''; // Extract file extension
    //     const baseName = originalName.substring(0, originalName.lastIndexOf('.')) || originalName; // Extract base name

    //     return `${baseName.replaceAll('/[ \-\/\\&+=%#@.,:;()]/g,', '_')}_${timestamp}_${randomString}${fileExtension}`;
    // }

    function generateUniqueFileName(originalName) {
        const timestamp = Date.now(); // Current timestamp
        const randomString = Math.random().toString(36).substring(2, 8); // Random string
        const fileExtension = originalName.substring(originalName.lastIndexOf('.')) || ''; // Extract file extension
        const baseName = originalName.substring(0, originalName.lastIndexOf('.')) || originalName; // Extract base name

        // Replace spaces, slashes, special chars with underscores
        const sanitizedBaseName = baseName.replace(/[ \-\/\\&+=%#@.,:;()]/g, '_');

        return `${sanitizedBaseName}_${timestamp}_${randomString}${fileExtension}`;
    }






    const CHUNK_SIZE = 0.5 * 1024 * 1024; // 500KB
    const MAX_RETRIES = 3; // Maximum retry attempts
    async function file_upload(files) {

        for (let j = 0; j < files.length; j++) {
            let preview_image = `<div  type="button"  class="border progress_${j}">
                <div class="text-center">
                    <i class="fas fa-spinner fa-pulse"></i>
                </div>
                <div> ${files[j].name}</div>
                <div> Please wait</div>
                <div class="progress">

                     <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"> 0%</div>
                    </div>
                </div>
            </div>`;
            $(previewContainer).prepend(preview_image)
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file) continue;
            const uniqueFileName = generateUniqueFileName(file.name);

            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
            let uploadedChunks = 0;

            for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                const start = chunkIndex * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunk = file.slice(start, end);

                let retryCount = 0;
                let success = false;

                while (retryCount < MAX_RETRIES && !success) {
                    try {
                        await uploadChunk(chunk, uniqueFileName, file.name, chunkIndex, totalChunks, file.size, i);
                        success = true;
                        uploadedChunks++;
                    } catch (error) {
                        retryCount++;
                        console.error(
                            `Error uploading chunk ${chunkIndex + 1}: ${error.message}. Retrying (${retryCount}/${MAX_RETRIES})`
                        );
                    }
                }

                if (!success) {
                    console.error(`Failed to upload chunk ${chunkIndex + 1} after ${MAX_RETRIES} attempts.`);
                    alert(`Upload failed for file: ${file.name}.`);
                    return;
                }

                const progress = Math.floor((uploadedChunks / totalChunks) * 100);
                // console.log(`Progress: ${progress}%`);
                $(`.progress_${i} .progress`).html(
                    `
                     <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ${progress}%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">${progress}%</div>`
                    )
                // Optionally update a progress bar or UI element here
            }



            {{--  alert(`File ${file.name} uploaded successfully!`);  --}}
            {{--  format_image_preview(uniqueFileName)  --}}

        }
    }



    async function uploadChunk(chunk, fileName, originalName, chunkIndex, totalChunks, size, current_key) {
        let converter_enabled = $('input[name="converter_enabled"]').is(':checked') ? 'convert_eanabled' : 'no';
        let quality = $('input[name="quality"]').val();
        let resize_width = $('input[name="resize-width"]').val();
        let resize_height = $('input[name="resize-height"]').val();


        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('fileName', fileName);
        formData.append('originalName', originalName);
        formData.append('chunkIndex', chunkIndex);
        formData.append('totalChunks', totalChunks);
        formData.append('file_size', size);
        formData.append('converter_enabled', converter_enabled);
        formData.append('quality', quality);
        formData.append('resize_width', resize_width);
        formData.append('resize_height', resize_height);

        const response = await fetch('{{ url('uploads/post') }}', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        {{--  when uploaded complete check  --}}
        const data = await response.json();
        console.log(data);
        if (data.complete) {
            preview_image = format_image_preview(data.name, data.id, data.orginal_name)
            $(`.progress_${current_key}`).html(`${preview_image}`)
            $(`.progress_${current_key}`).removeClass(`progress_${current_key}`)
        }
        {{--  end checking  --}}

    }






    dropArea.addEventListener('dragover', () => {
        dropArea.classList.add('drag-over');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('drag-over');
    });



    function format_image_preview(src_image, id, org_namge = '') {

        var src_image = '{{ asset('uploads') }}/' + src_image;

        // Check the file extension to determine if it's a video
        const videoExtensions = ['mp4', 'webm', 'ogg'];

        const extension = src_image.split('.').pop().toLowerCase();

        let return_items = `<div type="button" class="border" onclick="select_image(this)" data-file-id="${id}" data-file-url="${src_image}" style="text-align: center;">`;
        if (videoExtensions.includes(extension)) {


            return_items += `
                        <img hidden data-id="${id}" class="img-fluid h-100 "  style="object-fit:contain;max-height: 115px; max-width: 180px; " src="${src_image}"/>
                            <video   class="img-fluid " style="width:100%;height:100%;object-fit:contain"
                                    style="object-fit:contain;"
                                    src="${src_image}"
                                    preload="metadata">
                                </video>

                    `;
         }else if(extension.includes('pdf')){
              return_items += `
                    <img data-id="${id}" class="img-fluid h-100 " style="object-fit:contain;max-height: 115px;  max-width: 180px;" src="{{ asset('preset/pdf.png') }}"/>
                `;
        } else{
            return_items += `
                    <img data-id="${id}" class="img-fluid h-100 " style="object-fit:contain;max-height: 115px; max-width: 180px;" src="${src_image}"/>
                `;
        }

        return return_items  += `<br/><div style="padding: 3px 5px;
                                    flex-wrap: wrap-reverse;
                                    overflow-wrap: anywhere;">${org_namge}</div>
                                </div>`;

    }

    function isValidFileType(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        return allowedTypes.includes(file.type);
    }








    var current_id_loaded = 9999999999999;
    function load_image_ajax() {
        if(found_false){
            return;
        }else if(loding_image){
            return;
        }else{
            loding_image = true;
        }
        $.ajax({
            type: 'get',
            url: '{{ url('/uploads/get') }}',
            accepts: 'application/json',
            dataType: 'json',
            data: {
                id: current_id_loaded,
            },
            success: function(data) {
                // data = JSON.parse(data);
                if (Object.keys(data.data).length == 0) {
                    $(imageContainer).append('<div>No Found More Data</div>');
                }

                // let found =false;
                Object.keys(data.data).forEach(function(id, key) {
                    let insted_obj = data.data[id];
                    console.log(insted_obj);

                    $(imageContainer).append(format_image_preview(insted_obj.name, insted_obj.id, insted_obj.orginal_name));

                    if (insted_obj.id <= parseInt(current_id_loaded)) {
                        current_id_loaded = insted_obj.id
                    }
                })

                // console.log(loding_image);
                loding_image = false;
                if(data.length == 0){
                    found_false = true;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error uploading files:', textStatus, errorThrown); // Handle errors
                loding_image = false;
            }
        })
    }



</script>




<script>
    var currnet_element_selected = '';
    var current_summernote_selected = null;
    var current_ckeditor_selected = null;
    var current_ckeditor_instance_name = null;
    var p_w_image = '';
    var p_h_image = '';
    var loding_image = false;
    var found_false = false;

    function open_summernote_file_manager(noteElement, width = null, height = null) {
        current_summernote_selected = noteElement || null;
        current_ckeditor_selected = null;
        current_ckeditor_instance_name = null;
        currnet_element_selected = '';
        upload_select(null, width, height);
    }
    window.open_summernote_file_manager = open_summernote_file_manager;

    function open_ckeditor_file_manager(editorInstance, width = null, height = null) {
        current_ckeditor_selected = editorInstance || null;
        current_ckeditor_instance_name = editorInstance && editorInstance.name ? editorInstance.name : null;
        current_summernote_selected = null;
        currnet_element_selected = '';

        if (current_ckeditor_selected) {
            try {
                current_ckeditor_selected.focus();
                var selection = current_ckeditor_selected.getSelection();
                if (selection) {
                    current_ckeditor_selected._fileManagerBookmarks = selection.createBookmarks2(true);
                }
            } catch (e) {
                console.warn('Could not store CKEditor selection.', e);
            }
        }

        upload_select(null, width, height);
    }
    window.open_ckeditor_file_manager = open_ckeditor_file_manager;

    function upload_select(thi, width = null, height = null) {
        $('#upload_ajax_modal').modal('show');
        if (thi) {
            currnet_element_selected = thi;
            current_summernote_selected = null;
            current_ckeditor_selected = null;
            current_ckeditor_instance_name = null;
        }
        p_w_image = width ? width : '';
        p_h_image = height ? height : '';
        $("#upload_ajax_modal").find('input[name="resize-width"]').val(p_w_image);
        $("#upload_ajax_modal").find('input[name="resize-height"]').val(p_h_image);

        let image_container = $('#image-container');
        if(image_container.length > 0){
            if(image_container.html() == ''){
                load_image_ajax();
            }
        }


    }

   // Load more image when scroll
    let file_upload_manager = $('#upload_ajax_modal .modal-body');
    if (file_upload_manager.length > 0) {
        file_upload_manager.on('scroll', function () {
            if(found_false){
                return;
            }
            const scrollTop = $(this).scrollTop();
            const scrollHeight = $(this)[0].scrollHeight;
            const containerHeight = $(this).height();

            if (scrollTop + containerHeight >= scrollHeight * 0.9) {
                load_image_ajax();
            }
        });
    }

    let Loadhandler = setInterval(function() {
        let image_container = $('#image-container');
        let element = file_upload_manager[0]; // jQuery → DOM element
        let hasScrollbar = element.scrollHeight > element.clientHeight;

        if(found_false || hasScrollbar){
            clearInterval(Loadhandler);
        }else{
            if(image_container.html() == ''){
                return;
            }
            // console.log(image_container.html());
            load_image_ajax();
        }
    },700)




//     let file_upload_manager = $('#upload_ajax_modal .modal-body');

//     function checkScrollbar(el) {
//     let element = el[0]; // jQuery → DOM element
//     let hasScrollbar = element.scrollHeight > element.clientHeight;

//     if (!hasScrollbar) {
//         noScrollbarFound();
//     } else {
//         console.log("Scrollbar available");
//     }
//     }

// function noScrollbarFound() {
//   console.log("No scrollbar → do something here...");
// }

// Run on load
// $(window).on('load', function() {
//   checkScrollbar(file_upload_manager);
// });

    // end Load more image when scroll



    function select_image(thi) {

         //getting image data from upload window
        var img_tag_form_upload_window = $(thi).find('img').first();
        var src_img = $(thi).data('file-url') || $(img_tag_form_upload_window).attr('src');
        var img_id = $(thi).data('file-id') || $(img_tag_form_upload_window).data('id');

        let enable_delete = $('#enable_delete');
        if(enable_delete.length > 0){
            if($(enable_delete).is(':checked')){
               if(confirm('Are you sure you want to delete this image?')){
                    $.ajax({
                        type: 'get',
                        url: '{{ url('/uploads/delete') }}',
                        data: {
                            id: img_id,
                        },
                        success: function(data) {
                            // data = JSON.parse(data);
                        if(data.success){
                            $(thi).remove();
                        }else{
                            alert(data.message);
                        }
                        },
                    })
                }
                return;
            }
        }




        //setting img info selecting window
        if (current_summernote_selected) {
            const $note = $(current_summernote_selected);
            const extension = (src_img.split('.').pop() || '').toLowerCase();
            const videoExtensions = ['mp4', 'webm', 'ogg'];

            $note.summernote('restoreRange');

            if (videoExtensions.includes(extension)) {
                $note.summernote('pasteHTML', `<video controls style="max-width:100%;height:auto;" src="${src_img}"></video>`);
            } else if (extension.includes('pdf')) {
                $note.summernote('pasteHTML', `<p><a href="${src_img}" target="_blank" rel="noopener noreferrer">View PDF</a></p>`);
            } else {
                $note.summernote('insertImage', src_img);
            }

            current_summernote_selected = null;
            $('#upload_ajax_modal').modal('hide');
            return;
        }

        function buildCkMediaHtml(sourceUrl) {
            const extension = (sourceUrl.split('.').pop() || '').toLowerCase();
            const videoExtensions = ['mp4', 'webm', 'ogg'];

            if (videoExtensions.includes(extension)) {
                return `<video controls style="max-width:100%;height:auto;" src="${sourceUrl}"></video>`;
            }
            if (extension.includes('pdf')) {
                return `<p><a href="${sourceUrl}" target="_blank" rel="noopener noreferrer">View PDF</a></p>`;
            }
            return `<img src="${sourceUrl}" alt="" style="max-width:100%;height:auto;" />`;
        }

        var ckEditorTarget = null;
        var openedFromCkEditor = !!(current_ckeditor_instance_name || current_ckeditor_selected);
        if (openedFromCkEditor) {
            if (current_ckeditor_instance_name && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances[current_ckeditor_instance_name]) {
                ckEditorTarget = CKEDITOR.instances[current_ckeditor_instance_name];
            } else if (current_ckeditor_selected && typeof current_ckeditor_selected.insertHtml === 'function') {
                ckEditorTarget = current_ckeditor_selected;
            }
        }
        
        if (ckEditorTarget && typeof ckEditorTarget.insertHtml === 'function') {
            var htmlToInsert = buildCkMediaHtml(src_img);
            var oldData = '';

            try {
                ckEditorTarget.focus();
                if (ckEditorTarget._fileManagerBookmarks) {
                    ckEditorTarget.getSelection().selectBookmarks(ckEditorTarget._fileManagerBookmarks);
                }
            } catch (e) {
                console.warn('Could not restore CKEditor selection.', e);
            }

            try {
                oldData = ckEditorTarget.getData();
                ckEditorTarget.insertHtml(htmlToInsert);
                var newData = ckEditorTarget.getData();

                // Fallback: if insertHtml does not change content after modal focus flow, append manually.
                if (oldData === newData) {
                    ckEditorTarget.setData(oldData + htmlToInsert);
                }
            } catch (e) {
                console.warn('insertHtml failed. Falling back to setData append.', e);
                try {
                    oldData = oldData || ckEditorTarget.getData();
                    ckEditorTarget.setData(oldData + htmlToInsert);
                } catch (innerError) {
                    console.error('CKEditor fallback insert failed.', innerError);
                }
            }

            ckEditorTarget._fileManagerBookmarks = null;
            current_ckeditor_selected = null;
            current_ckeditor_instance_name = null;
            $('#upload_ajax_modal').modal('hide');
            return;
        }


        if (!currnet_element_selected) {
            $('#upload_ajax_modal').modal('hide');
            return;
        }

        var selected_eleemtn = $(currnet_element_selected).find('input[type="hidden"], input[type="text"]').first();
        var selected_eleemtn_img = $(currnet_element_selected).find('img').first();


        if(selected_eleemtn_img.length == 0){
            selected_eleemtn_img = $(currnet_element_selected).find('video').first();
        }

        if (selected_eleemtn.length == 0) {
            flasher.error('Could not bind selected file to target input.');
            return;
        }

        var should_store_url = $(currnet_element_selected).data('url_place');
        var has_img_id = (img_id !== undefined && img_id !== null && img_id !== '' && img_id !== 'undefined' && img_id !== 'null');

        if (!should_store_url && !has_img_id) {
            flasher.error('Selected file id not found. Please refresh and select again.');
            return;
        }

        var bind_value = should_store_url ? src_img : img_id;
        $(selected_eleemtn).val(bind_value).trigger('input').trigger('change');

        // check image or video
        var extension = src_img.split('.').pop().toLowerCase();



        var videoExtensions = ['mp4', 'webm', 'ogg'];

        if(videoExtensions.includes(extension)){
            var video_tag = `<video  class="img-fluid " style="width:100%;height:100%;object-fit:contain"
                                style="object-fit:contain;max-height: 60px;"
                                src="${src_img}"
                                preload="metadata">
                            </video>`;
            if (selected_eleemtn_img.length > 0) {
                selected_eleemtn_img.replaceWith(video_tag);
            } else {
                $(currnet_element_selected).append(video_tag);
            }
        }else if(extension.includes('pdf')){
            var image_tag = `<img class="img-fluid h-100 " style="object-fit:contain;max-height: 60px;" src="{{ asset('preset/pdf.png') }}"/>`;
            if (selected_eleemtn_img.length > 0) {
                selected_eleemtn_img.replaceWith(image_tag);
            } else {
                $(currnet_element_selected).append(image_tag);
            }
        }else{
            var image_tag = `<img class="img-fluid h-100 " style="object-fit:contain;max-height: 60px;" src="${src_img}"/>`;
            if (selected_eleemtn_img.length > 0) {
                selected_eleemtn_img.attr('src', src_img);
            } else {
                $(currnet_element_selected).append(image_tag);
            }
            // selected_eleemtn_img.attr('src', src_img);
        }

        if (should_store_url) {
            $(selected_eleemtn).val(src_img).trigger('input').trigger('change');
        }

        // check video

       // console.log(src_img);

        $('#upload_ajax_modal').modal('hide');
    }


     function change_category(thi, target_class, param_key = 'cat_id', items_value_unsets = []) {
        const selectedValue = $(thi).val();
        // Reset target select
        $(target_class)
            .val(0)
            .trigger('change')
            .attr('data-placeholder', 'Select item');

            if (items_value_unsets.length > 0) {
                items_value_unsets.forEach(item => {
                    $(item).val(0).trigger('change').attr('data-placeholder', 'Select item');
                    console.log(item);
                });
            }
            console.log($(target_class, items_value_unsets));

        // Update URL
        let data_url = $(target_class).data('url');
        const url = new URL(data_url, window.location.origin);

        url.searchParams.set(param_key, selectedValue);

        $(target_class).data('url', url.toString());

        select2_caller();
    }
    function fill_if_empty(thi, target_class) {

        const sourceVal = $(thi).val();
        const $target = $(target_class);

        // Fill only if source has value AND target is empty
        if (sourceVal !== '' && $target.val().trim() === '') {
            $target.val(sourceVal).trigger('change');
        }
    }

</script>
<script>
    function remove_element_image(thi) {
        $(thi).parents('.image_items_removeable').remove();
    }

    function add_more_filed_image(width = null, height = null) {
        var items_image = `<div class="image_items_removeable">
                                 <label type="button" class="multiple" onclick="upload_select(this, ${width}, ${height})">
                                    <input type="text" hidden name="uploads_id[]" id="image" class="form-control mb-2"/>
                                    <img style="max-height: 60px" src="{{ dynamic_asset(0) }}" alt=""/>
                                </label>
                                <span  onclick="remove_element_image(this)">x</span>
                            </div>`;
        $('.items_filed_iamge').append(items_image)
    }
</script>


<style>
    #preview-container,
    #image-container {
        display: grid;
        max-width: 1250px;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        /* grid-auto-flow: dense; */
        margin: 0 auto;
        justify-content: center;
        grid-gap: 5px;
    }


    #drop-area {
        width: 100%;
        height: 100%;
        /* margin: 20px auto; */
        padding: 0 15px;
        text-align: center;
        line-height: 43px;
        border: 2px dashed #ccc;
        cursor: pointer;
        display: flex !important;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    #preview-container {
        text-align: center;
    }



    #drop-area.drag-over {
        background-color: #eee;
    }




    .items_container_image .items_filed_iamge {
        display: flex;
        gap: 7px;
    }

    .items_container_image .items_filed_iamge input {
        visibility: hidden;
    }

    .items_container_image .image_items_removeable {
        max-width: 300px;
        position: relative;
    }


    .items_container_image .image_items_removeable span {
        position: absolute;
        top: -5px;
        right: -6px;
        z-index: 9;
        background: red;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px
    }

    .items_container_image .image_items_removeable span:hover {
        opacity: 0.8;
    }

    .items_container_image .items_container_image {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }


    .items_container_image .items_filed_iamge {
        display: flex;
        flex-wrap: wrap;
    }

    .items_container_image button.add_image_filed.btn.btn-primary {
        cursor: pointer;
    }

    #preview-container i.fas.fa-spinner.fa-pulse {
        font-size: 23px;
        margin-top: 10px;
    }

    #preview-container .progress {
        margin: 5px;
    }

    .class_for_sticky_top {
        position: sticky;
        top: -16px;
        background: white;
        padding: 10px 0px 3px 0;
        border-bottom: 1px solid #d5d5d5;
        box-shadow: 0px 1px 0px 0px #d5d5d5;
        margin-bottom: 11px;
    }
</style>
