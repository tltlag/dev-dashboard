@extends('employee.layouts.inner')

@section('title', __('Employee Login'))

@section('content')
    <div class="border col-md-6 p-5 text-center">
        <h2 class="login">{{__('Wildixin Employee Login')}}</h2>
        <p>{{__('Login with portal')}}</p>

        @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger alert-dismissible fade show" role="alert">
            :message<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        ')) !!}
        @endif

        @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
@endsection