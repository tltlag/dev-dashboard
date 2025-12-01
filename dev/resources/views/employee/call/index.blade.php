<?php

// Call History List
?>
@extends('employee.layouts.dashboard')

@section('title', ($title ?? null))

@push('footer_scripts')
<script type="text/javascript">
loadList('#list', {
    aLengthMenu: [
        [20, 100, 200, 300, 400, 500],
        [20, 100, 200, 300, 400, 500]
    ],
    bLengthChange: true,
    searching: false,
    manualCallback: '#u-search',
    order: [
        [0, 'desc']
    ],
    aoColumnDefs: [{
        bSortable: false,
        aTargets: [0, 1, 2, 3, 4, 5]
    }],
    bulkActions: null,
    ajax: {
        url: $('#list').data('action'),
        cache: false,
        data: function(data) {
            let keywords = $('#keywords').val();
            let date = $('#ntl-date').val();
            let endDate = $('#ntl-end-date').val();
            let responseType = $('#ntl-response-type').val();
            let callType = $('#ntl-call-type').val();

            data.keywords = keywords;
            data.date = date;
            data.end_date = endDate;
            data.response_type = responseType;
            data.call_type = callType;
        }
    },
    columns: [{
        data: 'call_id'
    }, {
        data: 'call_details'
    }, {
        data: 'from_number'
    }, {
        data: 'to_name'
    }, {
        data: 'disposition'
    }, {
        data: 'call_type'
    }, ]
}, function(e, settings) {
    $.each(settings.aoData, function(i, v) {
        var $obj = $('#list tbody').find('tr').eq(i * 2);
        $obj.find('td').eq(3).attr('colspan', 2);
        $('<tr>\
                <td colspan="3">' + v['_aData']['company'] + '</td>\
                <td>' + v['_aData']['action']['callback'] + '</td>\
                <td>' + v['_aData']['action']['bexio'] + '</td>\
                <td>' + v['_aData']['action']['log_time'] + '</td>\
                <td>' + v['_aData']['action']['log_time_history'] + '</td>\
            </tr>').insertAfter($obj);
    });
});

$('#list').on('init.dt', function(e, settings) {
    $('thead tr').find('th').eq(3).attr('colspan', 2);
    $('tfoot tr').find('th').eq(3).attr('colspan', 2);
    var $clone = $('#list_length').clone();
    $clone.removeAttr('class');
    $clone.removeAttr('id');
    $clone.addClass('cdtl');
    $('#list_paginate').parent().prepend($clone);
});

$(document).on('change', '.cdtl select', function() {
    $('#list_length select').val($(this).val());
    $('#list_length select').trigger('change');
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
                        <h2>{{__('Call History')}}</h2>
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
                                <!-- <li class="breadcrumb-item">
                                    <a href="{{route('employee.call.history')}}">{{__('Call History')}}</a>
                                </li> -->
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{__('Call History')}}
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
                                <a href="{{route('employee.dashboard')}}" title="{{__('All Logged Times')}}"
                                    class="btn btn-primary">{{__('All Logged Times')}}</a>
                                @if (config('global.CLOCKODO_API_KEY', '') && $clockoDoUserId)
                                <a href="#" data-logurl="{{route('employee.call.log.global-popup')}}"
                                    title="{{__('Log Time')}}"
                                    class="btn btn-primary log-time-popup">{{__('Log Time')}}</a>
                                @endif
                                <a href="{{route('employee.contacts')}}"
                                    class="btn btn-primary bexio-customer">{{__('Create Bexio Customer')}}</a>
                                <a href="{{route('employee.sync.bexio.contacts')}}"
                                    class="btn btn-primary">{{__('Sync All')}}</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="accordion" id="search-box">
                                <div class="accordion-item">
                                    <h2 id="search-heading" class="accordion-header">
                                        <button class="accordion-button collapsed text-dark" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#target-search-box"
                                            aria-expanded="true" aria-controls="target-search-box">
                                            {{ __('Search Call History') }}
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
                                                        <label for="ntl-date">{{ __('Start Date') }}</label>
                                                        <input type="date" class="form-control"
                                                            placeholder="{{ __('Select Start Date') }}" id="ntl-date" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="ntl-end-date">{{ __('End Date') }}</label>
                                                        <input type="date" class="form-control"
                                                            placeholder="{{ __('Select End Date') }}"
                                                            id="ntl-end-date" />
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="ntl-response-type">{{ __('Response') }}</label>
                                                        <select class="form-control" id="ntl-response-type">
                                                            <option value="">{{__('All')}}</option>
                                                            @foreach ($responseType as $response)
                                                            <option value="{{$response}}">{{$response}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="text-dark col-md-4">
                                                        <label for="ntl-call-type">{{ __('Call Type?') }}</label>
                                                        <select class="form-control" id="ntl-call-type">
                                                            <option value="">{{__('All')}}</option>
                                                            @foreach ($callTypes as $k => $v)
                                                            <option value="{{$k}}">{{$v}}</option>
                                                            @endforeach
                                                        </select>
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
                                data-action="{{route('employee.call.history.list')}}">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Call Details') }}</th>
                                        <th>{{ __('From Number')}}</th>
                                        <th>{{ __('To')}}</th>
                                        <th>{{ __('Response')}}</th>
                                        <th>{{ __('Call Type')}}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Call Details') }}</th>
                                        <th>{{ __('From Number')}}</th>
                                        <th>{{ __('To')}}</th>
                                        <th>{{ __('Response')}}</th>
                                        <th>{{ __('Call Type')}}</th>
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