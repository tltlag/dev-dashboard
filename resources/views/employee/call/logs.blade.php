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
        aTargets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
    }],
    bulkActions: null,
    ajax: {
        url: $('#list').data('action'),
        cache: false,
        data: function(data) {
            let clientId = $('#client_id').val();
            let projectId = $('#clockodo_project_id').val();
            let serviceId = $('#service_id').val();
            let startDatetime = $('#start_datetime').val();
            let endDatetime = $('#end_datetime').val();
            let duration = $('#duration').val();
            let search = $('#search').val();

            data.client_id = clientId;
            data.project_id = projectId;
            data.service_id = serviceId;
            data.start_datetime = startDatetime;
            data.end_datetime = endDatetime;
            data.duration = duration;
            data.search = search;
        }
    },
    columns: [{
            data: 'id'
        },
        {
            data: 'client_name'
        },
        {
            data: 'clockodo_project_name'
        },
        {
            data: 'service_name'
        },
        {
            data: 'date'
        },
        {
            data: 'start_time'
        },
        {
            data: 'end_time'
        },
        {
            data: 'duration'
        },
        {
            data: 'service_description'
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
                        <h2>{{__('Log Time History')}}</h2>
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
                                <li class="breadcrumb-item">
                                    <a href="{{route('employee.call.history')}}">{{__('Call History')}}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{__('Log Time History')}}
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
                                <a href="{{route('employee.call.history')}}" class="btn btn-dark">{{__('Back')}}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion" id="search-box">
                                <div class="accordion-item">
                                    <h2 id="search-heading" class="accordion-header">
                                        <button class="accordion-button collapsed text-dark" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#target-search-box"
                                            aria-expanded="true" aria-controls="target-search-box">
                                            {{ __('Search Logs') }}
                                        </button>
                                    </h2>
                                    <div id="target-search-box" class="accordion-collapse collapse"
                                        aria-labelledby="search-heading" data-bs-parent="#search-box">
                                        <div class="accordion-body">
                                            <form id="u-search">
                                                <div class="row">
                                                    <div class="text-dark col-md-4">
                                                        <label for="search">{{ __('Search') }}</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ __('Enter search keywords') }}" id="search" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="client_id">{{ __('Client') }}</label>
                                                        <select class="form-control" id="client_id">
                                                            <option value="">{{ __('All Clients') }}</option>
                                                            @foreach($clients as $client)
                                                            <option value="{{$client->client_id}}">
                                                                {{$client->client_name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="clockodo_project_id">{{ __('Project') }}</label>
                                                        <select class="form-control" id="clockodo_project_id">
                                                            <option value="">{{ __('All Projects') }}</option>
                                                            @foreach($projects as $project)
                                                            <option value="{{$project->clockodo_project_id}}">
                                                                {{$project->clockodo_project_name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="service_id">{{ __('Service') }}</label>
                                                        <select class="form-control" id="service_id">
                                                            <option value="">{{ __('All Services') }}</option>
                                                            @foreach($services as $service)
                                                            <option value="{{$service->service_id}}">
                                                                {{$service->service_name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="start_datetime">{{ __('Start Date') }}</label>
                                                        <input type="datetime-local" class="form-control"
                                                            placeholder="{{ __('Select Start Date') }}" id="start_datetime" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="end_datetime">{{ __('End Date') }}</label>
                                                        <input type="datetime-local" class="form-control"
                                                            placeholder="{{ __('Select End Date') }}" id="end_datetime" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="duration">{{ __('Duration') }}</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ __('Enter duration in seconds') }}" id="duration" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label>&nbsp;</label>
                                                        <div class="">
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
                                data-action="{{route('employee.call.log.list', [$id])}}">
                                <thead>
                                    <tr>
                                        <th>{{ __('#ID') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Project') }}</th>
                                        <th>{{ __('Service')}}</th>
                                        <th>{{ __('Date')}}</th>
                                        <th>{{ __('Start')}}</th>
                                        <th>{{ __('End')}}</th>
                                        <th>{{ __('Duration')}}</th>
                                        <th>{{ __('Description')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th >{{ __('#ID') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Project') }}</th>
                                        <th>{{ __('Service')}}</th>
                                        <th>{{ __('Date')}}</th>
                                        <th>{{ __('Start')}}</th>
                                        <th>{{ __('End')}}</th>
                                        <th>{{ __('Duration')}}</th>
                                        <th>{{ __('Description')}}</th>
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
<div class="modal fade" id="logdesc-modal" tabindex="-1" role="dialog" aria-labelledby="logdesc-label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logdesc-label">{{__('Log Description')}}</h5>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection