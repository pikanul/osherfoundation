<form action="{{ $subscriber ? route('admin.subscribers.update', $subscriber->id) : route('admin.subscribers.store') }}"
      method="POST" class="form_ajax_submit">
    @csrf
    @if($subscriber)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-1">
            <div class="form-group">
                <label for="subscriber_name">Name</label>
                <input type="text" id="subscriber_name" name="name" class="form-control"
                       value="{{ $subscriber?->name ?? '' }}" placeholder="Enter name">
            </div>
        </div>

        <div class="col-md-6 mb-1">
            <div class="form-group">
                <label for="subscriber_phone">Phone</label>
                <input type="text" id="subscriber_phone" name="phone" class="form-control"
                       value="{{ $subscriber?->phone ?? '' }}" placeholder="Enter phone">
            </div>
        </div>

        <div class="col-md-8 mb-1">
            <div class="form-group">
                <label for="subscriber_email">Email <span class="text-danger">*</span></label>
                <input type="email" id="subscriber_email" name="email" class="form-control"
                       value="{{ $subscriber?->email ?? '' }}" placeholder="Enter email" required>
            </div>
        </div>

        <div class="col-md-4 mb-1">
            <div class="form-group">
                <label for="subscriber_status">Status <span class="text-danger">*</span></label>
                <select id="subscriber_status" name="status" class="form-control" required>
                    <option value="1" {{ (int)($subscriber?->status ?? 1) === 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (int)($subscriber?->status ?? 1) === 0 ? 'selected' : '' }}>Inactive</option>
                    <option value="2" {{ (int)($subscriber?->status ?? 1) === 2 ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">
            {{ $subscriber ? 'Update' : 'Create' }}
        </button>
    </div>
</form>

