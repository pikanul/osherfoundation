<div>
    <div class="btn-group">
        @foreach ($actions as $actionName => $routeName)
            @if ($actionName == 'active' && $status == 'active')
                <div class="mr-1">
                    <form action="{{ $routeName }}" method="POST">
                        @csrf
                        @method('put')
                        <button class="btn btn-danger btn-sm" type="submit" title="Inactive Now">
                            {{ bladeIcon('inactive') }}
                        </button>
                    </form>
                </div>
            @endif

            @if ($actionName == 'inactive' && $status == 'inactive')
                <div class="mr-1">
                    <form action="{{ $routeName }}" method="POST">
                        @csrf
                        @method('put')
                        <button class="btn btn-success btn-sm" type="submit" title="Active Now">
                            {{ bladeIcon('active') }}
                        </button>
                    </form>
                </div>
            @endif
            @if ($actionName == 'pending' && $status == 'pending')
                <div class="mr-1">
                    <form action="{{ $routeName }}" method="POST">
                        @csrf
                        @method('put')
                        <button class="btn btn-success btn-sm" type="submit" title="Approve Now">
                            {{bladeIcon('approve')}}
                        </button>
                    </form>
                </div>
            @endif
            @if ($actionName === 'show')
                <div class="mr-1">
                    <a href="{{ $routeName }}" class="btn btn-sm btn-info" title="Show more">
                        {{bladeIcon('show')}}
                    </a>
                </div>
            @endif
            @if ($actionName == 'edit')
                <div class="mr-1">
                    <a href="{{ $routeName }}" class="btn btn-sm btn-primary" title="Edit it">
                        {{bladeIcon('edit')}}
                    </a>
                </div>
            @endif
            @if ($actionName == 'print')
                <div class="mr-1">
                    <a href="{{ $routeName }}" class="btn btn-sm btn-primary" title="Print Here">
                        {{bladeIcon('print')}}
                    </a>
                </div>
            @endif
            @if ($actionName == 'barcode')
                <div class="mr-1">
                    <a href="{{ $routeName }}" class="btn btn-sm btn-secondary" title="Barcode Here">
                        {{bladeIcon('barcode')}}
                    </a>
                </div>
            @endif
            @if ($actionName == 'delete')
                <div>
                    <form action="{{ $routeName }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm confirm-text" type="submit" title="Delete Now">
                            {{bladeIcon('delete')}}
                        </button>
                    </form>
                </div>
            @endif
        @endforeach

        @if ($actionName == 'delivered' && $status == 'transit')
            <div class="mr-1">
                <form action="{{ $routeName }}" method="POST">
                    @csrf
                    @method('put')
                    <button class="btn btn-success btn-sm" type="submit" title="Delivered Now">
                        {{bladeIcon('deliver')}}
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

