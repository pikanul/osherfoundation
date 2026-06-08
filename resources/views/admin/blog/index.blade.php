@extends('admin.layouts.app')

@section('title', 'Blog List')

@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'Blog' => route('admin.blogs.index'),
                'Blog list' => ''
            ];
        @endphp
        <x-bread-crumb-component title='Blog list' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__('All Blog List')}}</h4>
                            </div>
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">

                                        <button type="button" id="bulkBlogNewsletterPreviewBtn" class="btn btn-info ml-1" disabled>
                                            Preview Selected Newsletter
                                        </button>
                                        <button type="button" id="bulkBlogNewsletterBtn" class="btn btn-warning ml-1" disabled>
                                            Send Selected to Newsletter
                                        </button>
                                         &nbsp;
                                         {!! button_g(['create' => route('admin.blogs.create')], 'Blog', true, 'blogs') !!}
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table id="dataTable"
                                class="datatables-basic table table-bordered table-secondary table-striped">
                                {{-- show from datatable--}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Responsive tables end -->
        </div>
    </div>
@endsection
@push('script')

    <script>
          const selectedBlogIds = new Set();

          function updateBulkBlogNewsletterButtonState() {
                const count = selectedBlogIds.size;
                const $sendBtn = $('#bulkBlogNewsletterBtn');
                const $previewBtn = $('#bulkBlogNewsletterPreviewBtn');
                $previewBtn.prop('disabled', count === 0);
                $previewBtn.text(count > 0 ? `Preview Selected Newsletter (${count})` : 'Preview Selected Newsletter');
                $sendBtn.prop('disabled', count === 0);
                $sendBtn.text(count > 0 ? `Send Selected to Newsletter (${count})` : 'Send Selected to Newsletter');
          }

          function syncSelectAllBlogCheckbox() {
                const rowCheckboxes = $('.blog-row-checkbox');
                const checkedCount = rowCheckboxes.filter(':checked').length;
                const totalCount = rowCheckboxes.length;
                const selectAll = $('#selectAllBlogRows').get(0);

                if (!selectAll) {
                    return;
                }

                selectAll.checked = totalCount > 0 && checkedCount === totalCount;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
          }

          let datatableM =  $('#dataTable').DataTable({
                stateSave: true,
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('admin.blogs.index') }}",
                },
                columns: [{
                    data: "id",
                    title: '<input type="checkbox" id="selectAllBlogRows">',
                    searchable: false,
                    orderable: false,
                    width: '40px',
                    className: 'text-center',
                    render(data) {
                        const checked = selectedBlogIds.has(String(data)) ? 'checked' : '';
                        return `<input type="checkbox" class="blog-row-checkbox" value="${data}" ${checked}>`;
                    }
                },
                {
                    data: "DT_RowIndex",
                    title: "SL",
                    name: "DT_RowIndex",
                    searchable: false,
                    orderable: false
                },
                {
                    data: "image",
                    title: "image",
                    searchable: true
                },
                {
                    data: "title",
                    title: "Title",
                    searchable: true
                },
                {
                    data: "cat_name",
                    name: "blog_categories.name",
                    title: "Category",
                    searchable: true
                },

                {
                    data: "publish_date",
                    title: "Publish Date",
                    searchable: true
                },
                {
                    data: "status",
                    title: "Status",
                    searchable: true,

                },
                {
                    data: "created_at",
                    title: "Created at",
                    searchable: true
                },
                {
                    data: "action",
                    title: "Action",
                    orderable: false,
                    searchable: false
                },
                ],
                buttons: true,
                dom: "<'row'<'col-lg-3 text-center text-lg-left mb-2'l><'col-lg-5 text-center mb-2'B><'col-lg-4 text-center text-lg-right mb-2'f>><'row'<'col-sm-12 overflow-auto'tr>><'row'<'col-sm-6'i><'col-sm-6 text-center text-md-right d-md-flex justify-content-md-end'p>>",
                drawCallback() {
                    syncSelectAllBlogCheckbox();
                    updateBulkBlogNewsletterButtonState();
                }
            });

          $(document).on('change', '.blog-row-checkbox', function () {
              const id = String($(this).val());
              if ($(this).is(':checked')) {
                  selectedBlogIds.add(id);
              } else {
                  selectedBlogIds.delete(id);
              }
              syncSelectAllBlogCheckbox();
              updateBulkBlogNewsletterButtonState();
          });

          $(document).on('change', '#selectAllBlogRows', function () {
              const checked = $(this).is(':checked');
              $('.blog-row-checkbox').each(function () {
                  const id = String($(this).val());
                  this.checked = checked;
                  if (checked) {
                      selectedBlogIds.add(id);
                  } else {
                      selectedBlogIds.delete(id);
                  }
              });
              syncSelectAllBlogCheckbox();
              updateBulkBlogNewsletterButtonState();
          });

          $('#bulkBlogNewsletterPreviewBtn').on('click', function () {
              if (selectedBlogIds.size === 0) {
                  flasher.error('Please select at least one blog item.');
                  return;
              }

              const ids = Array.from(selectedBlogIds);
              const previewBase = "{{ route('admin.blogs.newsletter_bulk_preview') }}";
              const previewUrl = `${previewBase}?blog_ids=${encodeURIComponent(ids.join(','))}`;
              window.open(previewUrl, '_blank');
          });

          $('#bulkBlogNewsletterBtn').on('click', function () {
              if (selectedBlogIds.size === 0) {
                  flasher.error('Please select at least one blog item.');
                  return;
              }

              const ids = Array.from(selectedBlogIds);
              Swal.fire({
                  title: 'Queue Blog Newsletter Send?',
                  text: `Selected ${ids.length} blog item(s) will be sent as one combined newsletter.`,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes, queue now',
                  customClass: {
                      confirmButton: 'btn btn-primary',
                      cancelButton: 'btn btn-outline-danger ml-1'
                  },
                  buttonsStyling: false
              }).then(function (result) {
                  if (!result.value) {
                      return;
                  }

                  $.ajax({
                      url: "{{ route('admin.blogs.newsletter_bulk') }}",
                      type: 'POST',
                      dataType: 'json',
                      data: {
                          _token: "{{ csrf_token() }}",
                          blog_ids: ids
                      },
                      success: function (data) {
                          if (data.type === 'success') {
                              flasher.success(data.title);
                              selectedBlogIds.clear();
                              updateBulkBlogNewsletterButtonState();
                              datatableM.ajax.reload();
                              return;
                          }
                          flasher.error(data.title || 'Failed to queue selected blog newsletter.');
                      },
                      error: function () {
                          flasher.error('Failed to queue selected blog newsletter.');
                      }
                  });
              });
          });

    </script>
@endpush
