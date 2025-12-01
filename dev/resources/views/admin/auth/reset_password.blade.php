@extends('admin.layouts.auth')

@section('title', __('Reset Password'))
  
@section('content')
<!-- ========== forgot-password-section start ========== -->
<section class="signin-section">
    <div class="container-fluid">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                            <h1 class="text-primary mb-10">{{ __('Reset Password!') }}</h1>
                            <p class="text-medium">
                                {{__('Reset your password to sign in to you account')}}
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
                        <h6 class="mb-15">{{__('Reset Password')}}</h6>
                        <form class="fp nt-form" name="fp_form" id="fp-form" method="POST"
                            action="{{ route('admin.reset.password.procced') }}">
                            @csrf

                            <input type="hidden" name="hash" value="{{$hash}}" />
                            <input type="hidden" name="id" value="{{$id}}" />

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
                                        <label for="password">{{ __('Password') }}</label>
                                        <input pattern="{{ \App\Models\User::PASSWORD_REGEX }}" type="password" name="password" id="password" minlength="8" class="form-control mb-2" placeholder="{{ __('Enter password') }}" data-msg-pattern="{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}" required />
                                        <div class="form-text">{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label for="confirm_password">{{ __('Confirm Password') }}</label>
                                        <input data-rule-equalto="#password" pattern="{{ \App\Models\User::PASSWORD_REGEX }}" type="password" name="password_confirmation" minlength="8" id="confirm_password" class="form-control mb-2" placeholder="{{ __('Enter confirm password') }}" data-msg-pattern="{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}" required />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="button-group d-flex justify-content-center flex-wrap">
                                        <button type="submit" class="main-btn primary-btn btn-hover w-100 text-center">
                                            {{__('Proceed') }}
                                        </button>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-md-12 mt-2">
                                    <div class="text-start text-md-end text-lg-start text-xxl-end mb-30">
                                        <p>{{__('Already have an account?')}} <a class="hover-underline" href="{{ route('admin.login') }}">{{ __('Sign In') }}</a></p>
                                    </div>
                                </div>
                                <!-- end col -->
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
<!-- ========== forgot-password-section end ========== -->
@endsection