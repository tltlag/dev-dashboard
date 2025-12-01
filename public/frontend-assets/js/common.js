let dt = null;
let ajaxMessage = [];
let customerXhr = null;
let currentLogTimeTr = null;
let empChSelect2 = null;
let cmpChSelect2 = null;
let cmpSelect2 = null;
let entryXhr = null;

(function ($) {
    // $.datetimepicker.setLocale('en');

    showWorkLogEntries = function (date = null, route, obj) {
        var ele = $('#user_report');

        if (entryXhr) {
            entryXhr.abort();
        }

        if (!date) {
            ele.removeClass('running');

            return false;
        }

        ele.addClass('running');

        entryXhr = $.ajax({
            url: route,
            data: {
                date: date
            },
            method: 'GET',
            dataType: 'json',
            cache: false,
            success: function (data) {
                ele.removeClass('running');
                entryXhr = null;

                if (data.status) {
                    $('#workLogEntriesModalContent').html(data.optionText);
                    $('#workLogEntriesModal').modal('show');
                } else {
                    $('#workLogEntriesModalContent').html(
                        '<div class="alert alert-danger">' +
                        data.error +
                        '</div>'
                    );
                    $('#workLogEntriesModal').modal('show');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                ele.removeClass('running');
                entryXhr = null;
            }
        });
    }

    addCompanyForm = function (obj) {
        var cteFormWrap = $(obj).closest('.ct-flds').find('.cte-form');

        if (cteFormWrap.hasClass('running')) {
            return false;
        }

        cteFormWrap.addClass('running');

        $.ajax({
            url: $(obj).data('url'),
            method: 'GET',
            cache: false,
            success: function (data) {
                cteFormWrap.removeClass('running');
                cteFormWrap.html(data);

                if (cteFormWrap.find('#search-ch-cmp').length > 0) {
                    cmpChSelect2 = $('#search-ch-cmp').select2({
                        placeholder: 'Search for a contact',
                        ajax: {
                            url: ntAjaxUrls.empSearchCh,
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: data.map(function (item) {
                                        return {
                                            id: item.name,
                                            text: item.name,
                                            additionalData: item
                                        };
                                    })
                                };
                            }
                        }
                    });
                }

                if (cteFormWrap.find('#companyDropdown').length > 0) {
                    cmpSelect2 = $('#companyDropdown').select2({
                        width: '90%',
                        ajax: {
                            url: ntAjaxUrls.cmpSearch,
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error loading content: ' + textStatus);
                cteFormWrap.removeClass('running');
            }
        });
    }

    closeCompanyForm  = function (obj) {
        var cteFormWrap = $(obj).closest('.ct-flds').find('.cte-form');

        if (cteFormWrap.hasClass('running')) {
            return false;
        }

        cteFormWrap.addClass('running');

        $.ajax({
            url: $(obj).data('url'),
            method: 'GET',
            cache: false,
            success: function (data) {
                cteFormWrap.removeClass('running');
                cteFormWrap.html(data);

                if (cteFormWrap.find('#search-ch-cmp').length > 0) {
                    cmpChSelect2 = $('#search-ch-cmp').select2({
                        placeholder: 'Search for a contact',
                        ajax: {
                            url: ntAjaxUrls.empSearchCh,
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: data.map(function (item) {
                                        return {
                                            id: item.name,
                                            text: item.name,
                                            additionalData: item
                                        };
                                    })
                                };
                            }
                        }
                    });
                }

                if (cteFormWrap.find('#companyDropdown').length > 0) {
                    cmpSelect2 = $('#companyDropdown').select2({
                        width: '90%',
                        ajax: {
                            url: ntAjaxUrls.cmpSearch,
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error loading content: ' + textStatus);
                cteFormWrap.removeClass('running');
            }
        });
    }

    ajaxMessage = {
        error: function (_messages) {
            $.each($('body').find('.a-msg').find('.alert'), function () {
                $(this).closest('.row').remove();
            });

            if ($('body').find('.a-msg').length <= 0) {
                $('body').append('<div class="a-msg fixed-top container"></div>');
            }

            $.each(_messages, function (k, m) {
                $('body').find('.a-msg').append('<div class="row justify-content-around a-msg-wrap"><div class="col-auto"><div class="alert alert-danger pt-2 pb-2 mb-2">' + m + '<button type="button" class="btn-close"></button></div></div></div>');
            });
        },
        success: function (_messages) {
            $.each($('body').find('.a-msg').find('.alert'), function () {
                $(this).closest('.row').remove();
            });

            if ($('body').find('.a-msg').length <= 0) {
                $('body').append('<div class="a-msg fixed-top container"></div>');
            }

            $.each(_messages, function (k, m) {
                $('body').find('.a-msg').append('<div class="row justify-content-around a-msg-wrap"><div class="col-auto"><div class="alert alert-success pt-2 pb-2 mb-2">' + m + '<button type="button" class="btn-close"></button></div></div></div>');
            });
        },
        processing: function () {
            if ($('body').find('.a-msg').length <= 0) {
                $('body').append('<div class="a-msg fixed-top container"></div>');
            }

            $('body').find('.a-msg').append('<div class="row justify-content-around a-msg-wrap process-msg-wrap"><div class="col-auto"><div class="alert alert-warning pt-2 pb-2 mb-2">Processing&hellip;</div></div></div>');
        },
        removeProcessing: function () {
            $('body').find('.process-msg-wrap').remove();

            if ($('body').find('.a-msg-wrap').length <= 0) {
                $('body').find('.a-msg').remove();
            }
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            ajaxMessage.processing();
        },
        complete: function () {
            ajaxMessage.removeProcessing();
        },
    });

    timeDifference = function (startTime, timeDifference) {
        // Split the start time and time difference into components
        const [startHours, startMinutes] = startTime.split(':').map(Number);
        const [diffHours, diffMinutes] = timeDifference.split(':').map(Number);

        // Calculate the total minutes, and hours
        let endMinutes = startMinutes + diffMinutes;
        let endHours = startHours + diffHours + Math.floor(endMinutes / 60);

        // Adjust minutes to be within 0-59
        endMinutes = endMinutes % 60;
        endHours = endHours % 24; // Adjust hours to be within 0-23 (for a 24-hour clock format)

        // Format the result as hh:mm:ss
        const formattedEndTime = [
            endHours.toString().padStart(2, '0'),
            endMinutes.toString().padStart(2, '0'),
        ].join(':');

        return formattedEndTime;
    }

    reduceDifferece = function calculateStartTime(endTime, duration) {
        // Split the end time and duration into components
        const [endHours, endMinutes] = endTime.split(':').map(Number);
        const [durHours, durMinutes] = duration.split(':').map(Number);

        const endTimeInSeconds = endHours * 3600 + endMinutes * 60;
        const durationInSeconds = durHours * 3600 + durMinutes * 60;

        // Calculate the start time in seconds
        const startTimeInSeconds = endTimeInSeconds - durationInSeconds;

        // Convert the start time back to HH:MM:SS format
        let startTimeHours = Math.floor(startTimeInSeconds / 3600);
        let startTimeMinutes = Math.floor((startTimeInSeconds % 3600) / 60);
        let startTimeSeconds = Math.floor(startTimeInSeconds % 60);

        // Adjust hours, minutes, and seconds if needed
        if (startTimeHours < 0) {
            startTimeHours += 24; // For a 24-hour clock format
        }
        if (startTimeMinutes < 0) {
            startTimeMinutes += 60;
        }
        if (startTimeSeconds < 0) {
            startTimeSeconds += 60;
        }

        if (startTimeSeconds > 0) {
            startTimeMinutes += 1;
        }

        // Format the result as HH:MM:SS
        const formattedStartTime = [
            startTimeHours.toString().padStart(2, '0'),
            startTimeMinutes.toString().padStart(2, '0'),
        ].join(':');

        return formattedStartTime;
    }

    removeImage = function (_current, _doEmpty) {
        $(_current).closest('.image-area').remove();
        $(_doEmpty).val('');
    }

    clearForm = function (_form) {
        _form.form.reset();

        if ($(_form.form).find('.s-2 select').length > 0) {
            $(_form.form).find('.s-2 select').val(null).trigger('change');
        }

        if (dt) {
            dt.draw();
        }

        return false;
    }

    loadList = function (_dom, _args, drawCallback = null) {
        if (!_dom) {
            return false;
        }

        $dom = $(_dom);

        if ($dom.length < 0) {
            return false;
        }

        let args = {
            // lengthMenu: [2, 20, 30, 50, 80, 100],
            bLengthChange: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: $dom.data('action'),
                cache: false
            },
            language: dTList
        };

        args = {
            ...args,
            ..._args
        };

        dt = $dom.DataTable(args);

        if (_args.manualCallback) {
            $(document).on('submit', _args.manualCallback, function () {
                dt.draw();

                return false;
            });
        }

        $dom.on('change', '.select-all, .list-ids', function () {
            if ($(this).hasClass('select-all')) {
                if ($dom.find('.select-all').prop('checked')) {
                    $dom.find('.select-all, .list-ids').prop('checked', true);
                } else {
                    $dom.find('.select-all, .list-ids').prop('checked', false);
                }
            } else {
                if (
                    $(this).prop('checked') &&
                    $dom.find('.list-ids:checked').length == $dom.find('.list-ids').length
                ) {
                    $dom.find('.select-all').prop('checked', true);
                } else {
                    $dom.find('.select-all').prop('checked', false);
                }

                if ($dom.find('.select-all').prop('checked')) {
                    $dom.find('.l-a-f').removeClass('d-none');
                } else {
                    $dom.find('.l-a-f').addClass('d-none');
                }
            }

            if ($dom.find('.list-ids:checked').length > 0) {
                $('.l-a-f').removeClass('d-none');
            } else {
                $('.l-a-f').addClass('d-none');
            }


            if ($('.l-a-f select').hasClass('d-none')) {
                $('.l-a-f select').val('');
            }
        });

        $dom.on('draw.dt', function (e, settings) {
            $.each($dom.find('tbody > tr'), function () {
                $(this).attr('data-previndex', $(this).index());
            });

            let $mainWrap = $dom.closest('.dataTables_wrapper');
            $mainWrap.find('.select-all, .list-ids').prop('checked', false);
            $mainWrap.find('.l-a-f').addClass('d-none');

            $mainWrap.addClass('d-flex justify-content-between row');
            $mainWrap.find('.dt-button').addClass('btn btn-dark btn-sm').removeClass('dt-button');
            $mainWrap.find('.dataTables_length, .dt-buttons').addClass('col-md-6');
            $mainWrap.find('.dataTables_length > label').addClass('d-inline-flex float-end');
            $mainWrap.find('.dataTables_length > label > select').addClass('mx-2');
            $mainWrap.find('.dataTables_paginate').parent().addClass('text-end');

            let actionHtml = '';

            if (
                !$.isEmptyObject(args.bulkActions) &&
                typeof args.bulkActions.route != typeof undefined &&
                args.bulkActions.route != null &&
                !$.isEmptyObject(args.bulkActions.options)
            ) {
                let optionsHtml = '';

                $.each(args.bulkActions.options, function (k, v) {
                    optionsHtml += '<option value="' + k + '">' + v + '</option>';
                });

                actionHtml = '<div class="l-a-f p-0 d-none">\
                    <form action="#" method="POST" onsubmit="bulkUpdate(this, \'' + args.bulkActions.route + '\'); return false;">\
                        <div class="row align-items-center">\
                            <div class="col-auto my-1">\
                                <select class="form-control" required>\
                                    ' + optionsHtml + '\
                                </select>\
                            </div>\
                            <div class="col-auto my-1">\
                                <button type="submit" class="btn btn-primary">' + (typeof args.bulkActions.btnText == typeof undefined ? 'Apply' : args.bulkActions.btnText) + '</button>\
                            </div>\
                        </div>\
                    </form>\
                </div>';
            }

            if (actionHtml && $mainWrap.find('.l-a-f').length <= 0) {
                $dom.before(actionHtml);
                $dom.after(actionHtml);
            }

            if (typeof drawCallback == 'function') {
                drawCallback(e, settings);
            }
        });

        return dt;
    }

    $(document).ready(function () {
        $('.nt-form').validate();

        $(document).on('click', 'body > .a-msg .btn-close', function () {
            $(this).closest('.row').remove();

            if ($('body').find('.a-msg-wrap').length <= 0) {
                $('body').find('.a-msg').remove();
            }
        });

        $(document).on('click', '.log-time-popup', function () {
            currentLogTimeTr = $(this).closest('tr');

            // currentLogTimeTr.find('.lth-btn').removeClass('btn-warning').addClass('btn-success');

            $.ajax({
                url: $(this).data('logurl'),
                method: 'GET',
                cache: false,
                success: function (data) {
                    $('#log-time-modal .modal-content').html(data);
                    $('#log-time-modal').modal('show');
                    $("#duration").mask('00:00');
                    var duration = $('#duration').val();
                    var startTime = $('#startTime').val();

                    if (duration && startTime) {
                        var endTime = timeDifference(startTime, duration);

                        $('#endTime').val(endTime);
                    }

                    $('#startTime, #endTime').inputmask(
                        "hh:mm",
                        {
                            placeholder: "HH:MM",
                            insertMode: false,
                            showMaskOnHover: false,
                            // hourFormat: 24
                        }
                    );
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error loading content: ' + textStatus);
                }
            });

            return false;
        });

        $(document).on('click', '.bexio-customer', function () {
            $.ajax({
                url: $(this).data('logurl'),
                method: 'GET',
                cache: false,
                success: function (data) {
                    $('#log-time-modal .modal-content').html(data);
                    $('#log-time-modal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error loading content: ' + textStatus);
                }
            });
        });

        $(document).on('focusout', '#startTime', function () {
            var duration = $('#duration').val();
            var startTime = $(this).val();

            if (duration && startTime) {
                var endTime = timeDifference(startTime, duration);

                $('#endTime').val(endTime);
            }
        });

        $(document).on('focusout', '#duration', function () {
            var startTime = $('#startTime').val();
            var duration = $(this).val();

            if (duration && startTime) {
                var endTime = timeDifference(startTime, duration);

                $('#endTime').val(endTime);
            }
        });

        $(document).on('focusout', '#endTime', function () {
            var duration = $('#duration').val();
            var endTime = $(this).val();

            if (duration && endTime) {
                var startTime = reduceDifferece(endTime, duration);

                $('#startTime').val(startTime);
            }
        });


        $(document).on('click', '.show-desc', function () {
            $.ajax({
                url: $(this).data('url'),
                method: 'GET',
                cache: false,
                success: function (data) {
                    $('#logdesc-modal .modal-content').html(data);
                    $('#logdesc-modal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error loading content: ' + textStatus);
                }
            });
        });

        $(document.body).on('submit', '#log-time-modal .nt-form', function () {

            if ($(this).hasClass('running')) {
                return false;
            }

            $(this).addClass('running');
            $(this).find('.error').removeClass('error');
            $(this).find('.alert').remove();

            var $date = $(this).find('[name="nt_date"]').val().trim();
            var $duration = $(this).find('[name="nt_duration"]').val().trim();
            var $startTime = $(this).find('[name="nt_start_time"]').val().trim();
            var $endTime = $(this).find('[name="nt_end_time"]').val().trim();
            var $client = $(this).find('[name="nt_client"]').val().trim();
            var $service = $(this).find('[name="nt_service"]').val().trim();
            var $serviceDesc = $(this).find('[name="nt_service_desc"]').val().trim();
            var $projectId = $(this).find('[name="nt_project_id"]').val().trim();

            if (!$date) {
                $(this).find('[name="nt_date"]').addClass('error');
            }

            if (!$duration) {
                $(this).find('[name="nt_duration"]').addClass('error');
            }

            if (!$startTime) {
                $(this).find('[name="nt_start_time"]').addClass('error');
            }

            if (!$endTime) {
                $(this).find('[name="nt_end_time"]').addClass('error');
            }

            if (!$client) {
                $(this).find('[name="nt_client"]').addClass('error');
            }

            if (!$service) {
                $(this).find('[name="nt_service"]').addClass('error');
            }

            if (!$serviceDesc) {
                $(this).find('[name="nt_service_desc"]').addClass('error');
            }

            if ($(this).find('.error').length > 0) {
                $(this).removeClass('running');

                return false;
            }

            $(this).find('[name="client_name"]').val($(this).find('[name="nt_client"] option:selected').text());
            $(this).find('[name="service_name"]').val($(this).find('[name="nt_service"] option:selected').text());
            $(this).find('[name="project_name"]').val($(this).find('[name="nt_project_id"] option:selected').text());

            var $frm = $(this);

            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'POST',
                success: function (response) {
                    ajaxMessage.removeProcessing();
                    if (response.error) {
                        $frm.prepend('<div class="alert alert-danger">' + response.error.message + '</div>');
                    } else {
                        currentLogTimeTr.find('.lth-btn').removeClass('btn-warning').addClass('btn-success');
                        currentLogTimeTr = null;

                        $frm.prepend('<div class="alert alert-success">Your time entry has been logged</div>');
                        $frm[0].reset();

                        setTimeout(function () {
                            $('#log-time-modal').modal('hide');
                        }, 2000);
                    }
                },
                error: function () {
                    ajaxMessage.removeProcessing();
                    $frm.removeClass('running');
                },
                complete: function () {
                    ajaxMessage.removeProcessing();
                    $frm.removeClass('running');
                },
            });

            return false;
        });

        $(document.body).on('submit', '#log-time-modal .bc-form', function () {

            if ($(this).hasClass('running')) {
                return false;
            }

            $(this).addClass('running');
            $(this).find('.border-danger').removeClass('border-danger');
            $(this).find('.alert').remove();

            var $name = $(this).find('[name="nt_name_1"]').val().trim();
            var $name2 = $(this).find('[name="nt_name_2"]').val().trim();
            var $phone = $(this).find('[name="nt_phone"]').val().trim();
            var $mobile = $(this).find('[name="nt_mobile"]').val().trim();
            var $fax = $(this).find('[name="nt_fax"]').val().trim();

            if (!$name) {
                $(this).find('[name="nt_name_1"]').addClass('border-danger');
            }

            if (!$name2) {
                $(this).find('[name="nt_name_2"]').addClass('border-danger');
            }

            if (!$phone && !$mobile && !$fax) {
                $(this).find('[name="nt_phone"], [name="nt_mobile"], [name="nt_fax"]').addClass('border-danger');
            }

            if ($(this).find('.border-danger').length > 0) {
                $(this).removeClass('running');

                return false;
            }

            var $frm = $(this);

            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'POST',
                success: function (response) {
                    ajaxMessage.removeProcessing();
                    if (response.error) {
                        $frm.prepend('<div class="alert alert-danger">' + response.error.message + '</div>');
                    } else {
                        $frm.prepend('<div class="alert alert-success">' + response.success.message + '</div>');
                        $frm[0].reset();

                        setTimeout(function () {
                            $('#log-time-modal').modal('hide');
                        }, 2000);
                    }
                },
                error: function () {
                    ajaxMessage.removeProcessing();
                    $frm.removeClass('running');
                },
                complete: function () {
                    ajaxMessage.removeProcessing();
                    $frm.removeClass('running');
                },
            });

            return false;
        });

        $(document).on('change', '.contact-type', function () {
            var contactType = $('.contact-type:checked').val();

            $('.ct-flds').addClass('d-none');
            $('.ct-' + contactType + '-fld').removeClass('d-none');
            $("#linked_account").val(0);
            $('.cte-form').html('');
            $('.link_contact').removeClass('d-none');
            $('.cancel_link_contact').addClass('d-none');

            if (empChSelect2) {
                empChSelect2.select2('destroy');
                empChSelect2 = null;
            }

            if (cmpSelect2) {
                cmpSelect2.select2('destroy');
                cmpSelect2 = null;
            }

            if (cmpChSelect2) {
                cmpChSelect2.select2('destroy');
                cmpChSelect2 = null;
            }
        });

        $(document).on('click', '.link_contact', function () {
            var cteFormWrap = $(this).closest('.ct-flds').find('.cte-form');

            if (cteFormWrap.hasClass('running')) {
                return false;
            }

            cteFormWrap.addClass('running');

            $('.cte-form').html('');
            $('.link_contact, .cancel_link_contact').addClass('d-none');
            $(this).closest('.ct-flds').find('.cancel_link_contact').removeClass('d-none');
            $("#linked_account").val(1);

            $.ajax({
                url: cteFormWrap.data('url'),
                method: 'GET',
                cache: false,
                success: function (data) {
                    cteFormWrap.removeClass('running');
                    cteFormWrap.html(data);

                    if (cteFormWrap.find('#search-ch-emp').length > 0) {
                        empChSelect2 = $('#search-ch-emp').select2({
                            placeholder: 'Search for a contact',
                            ajax: {
                                url: ntAjaxUrls.empSearchCh,
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data.map(function (item) {
                                            return {
                                                id: item.name,
                                                text: item.name,
                                                additionalData: item
                                            };
                                        })
                                    };
                                }
                            }
                        });
                    }

                    if (cteFormWrap.find('#search-ch-cmp').length > 0) {
                        cmpChSelect2 = $('#search-ch-cmp').select2({
                            placeholder: 'Search for a contact',
                            ajax: {
                                url: ntAjaxUrls.empSearchCh,
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data.map(function (item) {
                                            return {
                                                id: item.name,
                                                text: item.name,
                                                additionalData: item
                                            };
                                        })
                                    };
                                }
                            }
                        });
                    }

                    if (cteFormWrap.find('#companyDropdown').length > 0) {
                        cmpSelect2 = $('#companyDropdown').select2({
                            width: '90%',
                            ajax: {
                                url: ntAjaxUrls.cmpSearch,
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error loading content: ' + textStatus);
                    cteFormWrap.removeClass('running');
                }
            });
        });

        $(document).on('click', '.cancel_link_contact', function () {
            $('.cte-form').html('');
            $('.link_contact, .cancel_link_contact').addClass('d-none');
            $(this).closest('.ct-flds').find('.link_contact').removeClass('d-none');

            $("#linked_account").val(0);

            if (empChSelect2) {
                empChSelect2.select2('destroy');
                empChSelect2 = null;
            }

            if (cmpSelect2) {
                cmpSelect2.select2('destroy');
                cmpSelect2 = null;
            }

            if (cmpChSelect2) {
                cmpChSelect2.select2('destroy');
                cmpChSelect2 = null;
            }
        });

        $(document).on('change', '#client', function () {
            let customerId = $(this).val();
            let $frm = $('.nt-form');

            if (customerXhr) {
                $frm.removeClass('running');
                customerXhr.abort();
            }

            $frm.addClass('running');

            customerXhr = $.ajax({
                url: $(this).data('url'),
                data: {
                    customer_id: customerId,
                },
                method: 'GET',
                success: function (response) {
                    customerXhr = null;
                    ajaxMessage.removeProcessing();
                    $('#project-id').html(response.html);
                },
                error: function () {
                    customerXhr = null;
                    ajaxMessage.removeProcessing();
                },
                complete: function () {
                    customerXhr = null;
                    ajaxMessage.removeProcessing();
                    $frm.removeClass('running');
                },
            });
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
})(jQuery);