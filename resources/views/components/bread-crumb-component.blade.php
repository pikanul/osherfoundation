{{--<div>--}}
{{-- <div class="app-title">--}}
{{-- <div>--}}
{{-- <h1>{{ $title }}</h1>--}}
{{-- </div>--}}
{{-- <ul class="app-breadcrumb breadcrumb">--}}
{{-- <li class="breadcrumb-item"><a href="{{ route('student.index') }}"><i class="fa fa-home fa-lg"></i></a></li>--}}
{{-- @foreach ($links as $linkName=>$route)--}}
{{-- @if($route === '')--}}
{{-- <li class="breadcrumb-item active">{{ $linkName }}</li>--}}
{{-- @else--}}
{{-- <li class="breadcrumb-item active"><a href="{{ $route }}"></a></li>--}}
{{-- @endif--}}

{{-- @endforeach--}}

{{-- </ul>--}}
{{-- </div>--}}
{{--</div>--}}


<div>

</div>





<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{__($title)}}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        @foreach($links as $linkName=>$route)
                        @if($route== null)
                        <li class="breadcrumb-item">
                            {{ $linkName }}
                        </li>
                        @else
                        <li class="breadcrumb-item">
                            <a href="{{ $route }}">{{ $linkName }}</a>
                        </li>
                        @endif
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">--}}
    {{-- <div class="form-group breadcrumb-right">--}}
    {{-- <div class="dropdown">--}}
    {{-- <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-float waves-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid">--}}
    {{-- <rect x="3" y="3" width="7" height="7"></rect>--}}
    {{-- <rect x="14" y="3" width="7" height="7"></rect>--}}
    {{-- <rect x="14" y="14" width="7" height="7"></rect>--}}
    {{-- <rect x="3" y="14" width="7" height="7"></rect>--}}
    {{-- </svg></button>--}}
    {{-- <div class="dropdown-menu dropdown-menu-right">--}}
    {{-- <a class="dropdown-item" href="{{route('area.create')}}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square mr-1">--}}
        {{-- <polyline points="9 11 12 14 22 4"></polyline>--}}
        {{-- <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>--}}
        {{-- </svg><span class="align-middle">Add Area</span></a>--}}



        {{-- </div>--}}
        {{-- </div>--}}
        {{-- </div>--}}
        {{-- </div>--}}
</div>