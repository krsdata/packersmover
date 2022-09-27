@extends('layouts.app')

@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
            <div class="brand-logo">
                @if (session('status'))
                    <p class="alert alert-success">{{ session('status') }}</p>
                @endif
            </div>
            <h4>{{ __('Reset Password') }}</h4>
            <h6 class="font-weight-light"></h6>
            <form method="POST" action="{{ route('password.request') }}"  class="pt-3">
             @csrf
             <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }}</label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-account-outline text-primary"></i>
                    </span>
                </div>
                <input id="email" type="email" class="form-control-lg border-left-0 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                </div>
            </div>
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-lock-outline text-primary"></i>
                    </span>
                </div>
                <input id="password" type="password" class="form-control-lg border-left-0 form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="new-password">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif             
                </div>
            </div>
            <div class="form-group">
                <label for="password">{{ __('Confirm Password') }}</label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="mdi mdi-lock-outline text-primary"></i>
                    </span>
                </div>
                <input id="password-confirm" type="password" class="form-control-lg border-left-0 form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">  {{ __('Reset Password') }}</button>
            </div>
            
            <div class="text-center mt-4 font-weight-light">
                Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>
            </form>
        </div>
        </div>
        <div class="col-lg-6 login-half-bg d-flex flex-row">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2019  All rights reserved.</p>
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->

@endsection
