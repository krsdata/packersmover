@extends('front/layouts/main')

@section('content')

        <!-- register begin -->
        <div class="register login-page">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-md-8">
                        <div class="reg-body">
                        <form method="POST" action="{{ route('login') }}" class="pt-3">
                             {{ csrf_field() }}
                                <h4 class="sub-title">Login to your account</h4>
                                <input id="email" type="email" class="form-control-lg border-left-0 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="User Name*" required>
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                                <input id="password" type="password" class="form-control-lg border-left-0 form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif    
                                <button type="submit" class="btn-form def-btn">Login</button>
                            </form>
                            <div class="">
                    Don't have an account? <a href="{{url('registers')}}" class="text-primary">Create</a>
                     </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection