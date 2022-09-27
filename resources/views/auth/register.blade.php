@extends('layouts.app')

@section('content')

<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left clear-fix p-3" style="width:100%">
            <div class="brand-logo">
            <!-- <img src="" alt="logo"> -->
            </div>
            <h4>New here?</h4>
            <h6 class="font-weight-light">Join us today! It takes only few steps</h6>
            <form method="POST" action="{{ route('register') }}" class="pt-3">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name">{{ __('First Name') }}</label>
                        <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-account-outline text-primary"></i>
                            </span>
                        </div>
                        <input id="name" type="text" class="form-control-lg border-left-0 form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="last_name">{{ __('Last Name') }}</label>
                        <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-account-outline text-primary"></i>
                            </span>
                        </div>
                        <input id="last_name" type="text" class="form-control-lg border-left-0 form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
        
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }}</label>
                        <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-email-outline text-primary"></i>
                            </span>
                        </div>
                        <input id="email" type="email" class="form-control-lg border-left-0 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email">
        
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="card_number">{{ __('Card Number') }}</label>
                        <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-credit-card text-primary"></i>
                            </span>
                        </div>
                        <input id="card_number" type="text" class="form-control-lg border-left-0 form-control{{ $errors->has('card_number') ? ' is-invalid' : '' }}" value="{{ old('card_number') }}" name="card_number"  required >
        
                        @if ($errors->has('card_number'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('card_number') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
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
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-lock-outline text-primary"></i>
                            </span>
                        </div>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-sm-6 ">
                    <div class="mb-4">
                        <div class="form-check">
                        <label class="form-check-label text-muted">
                            <input type="checkbox" class="form-check-input" required>
                            I agree to all Terms & Conditions
                        </label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"> {{ __('Register') }}</button>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 font-weight-light">
                Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>
            </form>
        </div>
        </div>
        <div class="col-lg-6 register-half-bg d-flex flex-row">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2019  All rights reserved.</p>
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->

@endsection
