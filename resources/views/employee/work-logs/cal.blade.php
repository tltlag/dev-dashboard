<?php

// Work Log Calendar
?>
@extends('employee.layouts.dashboard')

@section('title', ($title ?? null))

@push('footer_scripts')
<script type="text/javascript">
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
                        <h2>{{__('Work Logs')}}</h2>
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
                                    {{__('Work Logs')}}
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
                        <div id="contentWrapper">
                            <div id="content" class="currency-euro">
                                <div class="grouphead textXLSemiBold my-4">
                                    <div class="groupheadElementsLeft ">
                                        <div class="d-flex gap-2 justify-content-between" title="{{__('Calendar')}}">
                                            <a class="freeNavLeft freeNavPrevYear freeNavHover"
                                                href="{{route('employee.work.log', ['year' => $year - 1])}}"
                                                title="{{__('Previous year')}}" data-update="#content">
                                                <i class="fa fa-chevron-left"></i>
                                            </a>
                                            <span class="freeNavItem freeNavCurrentYear">{{$year}}</span>
                                            @if (! $error)
                                            <a class="freeNavRight freeNavNextYear freeNavHover"
                                                href="{{route('employee.work.log', ['year' => $year + 1])}}"
                                                title="{{__('Next year')}}" data-update="#content">
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($error)
                                    <div class="alert alert-danger" role="alert">
                                        {{$error}}
                                    </div>
                                @else
                                <div id="user_report" class="contentCard">
                                    <div class="d-flex gap-2 summaryBoxes">
                                        <div class="border col-md-6 p-4">
                                            <div class="title">
                                                <h4>
                                                    {{__('Absence')}}
                                                    <i class="fa fa-info-circle" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{__('Absences for the 
                                                            whole year :year (also days to come)', [
                                                                'year' => $year
                                                            ])}}">
                                                    </i>
                                                </h4>
                                            </div>
                                            <hr>
                                            <div>
                                                {{__('Holidays :regularHolidays of :holidaysQuota', [
                                                    'regularHolidays' =>
                                                        $userReports['sum_absence']['regular_holidays'],
                                                    'holidaysQuota' => $userReports['holidays_quota']
                                                ])}} <i class="fa fa-info-circle"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="{{__('Carryover from previous year')}}: 
                                                        {{$userReports['holidays_carry']}}">
                                                </i>, {{__('rest')}}
                                                {{
                                                    ($userReports['holidays_quota'] ?? 0)
                                                    -
                                                    ($userReports['sum_absence']['regular_holidays'] ?? 0)
                                                }}
                                            </div>

                                            @if($userReports['sum_absence']['sick_self'] > 0)
                                            <div>
                                                {{__('Sick days (self)')}}: {{$userReports['sum_absence']['sick_self']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['sick_child'] > 0)
                                            <div>
                                                {{__('Sick days (child)')}}: 
                                                    {{$userReports['sum_absence']['sick_child']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['special_leaves'] > 0)
                                            <div>
                                                {{__('Special leaves')}}: 
                                                    {{$userReports['sum_absence']['special_leaves']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['school'] > 0)
                                            <div>
                                                {{__('School')}}: {{$userReports['sum_absence']['school']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['maternity_protection'] > 0)
                                            <div>
                                                {{__('Maternity protection')}}: {{
                                                    $userReports['sum_absence']['maternity_protection']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['home_office'] > 0)
                                            <div>
                                                {{__('Home office')}}: {{$userReports['sum_absence']['home_office']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['out_of_office'] > 0)
                                            <div>
                                                {{__('Out of office')}}: 
                                                    {{$userReports['sum_absence']['out_of_office']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['quarantine'] > 0)
                                            <div>
                                                {{__('Quarantine')}}: {{$userReports['sum_absence']['quarantine']}}
                                            </div>
                                            @endif

                                            @if($userReports['sum_absence']['military_service'] > 0)
                                            <div>
                                                {{__('Military service')}}: 
                                                    {{$userReports['sum_absence']['military_service']}}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="border col-md-6 p-4">
                                            <div id="sumHours" class="min2Rows">
                                                <h4 class="d-flex justify-content-between">
                                                    <span>{{__('Working time')}}</span>
                                                    <span class="hoursDiff
                                                        {{ (
                                                            $userReports['diff'] < 0 ?
                                                            'text-danger' :
                                                            'text-success'
                                                        ) }}">
                                                        {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                            $userReports['diff'] ?: 0
                                                        )}}
                                                    </span>
                                                </h4>
                                                <hr>
                                                <div class="hours grey">
                                                    {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                        $userReports['sum_hours'] ?: 0
                                                    )}}
                                                    &nbsp;{{__('of')}}&nbsp;
                                                    {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                        $userReports['sum_target'] ?: 0
                                                    )}}
                                                    &nbsp;{{__('planned hours')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix month my-4 overflow-auto">
                                        @php
                                            krsort($userReports['month_details']);
                                        @endphp
                                        @foreach ($userReports['month_details'] as $month)
                                        <hr/>
                                        <div class="month clearfix my-4">
                                            <div class="d-flex justify-content-between">
                                                <div class="monthName">
                                                    {{
                                                        \Carbon\Carbon::create()
                                                        ->month($month['nr'])->format('F') }} {{ $year }}
                                                </div>
                                                <div class="border monthEvaluation p-2">
                                                    <div class="monthsum {{ (
                                                                $userReports['diff'] < 0 ?
                                                                'text-danger' :
                                                                'text-success'
                                                            ) }}">
                                                        <div class="duration">
                                                            {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                $month['diff'] ?:0
                                                            )}}
                                                        </div>
                                                        <div class="hours">
                                                            {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                $month['sum_hours'] ?:0
                                                            )}}
                                                            &nbsp;/&nbsp;
                                                            {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                $month['sum_target'] ?:0
                                                            )}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="headTitles table table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th class="weeknr">{{__('CW')}}</th>
                                                        <th class="px-4">{{__('Mon')}}</th>
                                                        <th class="px-4">{{__('Tue')}}</th>
                                                        <th class="px-4">{{__('Wed')}}</th>
                                                        <th class="px-4">{{__('Thu')}}</th>
                                                        <th class="px-4">{{__('Fri')}}</th>
                                                        <th class="weekend weekendstart px-4">{{__('Sat')}}</th>
                                                        <th class="weekend px-4">{{__('Sun')}}</th>
                                                        <th class="sum text-end">{{__('Sum')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($month['week_details'] as $week)
                                                    <tr class="week">
                                                        <td class="weeknr">{{ 
                                                            ! ($month['nr'] == 12 && $week['nr'] == 1) ? 
                                                            $week['nr'] : '' }}</td>
                                                        @for ($i = 0; $i < (7 - count($week['day_details'])); $i++)
                                                        <td class="day px-4">
                                                            <div class="d-flex justify-content-between td-c">
                                                                <div class="date">&nbsp;</div>
                                                            </div>
                                                        </td>
                                                        @endfor

                                                        @foreach ($week['day_details'] as $d)
                                                        <td class="day px-4">
                                                            <div class="d-flex justify-content-between td-c">
                                                                <div class="date">
                                                                    {{ \Carbon\Carbon::parse($d['date'])
                                                                        ->format('d') }}
                                                                </div>

                                                                @php
                                                                    $diff = $d['diff'] ?? 0;
                                                                    $onclick = '';

                                                                    if ($diff <> 0) {
                                                                        $onclick = ' onclick="showWorkLogEntries(\''
                                                                            . $d['date'] . '\', \''
                                                                            . route('employee.work.log.entries') . '\', this);"';
                                                                    }
                                                                @endphp

                                                                @if ($diff <> 0)
                                                                    <div class="cursor-pointer inner {{ (
                                                                            $diff < 0 ?
                                                                            'text-danger' :
                                                                            'text-success'
                                                                        ) }}"{!!$onclick!!}>
                                                                        {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                            $diff
                                                                        )}}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        @endforeach
                                                        <td class="sum text-end">
                                                            <div class="inner {{ (
                                                                    $userReports['diff'] < 0 ?
                                                                    'text-danger' :
                                                                    'text-success'
                                                                ) }}">
                                                                <div class="duration">
                                                                    {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                        $week['diff'] ?:0
                                                                    )}}
                                                                </div>
                                                                <div class="hours">
                                                                    {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                        $week['sum_hours'] ?:0
                                                                    )}}
                                                                    &nbsp;/&nbsp;
                                                                    {{\App\Helpers\CommonHelper::convertSecondsToHours(
                                                                        $week['sum_target'] ?:0
                                                                    )}}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ========== Modal for entries html ===== -->
<div class="modal fade" id="workLogEntriesModal" tabindex="-1" role="dialog" aria-labelledby="workLogEntriesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-md-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workLogEntriesModalLabel">{{__('Work Log Entries')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="workLogEntriesModalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection