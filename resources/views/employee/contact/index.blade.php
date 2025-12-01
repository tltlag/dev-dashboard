@extends('employee.layouts.dashboard')

@section('title', ($title ?? null))

@push('footer_scripts')
<script type="text/javascript">
loadList('#list', {
    searching: false,
    manualCallback: '#u-search',
    order: [
        [0, 'desc']
    ],
    aoColumnDefs: [{
        bSortable: false,
        aTargets: [2, 3, 4, 5, 6]
    }],
    bulkActions: null,
    ajax: {
        url: $('#list').data('action'),
        cache: false,
        data: function(data) {
            let keywords = $('#keywords').val();
            let contactType = $('#ntl-contact-type').val();

            data.keywords = keywords;
            data.contact_type = contactType;
        }
    },
    columns: [{
        data: 'id'
    }, {
        data: 'name'
    }, {
        data: 'mobile_number'
    }, {
        data: 'phone_number'
    }, {
        data: 'fax_number'
    }, {
        data: 'type'
    }, {
        data: 'action'
    }, ]
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
                        <h2>{{__('Contacts')}}</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('employee.dashboard')}}">{{__('Dashboard')}}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{__('Contacts')}}
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
                                <a href="{{route('employee.contact.add')}}" title="' . __('Add New Contact') . '"
                                    class="btn btn-primary mr-7 mb-2">{{__('Add New Contact')}}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion" id="search-box">
                                <div class="accordion-item">
                                    <h2 id="search-heading" class="accordion-header">
                                        <button class="accordion-button collapsed text-dark" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#target-search-box"
                                            aria-expanded="true" aria-controls="target-search-box">
                                            {{ __('Search Contacts') }}
                                        </button>
                                    </h2>
                                    <div id="target-search-box" class="accordion-collapse collapse"
                                        aria-labelledby="search-heading" data-bs-parent="#search-box">
                                        <div class="accordion-body">
                                            <form id="u-search">
                                                <div class="row">
                                                    <div class="text-dark col-md-4">
                                                        <label for="keywords">{{ __('Keywords') }}</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ __('Enter keywords') }}" id="keywords" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="ntl-contact-type">{{ __('Contact Type') }}</label>
                                                        <select class="form-control" id="ntl-contact-type">
                                                            <option value="">{{__('All')}}</option>
                                                            @foreach (\App\Models\BexioEmployee::getContactTypeList() as
                                                            $k => $v)
                                                            <option value="{{$k}}">{{$v}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label>&nbsp;</label>
                                                        <div class="d-flex gap-3">
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ __('Search') }}</button>
                                                            <button onclick="return clearForm(this);"
                                                                class="btn btn-light">{{ __('Clear Search') }}</button>
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
                            <table class="table text-dark" id="list" width="100%" cellspacing="0"
                                data-action="{{route('employee.contact.list')}}">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Mobile') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Fax') }}</th>
                                        <th>{{ __('Type')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Mobile') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Fax') }}</th>
                                        <th>{{ __('Type')}}</th>
                                        <th>{{ __('Action')}}</th>
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

<!-- Modal Structure -->
<div class="modal fade" id="log-time-modal" tabindex="-1" role="dialog" aria-labelledby="log-time-label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="log-time-label">{{__('Log Time')}}</h5>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection