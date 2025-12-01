@extends('admin.layouts.main')

@section('title', $title)

@section('content')

<!-- ========== section start ========== -->
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{$title}}</h2>
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
                                    {{$title}}
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
                    <form method="POST" action="{{ route('admin.configuration.save', [strtolower($group)]) }}"
                        enctype="multipart/form-data">
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
                            @foreach ($settingValues as $settingKey => $setting)

                            @php
                            $type = $setting['type'] ?? 'text';
                            $required = $setting['required'] ?? false;
                            $options = $setting['options'] ?? [];
                            $isMultiple = $setting['multiple'] ?? false;
                            $placeholder = $setting['placeholder'] ?? '';
                            $defaultValue = $setting['default_value'] ?? null;
                            $currentValue = old($settingKey, ($settings[$settingKey] ?? $defaultValue));
                            @endphp
                            <div class="{{$type=='color'?'col-6':'col-12'}}">
                                <div class="{{ (! ($type == 'radio' || $type == 'checkbox' || $type == 'file')) ? 'input-style-1' : 'mb-3' }}">
                                    <label for="{{ $setting['id'] ?? $settingKey }}">
                                        {{ $setting['label'] ?? $settingKey }}
                                    </label>

                                    @if($type == 'select')
                                    <select id="{{ $setting['id'] ?? $settingKey }}" name="{{ $setting['name'] ?? $settingKey }}"
                                        class="{{ $setting['class'] ?? null }} form-control @error($settingKey) is-invalid @enderror"
                                        {!! $placeholder ? ' title="' . $placeholder . '"' : '' !!}
                                        {{ ($required ? ' required' : '') }} {{ ($isMultiple ? ' multiple ' : '') }}>
                                        @foreach($options as $option => $optionName)
                                        <option value="{{$option}}" {{($option == $currentValue) ? ' selected ' : ''}}>
                                            {{$optionName}}</option>
                                        @endforeach
                                    </select>
                                    @elseif($type == 'file')
                                    @if (!empty($currentValue))
                                    <div class="mb-2">
                                        <img src="{{ route('images.show', basename($currentValue)) }}" alt="Current Logo"
                                            style="max-width: 100px; max-height: 100px;">
                                        <div class="d-flex">
                                            <input type="checkbox" name="delete_{{ strtolower($settingKey) }}"
                                                id="delete_{{ strtolower($settingKey) }}">
                                            <label for="delete_{{ strtolower($settingKey) }}">Delete Logo</label>
                                        </div>
                                    </div>
                                    @endif
                                    <input type="file" id="{{ $setting['id'] ?? $settingKey }}"
                                        class="{{ $setting['class'] ?? null }} form-control @error($settingKey) is-invalid @enderror"
                                        name="{{ $setting['name'] ?? $settingKey }}" {{ ($required ? ' required' : '') }} />
                                    @elseif($type == 'radio' || $type == 'checkbox')
                                    @php($i = 1)

                                    @foreach($options as $option => $optionName)
                                    <div class="check-input-primary @error($settingKey) is-invalid @enderror">
                                        <input type="{{$type}}" class="form-check-input {{ $setting['class'] ?? null }}"
                                            value="{{$option}}" name="{{ $setting['name'] ?? $settingKey }}"
                                            id="{{ $setting['id'] ?? $settingKey }}-{{$i}}"
                                            {{($option == $currentValue) ? ' checked ' : ''}}
                                            {{ ($required ? ' required' : '') }} />
                                        <label class="form-check-label"
                                            for="{{ $setting['id'] ?? $settingKey }}-{{$i}}">{{$optionName}}</label>
                                    </div>
                                    @php($required = false)
                                    @php($i++)
                                    @endforeach
                                    @elseif($type == 'textarea')
                                    <textarea id="{{ $setting['id'] ?? $settingKey }}"
                                        class="{{ $setting['class'] ?? null }} form-control @error($settingKey) is-invalid @enderror"
                                        name="{{ $setting['name'] ?? $settingKey }}" {!! $placeholder ? ' placeholder="' .
                                        $placeholder . '"' : '' !!}
                                        {{ ($required ? ' required' : '') }}>{{ $currentValue }}</textarea>
                                    @elseif($type == 'button')
                                    <button id="{{ $setting['id'] ?? $settingKey }}"
                                        class="{{ $setting['class'] ?? null }} btn @error($settingKey) is-invalid @enderror"
                                        name="{{ $setting['name'] ?? $settingKey }}" {!! $placeholder ? ' title="' . $placeholder
                                        . '"' : '' !!}>{{ $currentValue }}</button>
                                    @else
                                    <input type="{{ $setting['type'] ?? 'text' }}" id="{{ $setting['id'] ?? $settingKey }}"
                                        class="{{ $setting['class'] ?? null }} form-control @error($settingKey) is-invalid @enderror"
                                        name="{{ $setting['name'] ?? $settingKey }}" value="{{ $currentValue }}" {!! $placeholder
                                        ? ' placeholder="' . $placeholder . '"' : '' !!} {{ ($required ? ' required' : '') }} />
                                    @endif

                                    @error($settingKey)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="col-12">
                            <button type="submit" class="main-btn primary-btn btn-hover">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.container-fluid -->
@endsection