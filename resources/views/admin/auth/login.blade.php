@extends('admin.layouts.auth')

@section('title', __('Login'))

@section('content')
<!-- ========== signin-section start ========== -->
<section class="signin-section">
    <div class="container-fluid">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                            <h1 class="text-primary mb-10">{{ __('Welcome Back!') }}</h1>
                            <p class="text-medium">
                                {{__('Sign in to you account')}}
                            </p>
                        </div>
                        <div class="cover-image">
                            <img src="{{ asset('dashboard-assets/images/auth/signin-image.svg')}}" alt="" />
                        </div>
                        <div class="shape-image">
                            <img src="{{ asset('dashboard-assets/images/auth/shape.svg')}}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-6">
                <div class="signin-wrapper">
                    <div class="form-wrapper">
                        <h6 class="mb-15">{{__('Sign In')}}</h6>
                        <p class="text-sm mb-25">
                            {{__('Start creating the best possible user experience for you customers.')}}
                        </p>
                        <form class="login nt-form" name="login_form" id="login-form" method="POST"
                            action="{{ route('admin.login') }}">
                            @csrf
                            <div class="row">

                                @if($errors->any())
                                {!! implode('', $errors->all('<div
                                    class="alert alert-danger alert-dismissible fade show" role="alert">:message<button
                                        type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button></div>')) !!}
                                @endif

                                @if(session('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control form-control-user"
                                            id="email" aria-describedby="emailHelp"
                                            placeholder="{{ __('Enter Your Email Address') }}"
                                            value="{{ old('email', '') }}"
                                            data-msg="{{__('Please enter your email address.')}}" required />
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="password" placeholder="{{ __('Enter Your Password') }}"
                                            data-msg="{{__('Please enter your password')}}" required />
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-xxl-6 col-lg-12 col-md-6">
                                    <div class="form-check checkbox-style mb-30">
                                        <input type="checkbox" name="remember_me" class="form-check-input"
                                            id="remember_me" value="1"
                                            {{ old('remember_me', 0) == 1 ? ' checked' : '' }} />
                                        <label class="custom-control-label"
                                            for="remember_me">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-xxl-6 col-lg-12 col-md-6">
                                    <div class="text-start text-md-end text-lg-start text-xxl-end mb-30">
                                        <a class="hover-underline" href="{{ route('admin.forgot_password') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-12">
                                    <div class="button-group d-flex justify-content-center flex-wrap">
                                        <button type="submit" class="main-btn primary-btn btn-hover w-100 text-center">
                                            {{__('Sign In') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
</section>
<!-- ========== signin-section end ========== -->
@endsection