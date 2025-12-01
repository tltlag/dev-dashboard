<?php

// Employee Dashboard
?>
@extends('employee.layouts.dashboard')

@section('title', ($title ?? null))

@push('footer_scripts')
<link href='https://unpkg.com/@fullcalendar/core@4.4.2/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/daygrid@4.4.2/main.min.css' rel='stylesheet' />
<link href='https://unpkg.com/@fullcalendar/timegrid@4.4.2/main.min.css' rel='stylesheet' />
<script src='https://unpkg.com/@fullcalendar/core@4.4.2/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.2/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.2/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.2/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/core@4.4.2/locales-all.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: '{{config("global.SITE_LOCALE", \App\Models\Translation::LANG_CODE_ENGLISH)}}',
        plugins: ['interaction', 'dayGrid', 'timeGrid'],
        defaultView: 'dayGridMonth',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{route("employee.call.time-logs")}}',
        eventClick: function(info) {
            // Open modal and display event details
            $('#eventModal').modal('show');
            var eventDetails = `
                        <p><strong>{{__('Title')}}:</strong> ${info.event.title}</p>
                        <p><strong>{{__('Start Time')}}:</strong> ${info.event.start.toLocaleString()}</p>
                        <p><strong>{{__('End Time')}}:</strong> ${info.event.end.toLocaleString()}</p>
                        <p><strong>{{__('Description')}}:</strong> ${info.event.extendedProps.description}</p>
                        <p><strong>{{__('Project')}}:</strong> ${info.event.extendedProps.project}</p>
                        <p>
                            <a href="${info.event.extendedProps.delete_url}"
                            title="{{ __('Delete Record') }}"
                            onclick="return confirm('{{ __('Are you sure?') }}');"
                            class="btn btn-danger mt-2">{{ __('Delete Record') }}</a></p>
                    `;
            document.getElementById('eventDetails').innerHTML = eventDetails;
        }
    });
    calendar.render();
});
</script>
@endpush

@section('content')
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            @if ($user->last_login)
            <div class="row ">
                <div class="px-2 py-2 col-md-12">
                    <div class=" p-2">
                        <i class="fa fa-lock"></i>
                        {{__('Last Login')}}: {{\App\Helpers\CommonHelper::date($user->last_login)}}.
                        <i class="fa fa-clock"></i>
                        {{\App\Helpers\CommonHelper::time($user->last_login)}}
                    </div>
                </div>
            </div>
            @endif
            <div class="row">&nbsp;</div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{__('Dashboard')}}</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    {{__('Dashboard')}}
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
        <div class="container">
            @if ($currentCall instanceof \App\Models\UserHasOngoingCall)
            <div class="row">
                <div class="px-2 py-2 col-md-12">
                    <div class="border p-2">
                        <i class="fa fa-phone-alt"></i>
                        {!!__('On Going Call With :phone_number', [
                        'phone_number' => $currentCallName
                        ])!!} <br />
                        {{__('Started At:')}} {{\App\Helpers\CommonHelper::date($currentCall->created_at)}}. <i
                            class="fa fa-clock"></i> {{\App\Helpers\CommonHelper::time($currentCall->created_at)}}
                    </div>
                </div>
            </div>
            @endif

            @if ($lastCall instanceof \App\Models\UserHasOngoingCall)
            <div class="row">
                <div class="px-2 py-2 col-md-12">
                    <div class="border p-2">
                        <i class="fa fa-phone-alt"></i>
                        {!!__('Last Call With :phone_number', [
                        'phone_number' => $lastCallName
                        ])!!} <br />
                        {{__('Started At:')}} {{\App\Helpers\CommonHelper::date($lastCall->created_at)}}. <i
                            class="fa fa-clock"></i> {{\App\Helpers\CommonHelper::time($lastCall->created_at)}}
                    </div>
                </div>
            </div>
            @endif

            <div class="row align-items-center">
                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon orange">
                            <i class="lni lni-user"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">{{__('Contacts')}}</h6>
                            <h3 class="text-bold mb-10"> {{\App\Models\BexioEmployee::count()}} </h3>

                        </div>
                    </div>
                </div>
                <!-- End Col -->

                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30">
                        <div class="icon primary">
                            <i class="lni lni-phone"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">{{__('Calls')}}</h6>
                            <h3 class="text-bold mb-10">{{
                                \App\Models\CallHistory::where('user_id', auth('employee')->user()->id)
                                ->whereDate('start', \Carbon\Carbon::today())
                                ->orWhereDate('end',\Carbon\Carbon::today())
                                ->count()
                            }}</h3>
                        </div>
                    </div>
                </div>

                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30" style="padding: 40px 20px;">
                        <div class="icon primary">
                            <i class="lni lni-chevron-right-circle"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">
                                <a href="{{route('employee.work.log')}}">
                                    {{__('Holiday')}}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <!-- End Col -->
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="icon-card mb-30" style="padding: 40px 20px;">
                        <div class="icon primary">
                            <i class="lni lni-chevron-right-circle"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb-10">
                                <a href="{{route('employee.work.log')}}">
                                    {{__('Work Log')}}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 p-2 shadow">
                    <a href="{{route('employee.contacts')}}" title="' . __('Create Customer') . '"
                        class="btn btn-primary w-100">{{__('Create Customer')}}</a>
                </div>
                @if (config('global.CLOCKODO_API_KEY', '') && $clockoDoUserId)
                <div class="col-md-6 p-2 shadow">
                    <a href="#" data-logurl="{{route('employee.call.log.global-popup')}}" title="' . __('Log Time') . '"
                        class="btn btn-primary log-time-popup w-100">{{__('Log Time')}}</a>
                </div>
                @endif
                @if ($bexioUrl)
                <div class="col-md-6 p-2 shadow">
                    <a href="{{$bexioUrl}}" title="' . __('Bexio') . '" target="_blank"
                        class="btn btn-primary w-100">{{__('Bexio')}}</a>
                </div>
                @endif
                <div class="col-md-6 p-2 shadow">
                    <a href="{{route('employee.call.history')}}" title="' . __('Call History') . '"
                        class="btn btn-primary w-100">{{__('Call History')}}</a>
                </div>
            </div>


            <div class="row">
                <div id="calendar" class="p-2 col-md-12 dark-text"></div>
            </div>



        </div>
    </div>
</section>

<!-- Modal Structure -->
<div class="modal fade" id="log-time-modal" tabindex="-1" role="dialog" aria-labelledby="log-time-label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">{{__('Time Log Details')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventDetails">
                    <!-- Event details will be dynamically populated here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection