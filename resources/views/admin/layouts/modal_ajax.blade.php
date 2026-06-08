<!-- Button trigger modal -->
<button type="button" hidden class="btn btn-primary" data-toggle="modal" data-target="#ajax_modal">
    Launch modal data-target="#ajax_modal"
</button>

<!-- Modal -->
<div class="modal fade ajax_modal_dialog" id="ajax_modal" tabindex="-1" role="dialog" aria-labelledby="ajax_modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="eajax_modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>

        </div>
    </div>
</div>




<!-- Button trigger modal -->
<button type="button" hidden class="btn btn-primary" data-toggle="modal" data-target="#ajax_modal_dialog_add">
    Launch modal data-target="#ajax_modal_dialog_add"
</button>

<!-- Modal -->
<div class="modal fade ajax_modal_dialog_add" id="ajax_modal_dialog_add" tabindex="-1" role="dialog"
    aria-labelledby="add_modal_lLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eajax_modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>

        </div>
    </div>
</div>




<!-- Button trigger modal -->
<button type="button" hidden class="btn btn-primary" data-toggle="modal" data-target="#ajax_modal_add_plus">
    Launch modal data-target="#ajax_modal_add_plus"
</button>

<!-- Modal -->
<div class="modal fade ajax_modal_add_plus" id="ajax_modal_add_plus" tabindex="-1" role="dialog"
    aria-labelledby="add_modal_lLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eajax_modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>

        </div>
    </div>
</div>


<!-- Button trigger modal -->
<button type="button" hidden class="btn btn-primary" data-toggle="modal" data-target="#ajax_modal_add_plus_x">
    Launch modal data-target="#ajax_modal_add_plus_x"
</button>

<!-- Modal -->
<div class="modal fade ajax_modal_add_plus_x" id="ajax_modal_add_plus_x" tabindex="-1" role="dialog"
    aria-labelledby="add_modal_lLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eajax_modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>

        </div>
    </div>
</div>




