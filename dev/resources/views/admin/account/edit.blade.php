@extends('admin.layouts.main')

@section('title', __('Edit Your Profile'))

@section('content')

<!-- ========== section start ========== -->
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{__('My Profile')}}</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{__('My Profile')}}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->

        <div class="row">
            <div class="col-md-12">
                <div class="card-style settings-card-2 mb-30">
                    <form method="POST" action="{{ route('admin.profile.update') }}" class="nt-form" enctype="multipart/form-data">
                        @csrf

                        @if($errors->any())
                        {!! implode('', $errors->all('<div
                            class="alert alert-danger alert-dismissible fade show" role="alert">:message<button
                                type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button></div>')) !!}
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="{{ __('Enter name') }}" value="{{old('name', $user->name)}}" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="name">{{ __('Upload Profile Picture') }}</label>
                                    <div class="d-flex align-items-center mb-30 gap-3">
                                        <div class="update-image">
                                            <input type="file" name="profile_image_file" class="form-control" id="profile_image_file"  />
                                        </div>

                                        @if ($user->profile_image)
                                            <div class="position-relative profile-image rounded-circle"
                                                style="width: 100px;">
                                                <div class="img-area">
                                                    <img src="{{route('admin.profile.image')}}" alt="{{$user->name}}" class="w-100" />
                                                    <a class="remove-image d-inline" onclick="removeImage(this, '#profile_image')"
                                                        href="javascript:void(0);">&#215;</a>
                                                </div>
                                                <input type="hidden" name="profile_image" id="profile_image" value="{{$user->profile_image}}" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="username">{{ __('Username') }}</label>
                                    <input type="text" name="username" id="username" class="form-control"
                                        placeholder="{{ __('Enter username') }}" value="{{old('username', $user->username)}}"
                                        minlength="8" maxlength="29" pattern="{{ \App\Models\User::USERNAME_REGEX }}"
                                        data-msg-pattern="{{ __(\App\Models\User::USERNAME_HINT_MESSAGE) }}" required />
                                    <div class="form-text text-dark">{{ __(\App\Models\User::USERNAME_HINT_MESSAGE) }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="{{ __('Enter user email address') }}"
                                        value="{{old('email', $user->email)}}" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input pattern="{{ \App\Models\User::PASSWORD_REGEX }}" type="password" name="password"
                                        id="password" minlength="8" class="form-control"
                                        placeholder="{{ __('Enter password') }}"
                                        data-msg-pattern="{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}" />
                                    <div class="form-text text-dark">{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="confirm_password">{{ __('Confirm Password') }}</label>
                                    <input data-rule-equalto="#password" pattern="{{ \App\Models\User::PASSWORD_REGEX }}"
                                        type="password" name="password_confirmation" minlength="8" id="confirm_password"
                                        class="form-control" placeholder="{{ __('Enter Confirm Password') }}"
                                        data-msg-pattern="{{ __(\App\Models\User::PASSWORD_HINT_MESSAGE) }}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="main-btn primary-btn btn-hover">
                                    {{__('Update Profile')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- ========== section end ========== -->
@endsection