@extends('admin.layouts.main')

@section('title', __('Translations'))

@push('footer_scripts')
<script type="text/javascript">
    loadList('#list', {
        searching: false,
        manualCallback: '#u-search',
        order: [[0, 'desc']],
        aoColumnDefs: [
            {
                bSortable: false,
                aTargets: [4]
            }
        ],
        bulkActions: null,
        ajax: {
            url: $('#list').data('action'),
            cache: false,
            data: function(data) {
                let keywords = $('#keywords').val();
                let lcoale = $('#locale').val();

                data.keywords = keywords;
                data.lcoale = lcoale;
            }
        },
        columns: [
            {data: 'id'},
            {data: 'key'},
            {data: 'locale'},
            {data: 'value'},
            {data: 'action'},
        ]
    });
</script>
@endpush
  
@section('content')
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{__('Employees')}}</h2>
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
                                    {{__('Employees')}}
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

        <!-- ========== tables-wrapper start ========== -->
        <div class="tables-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <div class="row">
                            <div class="col-md-12 mb-4 text-end">
                                <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">{{ __('Add Translation')}}</a>
                                <a href="{{ route('admin.translations.sync') }}" class="btn btn-primary">{{ __('Sync Translations')}}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion" id="search-box">
                                <div class="accordion-item">
                                    <h2 id="search-heading" class="accordion-header">
                                        <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#target-search-box" aria-expanded="true" aria-controls="target-search-box">
                                            {{ __('Search Translations') }}
                                        </button>
                                    </h2>
                                    <div id="target-search-box" class="accordion-collapse collapse" aria-labelledby="search-heading" data-bs-parent="#search-box">
                                        <div class="accordion-body">
                                            <form id="u-search">
                                                <div class="row">
                                                    <div class="text-dark col-md-4">
                                                        <label for="keywords">{{ __('Keywords') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ __('Enter keywords') }}" id="keywords" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="locale">{{ __('Language') }}</label>
                                                        <select name="locale" id="locale" class="form-control">
                                                            <option value="">{{__('Select Language')}}</option>
                                                            @foreach (\App\Models\Translation::getLanguages() as $code => $language)
                                                                <option value="{{$code}}"{{ old('locale', $translation->locale ?? '') == $code ? ' selected' : '' }}>{{$language}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label>&nbsp;</label>
                                                        <div class="col d-flex gap-3">
                                                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                                            <button onclick="return clearForm(this);" class="btn btn-light">{{ __('Clear Search') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper table-responsive mt-3">
                            <table class="table text-dark" id="list" width="100%" cellspacing="0" data-action="{{route('admin.translation.list')}}">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID')}}</th>
                                        <th>{{ __('Key')}}</th>
                                        <th>{{ __('Locale')}}</th>
                                        <th>{{ __('Value')}}</th>
                                        <th>{{ __('Actions')}}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>{{ __('ID')}}</th>
                                        <th>{{ __('Key')}}</th>
                                        <th>{{ __('Locale')}}</th>
                                        <th>{{ __('Value')}}</th>
                                        <th>{{ __('Actions')}}</th>
                                    </tr>
                                </tfoot>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.container-fluid -->
@endsection