<script>
    var modalDialog = '';
    var setElement = '';
    var model = '';
    var success_func = null;
    var placeholder_body = `
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
        <div class="w-100 p-2 glow_text"></div>
`;


    function button_ajax(thi) {







        if ($(thi).data('setelement')) {
            setElement = $(thi).data('setelement');

        } else {

            if ($(thi).data('target')) {
                model = document.querySelector($(thi).data('target'));
            } else {
                model = document.querySelector('#ajax_modal');
            }


            if ($(thi).data('success')) {
                success_func = $(thi).data('success');
            }



            modalDialog = model.querySelector('.modal-dialog')

            setElement = model.querySelector('.modal-body');

            if ($(thi).data('dialog')) {
                if ($(modalDialog).data('dialog')) {
                    var data_array = modalDialog.getAttribute('data-dialog').split(" ");
                    data_array.forEach(function(element) {
                        $(modalDialog).removeClass(element); // Remove each class from modal-dialog
                    });
                }


                var dialogValue = thi.getAttribute('data-dialog');
                $(modalDialog).addClass($(thi).data('dialog'));
                // Add the data-dialog attribute value from thi to the .modal-dialog element
                modalDialog.setAttribute('data-dialog', dialogValue);
            }

            {{--  Modal Title  --}}
            if ($(thi).data('title')) {
                $(model).find('#eajax_modalLabel').html($(thi).data('title'));
            } else {
                if ($(thi).title) {
                    $(model).find('#eajax_modalLabel').html($(thi).title);
                } else {
                    $(model).find('#eajax_modalLabel').html('data-title or title');
                }
            }
            {{--  end modal Title  --}}


        }
        $(setElement).html(placeholder_body);



        // console.log(model)


        let model_dialog = $(model);
        if ($(thi).data('dialogstatus') === true || $(thi).data('dialogstatus') === undefined) {
            model_dialog.modal('show');
        }





        {{--  data set  --}}
        if ($(thi).data('href')) {

            $.ajax({
                'type': 'get',
                'dataType': 'json',
                'accepts': 'application/json',
                'url': thi.getAttribute('data-href'),
                success: function(data) {

                        if (data.success == false) {
                            flasher.error(data.message);
                        } else if (data.success == true) {
                            if(data.html){
                                $(setElement).html(data.html);
                            }else{
                                flasher.success(data.message);
                                setTimeout(() => {
                                    model_dialog.modal('hide');
                                }, 1500);
                            }
                        }




                    select2_caller();
                    setTimeout(()=>{
                        summernote_render();
                        ckeditor_run_all();
                    },500)
                    form_submit('#' + model.getAttribute('id') + ' form');
                    tooltip_active()

                },
                error: function(xhr, status, error) {

                    //Error show and refresh button generate
                    var items_refresh = "<div class='text-center btn_refresh_head d-flex align-items-center justify-content-center flex-column'>" + thi.outerHTML +
                        "<br/><br/>AJAX Error: " + status + error + "</div>";
                    $(model).find('.modal-body').html(items_refresh);
                    $(model).find('.btn_refresh_head .btn').html('Refresh')
                    //Error show and refresh button generate
                }
            })
        } else {
            $(model).find('.modal-body').html('data-href');
        }






    }

    function isValidJSONObject(obj) {
        try {

            return true;
        } catch (e) {
            return false;
        }
    }

    //Summernote render
    function summernote_render(class_element = null) {
        if (class_element == null) {
            class_element = '.summernote';
        }
        var summernote = document.querySelectorAll(class_element);
        // console.log(summernote);
        if (summernote.length > 0) {
            summernote.forEach(function(single_note) {
                if ($(single_note).data('summernote')) {
                    return;
                }
                let SHheight = 100;
                if($(single_note).data('minheight')){
                    SHheight = $(single_note).data('minheight');
                }
                $(single_note).summernote({
                    placeholder: single_note.placeholder,
                    tabsize: 2,
                    height: 100,
                    minHeight: SHheight,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'video', 'fileManager']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    buttons: {
                        fileManager: function(context) {
                            const ui = $.summernote.ui;
                            return ui.button({
                                contents: '<i class="note-icon-picture"></i> File Manager',
                                tooltip: 'Insert from File Manager',
                                click: function() {
                                    context.invoke('editor.saveRange');
                                    if (typeof window.open_summernote_file_manager === 'function') {
                                        window.open_summernote_file_manager(single_note);
                                    } else {
                                        flasher.error('File manager is not available.');
                                    }
                                }
                            }).render();
                        }
                    }
                });
            });
        }
    }
    // Default All sumernote render
    summernote_render();


    // All Modal Submit
    function error_show(xhr, status, error) {
        var response_error = JSON.parse(xhr.responseText);

        if (response_error.errors) {
            const errors = response_error.errors;
            var i = 0;
            Object.keys(errors).forEach(function(key) {
                i++
                if (i == 1) {
                    $('input[name="' + key + '"]').focus();
                }
                errors[key].forEach(function(errorMessage) {
                    flasher.error(errorMessage);
                });
            });
        } else if (response_error.message) {
            flasher.error(response_error.message);
        } else {
            //Error show and refresh button generate
            var items_refresh = "<div class='text-center btn_refresh_head d-flex align-items-center justify-content-center flex-column'>" + thi.outerHTML +
                "<br/><br/>AJAX Error: " + status + error + "</div>";
            $('#ajax_modal .modal-body').html(items_refresh);
            $('.btn_refresh_head .btn').html('Refresh')
            //Error show and refresh button generate

        }
    }



    // Capture the state before reloading
    function form_submit(class_element = null) {
        var forem_reset = false;
        if (class_element == null) {
            class_element = '.form_ajax_submit'
        }
        document.querySelectorAll(class_element).forEach(function(element) {
            // console.log(element);

            $(element).on('submit', function(e) {
                if($(this).data('success')){
                    success_func = $(this).data('success');
                }
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    type: $(this).attr('method'), // Correct way to get form method
                    url: $(this).attr('action'), // Correct way to get form action URL
                    data: new FormData(this),
                    accecpt: 'json', // Correct constructor for FormData
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(data, status, xhr) {
                        // data = JSON.parse(data);
                        // console.log(data.status); // Handle success
                        if (data.type == 'success') {
                            flasher.success(data.title);
                            $(model).modal('hide');

                        } else if (data.status && data.status == 400) {

                            // its for steedfast specily
                            error_show(xhr, data.status, 'Bad Request');
                        } else {
                            flasher.error(data.title);
                        }



                        if (forem_reset == true) {
                            element.reset();
                        } else if (data.refresh &&( data.refresh == 'true' || data.refresh == true)) {
                            if (typeof datatableM === 'undefined') {
                                console.log('Data table refresh not found');
                            } else {

                                datatableM.ajax.reload();

                            }
                        } else {
                            if (data.page && data.page == 'true') {
                                window.location.href = '';
                            } else {
                                console.log('not changed');
                            }
                        }


                        if (success_func) {
                            eval(success_func);
                        }






                    },
                    error: function(xhr, status, error) {
                        error_show(xhr, status, error)
                        if (success_func) {
                            eval(success_func);
                        }

                    }
                });
            });

        });

    }

    //Default Call for mapping
    form_submit();


    //when declare class select2 convert select2 format
    function select2_caller() {
        document.querySelectorAll('.select2').forEach(function(element) {
            if ($(element).data('ajax')) {
                if ($(element).data('model')) {
                    ajax_data_request(element, true)
                } else {
                    ajax_data_request(element)
                }

            } else {
                if ($(element).data('model')) {
                    let model = $(element).closest('.modal')
                    model = model[0].id;
                    model = '#' + model;
                    console.log(model);
                    $(element).select2({
                        dropdownParent: model,

                    });
                    console.log(2342)
                } else {
                    let tagable = false;
                    if ($(element).data('tagble')) {
                        tagable = $(element).data('tagble');
                    }
                    $(element).select2({
                        tags: tagable,

                    });
                }

            }
        });
    }

    //Default Calling option Select2
    select2_caller();



    //select2 ajax request
    function ajax_data_request(thi, model = false) {
        if (model == true) {
            model = $(thi).closest('.modal')
            model = model[0].id;
            model = '#' + model;
        }



        $(thi).select2({
            dropdownParent: model,
            placeholder: $(thi).placeholder,
            ajax: {
                url: $(thi).data('url'), // Replace with your API endpoint
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // Search term
                    };
                },
                processResults: function(data, params) {
                    // Parse the results into the format expected by Select2
                    return {
                        results: data.items, // The array of items
                    };
                },
                cache: true
            },
            minimumInputLength: 0 // Minimum length of input to trigger search
        });


    }



    function tooltip_active() {
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    tooltip_active()

    function copyElement(button) {

        const text = button.getAttribute('data-href');

        // Copy the text to the clipboard
        navigator.clipboard.writeText(text).then(() => {
            alert('Text copied to clipboard!');
        }).catch(err => {
            console.error('Error copying text: ', err);
        });
    }

    // ========================================= Copy to Clipboard Common Function =========================================
function copyToClipboard(text) {
    if (navigator.clipboard) {
        // Modern approach using Clipboard API
        navigator.clipboard.writeText(text).then(() => {
            console.log('Text copied to clipboard');
            flasher.success('Text copied to clipboard <br/>' + text);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            flasher.error('Failed to copy text: ' + err);
        });
    } else {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, textarea.value.length); // For mobile compatibility

        try {
            document.execCommand('copy');
            console.log('Text copied to clipboard');
            flasher.success('Text copied to clipboard <br/>' + text);
        } catch (err) {
            console.error('Failed to copy text: ', err);
            flasher.error('Failed to copy text: ' + err);
        }

        document.body.removeChild(textarea); // Cleanup
    }
}
//  Copy to Clipboard ============================================================================================









</script>


@include('admin.layouts.file_manager_modal')
