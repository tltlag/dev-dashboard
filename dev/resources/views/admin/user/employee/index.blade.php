@extends('admin.layouts.main')

@section('title', __('Employees'))

@push('footer_scripts')
<script type="text/javascript">
loadList('#list', {
    searching: false,
    manualCallback: '#u-search',
    order: [
        [1, 'desc']
    ],
    aoColumnDefs: [{
        bSortable: false,
        aTargets: [0, 2, 3]
    }],
    bulkActions: null,
    ajax: {
        url: $('#list').data('action'),
        cache: false,
        data: function(data) {
            let keywords = $('#keywords').val();

            data.keywords = keywords;
        }
    },
    columns: [
        {
            data: 'sr'
        },
        {
            data: 'name'
        },
        {
            data: 'username'
        },
        {
            data: 'action'
        },
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
                        <div class="accordion" id="search-box">
                            <div class="accordion-item">
                                <h2 id="search-heading" class="accordion-header">
                                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#target-search-box" aria-expanded="true"
                                        aria-controls="target-search-box">
                                        {{ __('Search Employees') }}
                                    </button>
                                </h2>
                                <div id="target-search-box" class="accordion-collapse collapse" aria-labelledby="search-heading"
                                    data-bs-parent="#search-box">
                                    <div class="accordion-body">
                                        <form id="u-search">
                                            <div class="row">
                                                <div class="col-md-4 text-dark">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Enter keywords') }}" id="keywords" />
                                                </div>
                                                <div class="d-flex gap-3 col-md-6">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ __('Search') }}</button>
                                                    <button onclick="return clearForm(this);"
                                                        class="btn btn-light">{{ __('Clear Search') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper table-responsive mt-3">
                            <table class="table text-dark" id="list" width="100%" cellspacing="0"
                                data-action="{{route('admin.user.employee.list')}}">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sr.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Details')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>{{ __('Sr.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Details')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection