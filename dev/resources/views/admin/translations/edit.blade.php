@extends('admin.layouts.main')

@section('title', sprintf(__('%s Translation'), (isset($translation) ? 'Edit' : 'Add')))

@section('content')
<!-- ========== section start ========== -->
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{__('Edit Translation')}}</h2>
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
                                    {{__('Edit Translation')}}
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
                    <form
                        action="{{ isset($translation) ? route('admin.translations.update', $translation->id) : route('admin.translations.store') }}"
                        method="POST">
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

                        @if(isset($translation))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="key">{{__('Key')}}</label>
                                    <input type="text" name="key" id="key" class="form-control"
                                        value="{{ old('key', $translation->key ?? '') }}" {{ isset($translation) ? 'readonly' : '' }} />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="locale">{{__('Locale')}}</label>
                                    <select name="locale" id="locale" class="form-control">
                                        <option value="">{{__('Select Language')}}</option>
                                        @foreach (\App\Models\Translation::getLanguages() as $code => $language)
                                            <option value="{{$code}}"{{ old('locale', $translation->locale ?? '') == $code ? ' selected' : '' }}>{{$language}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-style-1">
                                    <label for="value">{{__('Value')}}</label>
                                    <textarea name="value" id="value"
                                        class="form-control">{{ old('value', $translation->value ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="main-btn primary-btn btn-hover">{{sprintf(__('%s Translation'), (isset($translation) ? 'Edit' : 'Add'))}}</button>
                                <a href="{{route('admin.translations.index')}}" class="btn btn-dark btn-hover main-btn">{{__('Back')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection