@extends('admin.layouts.app')
@php
$ntitle = $category ? $category->name : 'News & Event list';
$nlink = $category ? route('admin.news.newses.index', ['news_category_id' => $category->id]) :
route('admin.news.newses.index');
$ncreateLink = $category ? route('admin.news.newses.create')."?news_category_id=$category->id" : route('admin.news.newses.create');
$links = [
'Home' => route('admin.dashboard'),
$ntitle => $nlink,

];

@endphp
@section('title', $ntitle)

@section('content')

<div class="content-wrapper">

    <x-bread-crumb-component :title='$ntitle' :links="$links" />
    <div class="content-body">
        <div class="row" id="table-responsive">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <div class="head-label">
                            <h4 class="mb-0"> {{ $ntitle }}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">

                                <button type="button" id="bulkNewsletterPreviewBtn" class="btn btn-info ml-1" disabled>
                                    Preview Selected Newsletter
                                </button>
                                <button type="button" id="bulkNewsletterBtn" class="btn btn-warning ml-1" disabled>
                                    Send Selected to Newsletter
                                </button>
                                &nbsp;
                                {!! button_g(['create' => $ncreateLink], $ntitle, true,
                                'news.newses') !!}
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

@push('script')


<script>
const selectedNewsIds = new Set();

function updateBulkNewsletterButtonState() {
    const count = selectedNewsIds.size;
    const $btn = $('#bulkNewsletterBtn');
    const $previewBtn = $('#bulkNewsletterPreviewBtn');
    $previewBtn.prop('disabled', count === 0);
    $previewBtn.text(count > 0 ? `Preview Selected Newsletter (${count})` : 'Preview Selected Newsletter');
    $btn.prop('disabled', count === 0);
    $btn.text(count > 0 ? `Send Selected to Newsletter (${count})` : 'Send Selected to Newsletter');
}

function syncSelectAllCheckbox() {
    const rowCheckboxes = $('.news-row-checkbox');
    const checkedCount = rowCheckboxes.filter(':checked').length;
    const totalCount = rowCheckboxes.length;
    const selectAll = $('#selectAllNewsRows').get(0);

    if (!selectAll) {
        return;
    }

    selectAll.checked = totalCount > 0 && checkedCount === totalCount;
    selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
}

let datatableM = $('#dataTable').DataTable({
    stateSave: true,
    responsive: true,
    serverSide: true,
    processing: true,
    ajax: {
        url: "{{ route('admin.news.newses.index') }}?{{ http_build_query(request()->all()) }}",
    },
    columns: [{
            data: 'id',
            title: '<input type="checkbox" id="selectAllNewsRows">',
            name: "id",
            searchable: false,
            orderable: false,
            width: '40px',
            className: 'text-center',
            render(data) {
                const checked = selectedNewsIds.has(String(data)) ? 'checked' : '';
                return `<input type="checkbox" class="news-row-checkbox" value="${data}" ${checked}>`;
            }
        },
        {
            data: 'si',
            title: "si",
            name: "si",
            searchable: false,
            orderable: false,
            render(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: "image",
            title: "Image",
            searchable: false,
            orderable: false,
        },
        {
            data: "title",
            title: "title",
            searchable: true
        },
        {
            data: "publish_date",
            title: "publish date",
            searchable: true
        },

        {
            data: "category_name",
            name: "news_categories.name",
            title: "news category",
            searchable: true,
            "defaultContent": "No Set"

        },

        {
            data: "created_at",
            title: "created at",
            searchable: true,
            render(data, type, row, meta) {
                return moment(data).format('DD-MM-YYYY');
            }
        },
        {
            data: "action",
            title: "Action",
            orderable: false,
            searchable: false
        },
    ],
    drawCallback() {
        syncSelectAllCheckbox();
        updateBulkNewsletterButtonState();
    }
});

$(document).on('change', '.news-row-checkbox', function () {
    const id = String($(this).val());
    if ($(this).is(':checked')) {
        selectedNewsIds.add(id);
    } else {
        selectedNewsIds.delete(id);
    }
    syncSelectAllCheckbox();
    updateBulkNewsletterButtonState();
});

$(document).on('change', '#selectAllNewsRows', function () {
    const checked = $(this).is(':checked');
    $('.news-row-checkbox').each(function () {
        const id = String($(this).val());
        this.checked = checked;
        if (checked) {
            selectedNewsIds.add(id);
        } else {
            selectedNewsIds.delete(id);
        }
    });
    syncSelectAllCheckbox();
    updateBulkNewsletterButtonState();
});

$('#bulkNewsletterBtn').on('click', function () {
    if (selectedNewsIds.size === 0) {
        flasher.error('Please select at least one news item.');
        return;
    }

    const ids = Array.from(selectedNewsIds);

    Swal.fire({
        title: 'Queue Newsletter Send?',
        text: `Selected ${ids.length} news item(s) will be queued for subscribers.`,
        html: `
            <p class="mb-1">Selected ${ids.length} news item(s) will be queued for subscribers.</p>
            <input id="newsletterTitleInput" class="swal2-input" placeholder="Email title" maxlength="255">
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, queue now',
        focusConfirm: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1'
        },
        buttonsStyling: false,
        preConfirm: function () {
            const title = ($('#newsletterTitleInput').val() || '').trim();
            if (!title) {
                Swal.showValidationMessage('Please enter a newsletter title.');
                return false;
            }
            return {
                title: title
            };
        }
    }).then(function (result) {
        if (!result.value) {
            return;
        }

        const newsletterTitle = result.value.title;

        $.ajax({
            url: "{{ route('admin.news.newses.newsletter_bulk') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                news_ids: ids,
                newsletter_title: newsletterTitle
            },
            success: function (data) {
                if (data.type === 'success') {
                    flasher.success(data.title);
                    selectedNewsIds.clear();
                    updateBulkNewsletterButtonState();
                    datatableM.ajax.reload();
                    return;
                }

                flasher.error(data.title || 'Failed to queue newsletter updates.');
            },
            error: function () {
                flasher.error('Failed to queue selected news for newsletter.');
            }
        });
    });
});

$('#bulkNewsletterPreviewBtn').on('click', function () {
    if (selectedNewsIds.size === 0) {
        flasher.error('Please select at least one news item.');
        return;
    }

    const ids = Array.from(selectedNewsIds);
    const previewBase = "{{ route('admin.news.newses.newsletter_bulk_preview') }}";
    const previewUrl = `${previewBase}?news_ids=${encodeURIComponent(ids.join(','))}`;
    window.open(previewUrl, '_blank');
});

</script>
@endpush
@endsection
