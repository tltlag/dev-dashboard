let dt = null;
let ajaxMessage = [];

(function ($) {
    $.datetimepicker.setLocale('en');

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

    loadCountries = function (callback) {
        $.get(smsProps.country_url, function (data, status) {
            if (callback instanceof Function) {
                callBack(data, status);
            }
        });
    }

    loadStates = function (_appendToObj = null, _currentObj = 0, _selectedState = null, callback) {
        let $currentObj = $(_currentObj);

        if ($currentObj.length <= 0) {
            return false;
        }

        let id = $currentObj.val();

        $.get(smsProps.country_state_url.replace('~~', id), function (data, status) {
            if (callback instanceof Function) {
                callBack(data, status);
            } else {
                $appendToObj = $(_appendToObj);
                let _firstOptionText = "Select State";

                if ($appendToObj.length > 0) {
                    let _title = $appendToObj.attr('title');

                    if (_title) {
                        _firstOptionText = _title;
                    }

                    let optionHtml = '<option value="">' + _firstOptionText + '</option>';

                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (sid, sname) {
                            optionHtml += '<option value="' + sid + '"' + (_selectedState && _selectedState == sid ? ' selected' : '') + '>' + sname + '</option>';
                        });
                    }

                    $appendToObj.html(optionHtml);
                }
            }
        });
    }

    loadList = function (_dom, _args) {
        if (!_dom) {
            return false;
        }

        $dom = $(_dom);

        if ($dom.length < 0) {
            return false;
        }

        let args = {
            lengthMenu: [20, 30, 50, 80, 100],
            processing: true,
            serverSide: true,
            dom: 'Blfrtip',
            buttons: true,
            ajax: {
                url: $dom.data('action'),
                cache: false
            }
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

        $dom.on('draw.dt', function () {
            $.each($dom.find('tbody > tr'), function () {
                $(this).attr('data-previndex', $(this).index());
            });

            let $mainWrap = $dom.closest('.dataTables_wrapper');
            $mainWrap.find('.select-all, .list-ids').prop('checked', false);
            $mainWrap.find('.l-a-f').addClass('d-none');

            $mainWrap.addClass('d-flex justify-content-between row');
            $mainWrap.find('.dt-button').addClass('btn btn-dark btn-sm').removeClass('dt-button');
            $mainWrap.find('.dataTables_length, .dt-buttons, .dataTables_info, .dataTables_paginate').addClass('col-md-6');
            $mainWrap.find('.dataTables_length > label').addClass('d-inline-flex float-end');
            $mainWrap.find('.dataTables_length > label > select').addClass('mx-2');
            $mainWrap.find('.dataTables_paginate .pagination').addClass('float-end');

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

                actionHtml = '<div class="l-a-f col-md-12 d-none">\
                    <form action="#" method="POST" onsubmit="bulkUpdate(this, \'' + args.bulkActions.route + '\'); return false;">\
                        <div class="form-row align-items-center">\
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
        });

        return dt;
    }

    bulkUpdate = function (_current, _route) {
        let ids = new Array;

        $.each($(_current).closest('.dataTables_wrapper').find('.list-ids:checked'), function () {
            ids.push($(this).val());
        });

        let status = $(_current).find('select').val();

        $.ajax({
            url: _route,
            data: { status: status, ids: ids },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (!data.status) {
                    ajaxMessage.error([data.message]);
                } else {
                    ajaxMessage.success([data.message]);

                    if (dt) {
                        dt.draw();
                    }
                }
            },
            error: function (xhr, status, error) {
                ajaxMessage.error(['Unable to proceed with your request, please try again later.']);
            }
        });

        return false;
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

    updateStatus = function (_current, _route, _statusActive, _statusInActive) {
        if (
            typeof _current == typeof undefined ||
            typeof _route == typeof undefined ||
            typeof _statusActive == typeof undefined ||
            typeof _statusInActive == typeof undefined
        ) {
            return false;
        }

        let $obj = $(_current);

        if ($obj.length <= 0) {
            return false;
        }

        if ($obj.prop('checked')) {
            status = _statusActive;
        } else {
            status = _statusInActive;
        }

        $.ajax({
            url: _route,
            data: { status: status },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (!data.status) {
                    if (!$obj.prop('checked')) {
                        $obj.prop('checked', true);
                    } else {
                        $obj.prop('checked', false);
                    }

                    ajaxMessage.error([data.message]);
                } else if ($obj.parent().find('.s-txt').length > 0 && data.status_text) {
                    $obj.parent().find('.s-txt').text(data.status_text);
                    ajaxMessage.success([data.message]);
                }
            },
            error: function (xhr, status, error) {
                if (!$obj.prop('checked')) {
                    $obj.prop('checked', true);
                } else {
                    $obj.prop('checked', false);
                }

                ajaxMessage.error(['Unable to update status.']);
            }
        });

        return true;
    }

    removeImage = function (_current, _doEmpty) {
        $(_current).closest('.img-area').remove();
        $(_doEmpty).val('');
    }

    unloadSelect = function (_current) {
        if ($(_current).hasClass('select2-hidden-accessible')) {
            // $(_current).select2('destroy');
            $(_current).val(null).trigger('change');
        }
    }

    loadSelect = function (_current, _route, _args = null, _callback) {
        let args = {
            placeholder: "Search with keywords",
            allowClear: true,
            ajax: {
                url: _route,
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        }
                    };
                }
            }
        };

        if (_args !== null) {
            args = {
                ...args,
                ..._args
            };
        }

        $(_current).select2(args);

        if (_callback instanceof Function) {
            _callback(args, $);
        }
    }

    orderable = function (_current, _route) {
        $(_current).sortable({
            items: "tr",
            cursor: "move",
            handle: ".sorting_1",
            opacity: 0.6,
            update: function (e, ui) {
                let ids = new Array;

                $.each(ui.item.parent().find('tr'), function () {
                    let _obj = $(this).find('input[type="checkbox"]');

                    if (_obj.length <= 0) {
                        return false;
                    }

                    let id = _obj.attr('value');

                    if (typeof id != undefined && id > 0) {
                        ids.push(id);
                    }
                });

                if (ids.length <= 0) {
                    return false;
                }

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: _route,
                    data: { ids: ids },
                    success: function (data) {
                        if (!data.status) {
                            ajaxMessage.error([data.message]);
                        } else {
                            ajaxMessage.success([data.message]);

                            if (dt) {
                                dt.draw('page');
                            }
                        }
                    }
                });
            }
        });
    }

    loadDateTimPicker = function (_target) {
        $(_target).datetimepicker({
            format: 'Y-m-d h:00:00'
        });
    }

    initDragAndResize = function (_obj, pageId, bookId, _route) {
        $(_obj).draggable({
            drag: function () {
                var offset = $(this).position();
                var xPos = offset.left;
                var yPos = offset.top;
                var l = (100 * parseFloat(xPos / parseFloat($(this).parent().width()))) + "%";
                var t = (100 * parseFloat(yPos / parseFloat($(this).parent().height()))) + "%";
                $(this).find('.leftmargin').val(l);
                $(this).find('.topmargin').val(t);
            }
        });

        $(_obj).resizable({
            stop: function (event, ui) {
                var parent = ui.element.parent();
                var w = ui.size.width / parent.width() * 100 + "%";
                var h = ui.size.height / parent.width() * 100 + "%";

                $(this).find('.width').val(w);
                $(this).find('.height').val(h);
            }
        });

        $(_obj).click(function () {
            $(this).find(".tooltip").show();
        });

        loadSelect(_obj + " .lib-select", null, {
            ajax: {
                url: _route,
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        }
                    };
                },
                data: function (params) {
                    params['type'] = $(_obj + " .r-select").val();
                    params['book_id'] = bookId;
                    params['type_with_book_id_mendatory'] = true;

                    return params;
                }
            }
        });

        $(_obj + " .r-select").click(function () {
            unloadSelect(_obj + " .s-2 select");
        });
    }

    $(document).ready(function () {
        $('.nt-form').validate();

        $(document).on('click', 'body > .a-msg .btn-close', function () {
            $(this).closest('.row').remove();

            if ($('body').find('.a-msg-wrap').length <= 0) {
                $('body').find('.a-msg').remove();
            }
        });

        $('#youtubeModal').on('hidden.bs.modal', function () {
            $('.yt-form')[0].reset();
            $('#youtube-library').html('').removeAttr('style');
        });
    });
})(jQuery);