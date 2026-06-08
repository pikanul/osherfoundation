<div class="row">
    <div class="col-12 mb-1">
        <h5 class="mb-0">Subscriber Preview</h5>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Name</label>
        <div class="font-weight-bold">{{ $subscriber->name ?: '-' }}</div>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Email</label>
        <div class="font-weight-bold">{{ $subscriber->email ?: '-' }}</div>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Phone</label>
        <div class="font-weight-bold">{{ $subscriber->phone ?: '-' }}</div>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Status</label>
        <div class="font-weight-bold">
            @if((int) $subscriber->status === 1)
                Active
            @elseif((int) $subscriber->status === 2)
                Unsubscribed
            @else
                Inactive
            @endif
        </div>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Subscribed At</label>
        <div class="font-weight-bold">{{ $subscriber->subscribed_at ? \Carbon\Carbon::parse($subscriber->subscribed_at)->format('d-m-Y h:i:s A') : '-' }}</div>
    </div>

    <div class="col-md-6 col-12 mb-1">
        <label class="text-muted mb-25">Created At</label>
        <div class="font-weight-bold">{{ $subscriber->created_at ? $subscriber->created_at->format('d-m-Y h:i:s A') : '-' }}</div>
    </div>
</div>
