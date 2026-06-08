<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/bootstrap46.min.js') }}"></script>
<script>
    // Prevent jQuery UI tooltip from conflicting with Bootstrap/Summernote tooltip.
    if (window.jQuery && $.ui && $.ui.tooltip && $.widget) {
        $.widget.bridge('uitooltip', $.ui.tooltip);
    }
</script>
<script src="{{ asset('vendor/select/select2.full.min.js') }}"></script>



<script src="{{ asset('vendor/pickadate/picker.js') }}"></script>
<script src="{{ asset('vendor/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('vendor/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('vendor/pickadate/legacy.js') }}"></script>

<!-- daterangepicker -->
<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css')}}">

<script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>



<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('vendor/jszip/jszip.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-buttons/js/vfs_fonts.js') }}"></script>



<script src="{{ asset('vendor/toastr.min.js') }}"></script>
<script src="{{asset('vendor/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('vendor/summernote/summernote.js') }}"></script>
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

<script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>



<script src="{{ asset('assets/admin/admin.js') }}"></script>
<!-- flasher -->



<script type="text/javascript">
    @if(session('success'))
        toastr.success('{{ session('success') }}', 'Success', {
            closeButton: true,
            progressBar: true,
        });
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}', 'Error', {
            closeButton: true,
            progressBar: true,
        });
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error('{{$error}}', 'Error', {
                closeButton: true,
                progressBar: true,
            });
        @endforeach
    @endif
</script>





<script>

    function ckeditor_run_all() {
        if (typeof CKEDITOR !== 'undefined' && !CKEDITOR.plugins.get('filemanager')) {
            CKEDITOR.plugins.add('filemanager', {
                init: function (editor) {
                    editor.addCommand('filemanagerCommand', {
                        exec: function (currentEditor) {
                            if (typeof window.open_ckeditor_file_manager === 'function') {
                                window.open_ckeditor_file_manager(currentEditor);
                            } else {
                                console.error('File manager is not available.');
                            }
                        }
                    });

                    editor.ui.addButton('filemanager', {
                        label: 'File Manager',
                        command: 'filemanagerCommand',
                        toolbar: 'insert,10',
                        icon: 'image'
                    });
                }
            });
        }

        var editors = document.querySelectorAll(".ckeditor");
        editors.forEach(function (el) {
            if (el.dataset.ckeditorInitialized === '1') {
                return;
            }

            if (typeof CKEDITOR === 'undefined') {
                console.warn('CKEditor script is not loaded.');
                return;
            }

            if (!el.id) {
                el.id = 'ckeditor_' + Math.random().toString(36).slice(2, 10);
            }

            var editor = CKEDITOR.replace(el, {
                extraPlugins: 'filemanager',
                toolbar: [
                    { name: 'styles', items: ['Format'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['filemanager'] },
                    { name: 'document', items: ['Source'] }
                ],
                height: 260
            });

            el.dataset.ckeditorInitialized = '1';
        });
    }


    $(window).on('load', function () {
        ckeditor_run_all();
    })

    function generateSlugMake(thi, target) {
        var text = thi.value;

        // Remove unwanted punctuations (but keep Bangla letters, numbers, spaces)
        const punctuations = /[‘’“”"'`~!@#$%^&*()+={}\[\]|\\:;<>?,.\/।]/g;

        let output = text
            .replace(punctuations, '')            // Remove punctuations
            .replace(/\s+/g, '-')                 // Replace spaces with hyphens
            .replace(/-+/g, '-')                  // Merge multiple hyphens
            .replace(/[^\p{L}\p{M}\p{N}-]+/gu, '')// Keep letters (\p{L}), marks (\p{M}), numbers (\p{N}), hyphen
            .replace(/^-+|-+$/g, '')              // Trim hyphens from start/end
            .toLowerCase();                        // Optional lowercase English letters


        document.querySelector(target).value = output;
    }


    function confirmAlert(element, message = "You won't be able to revert this!", buttonText = 'Yes, delete it!', title = 'Are you sure?') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: buttonText,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                console.log('confirmed');
                $.ajax({
                    url: $(element).data('href'),
                    type: 'POST',
                    accepts: 'application/json',
                    dataType: 'json',
                    data: {
                        '_token': $(element).data('csrf'),
                        '_method': 'DELETE'
                    },

                    success: function (response) {
                        data = (response);
                        // console.log(data);
                        if (data.type == 'success') {
                            flasher.success(data.title);
                            if (data.refresh == 'true') {
                                if (typeof datatableM === 'undefined') {
                                    console.log('Data table refresh not found');
                                } else {
                                    // console.log(datatableM)

                                    datatableM.ajax.reload();
                                    console.log('Data table refreshed');
                                }
                            } else {
                                flasher.error(data.title);
                            }
                        } else if (data.type == false) {
                            flasher.error(data.title);
                        }
                    }

                })
            }
        });

    }

     var buttons_datatable = {

        dom: "<'row'<'col-xl-3 text-center text-xl-left mb-2'l><'col-xl-5 text-center mb-2'B><'col-xl-4 text-center text-xl-right mb-2'f>>" +
            "<'row'<'col-sm-12 overflow-auto'tr>>" +
            "<'row'<'col-xl-6 text-center text-xl-left'i><'col-xl-6 text-center text-xl-right d-xl-flex justify-content-xl-end'p>>",

         buttons: [

        {
            extend: 'copyHtml5',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        },
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        },
        {
            extend: 'csvHtml5',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        },
        {
            extend: 'pdfHtml5',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            },
             customize: function (doc) {
                 // Define the custom layout with borders
                var objLayout = {};
                // Set horizontal line width to 0.8pt
                objLayout['hLineWidth'] = function(i) { return .8; };
                // Set vertical line width to 0.5pt
                objLayout['vLineWidth'] = function(i) { return .5; };
                // Set horizontal line color to a light gray
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                // Set vertical line color to a light gray
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                // Optional: Add padding
                objLayout['paddingLeft'] = function(i) { return 8; };
                objLayout['paddingRight'] = function(i) { return 8; };

                doc.content.forEach(function(item) {
                    if (item.table) {
                        item.layout = objLayout;
                    }
                });
                   doc.defaultStyle.alignment = 'center'; //
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

            }
        },
        {
            extend: 'print',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        },
       
        {
            extend: 'colvis',
            text: 'Columns'
        }


    ]
    }





//  Datatable and ajax filter
let url_data = '';
let reRenderUpdateTimer = null;
const reRenderUpdateDebounceMs = 700;

function reRenderUpdateRun(data) {
    const $filterForm = $('.filter_form_for_datatable');
    if (!$filterForm.length) {
        return true;
    }

    if (data == 'filter') {
        url_data = '?' + $filterForm.serialize();
    } else if (data == 'reset') {
        $filterForm[0].reset();
        $filterForm.find('.select2').val(null).trigger('change')
        url_data = '?' + $filterForm.serialize();
    } else if (data == 'init' || typeof data === 'undefined') {
        url_data = '?' + $filterForm.serialize();
        return true;
    }

    if (typeof datatableM === 'undefined') {
        return true;
    }
    datatableM.ajax.url(url_data).load();
}

function reRenderUpdate(data) {
    if (data == 'filter') {
        clearTimeout(reRenderUpdateTimer);
        reRenderUpdateTimer = setTimeout(function () {
            reRenderUpdateRun(data);
        }, reRenderUpdateDebounceMs);
        return true;
    }

    return reRenderUpdateRun(data);
}




// Date range picker
if ($('#reportrange').length > 0) {
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);

    cb(start, end);
}
reRenderUpdate();

</script>
@stack('script')
