@extends('layouts.auth.admin')

@section('content')
<div class="login-box">

    <form class="login-form" method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
        <div class="form-group">
            <label class="control-label">Email</label>
            <input id="email" type="email" class="form-control " name="email" value="" required="" autocomplete="email" autofocus="">

                            </div>
    
        <div class="form-group">
            <div class="utility">
                <div class="animated-checkbox">
                    <label>
                       <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <span class="label-text">Stay Signed in</span>
                    </label>
                </div>
                <p class="semibold-text mb-2"><a href="{{ route('admin.password.request') }}">Forgot Password ?</a></p>
            </div>
        </div>
        <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
        </div>
    </form>
</div>
@endsection
