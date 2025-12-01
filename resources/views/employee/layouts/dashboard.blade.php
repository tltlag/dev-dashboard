<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title', __('Dashboard')) - {{ config('global.SITE_TITLE', '') }}</title>

    <!-- favicon -->
    <link rel="apple-touch-icon" href="{{ asset('/favicon.svg')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/favicon.svg')}}">

    <!-- font-awesome css -->
    <link href="{{ asset('common-assets/css/font-awasome-all.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- ========== All CSS files linkup ========= -->
    <link href="{{ asset('common-assets/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/lineicons.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/materialdesignicons.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/fullcalendar.css')}}" />
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/common.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/main.css?v='.time())}}" />
    @stack('head_styles')

    <script type="text/javascript">
    const smsProps = {
        home_url: '{{route("home")}}',
    }

    const dTList = {
        "decimal": "",
        "emptyTable": "{{__('No data available in table') }}",
        "info": "{{__('Showing _START_ to _END_ of _TOTAL_ entries') }}",
        "infoEmpty": "{{__('Showing 0 to 0 of 0 entries') }}",
        "infoFiltered": "({{__('filtered from _MAX_ total entries') }})",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "{{__('Show _MENU_ entries') }}",
        "loadingRecords": "{{__('Loading...') }}",
        "processing": "",
        "search": "{{__('Search') }}:",
        "zeroRecords": "{{__('No matching records found') }}",
        "paginate": {
            "first": "{{__('First') }}",
            "last": "{{__('Last') }}",
            "next": "{{__('Next') }}",
            "previous": "{{__('Previous') }}"
        },
        "aria": {
            "orderable": "{{__('Order by this column') }}",
            "orderableReverse": "{{__('Reverse order this column') }}"
        }
    };

    const ntAjaxUrls = {
        "empSearchCh": "{{route('employee.contact.searchch')}}",
        "cmpSearch": "{{route('employee.contact.getCompanies')}}",
    };
    </script>
    
    @include('common.theme-appear')
    @stack('head_scripts')
  </head>
 
<body id="@yield('body_id', 'page-top')" class="@yield('body_class', auth('employee')->user()->theme??'')">
    <!-- ======== Preloader =========== -->
    <div id="preloader">
      <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->

    @section('body_start')

    @include('employee.layouts.common.side_menu')

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        @include('employee.layouts.common.dashboard_header')
        @include('employee.layouts.common.messages')

        <!-- ========== table components start ========== -->
        @yield('content')
    </main>
    <!-- ======== main-wrapper end =========== -->
    @if (config('global.SITE_FOOTER_LOGO'))
        <footer class="mt-10" style="    padding-bottom: 50px;">
            <div class="text-center">{{ config('global.SITE_FOOTER_SLOGAN', '') }}
                <a href="{{route('employee.dashboard')}}">
                    <img src="{{ route('images.show', basename(config('global.SITE_FOOTER_LOGO'))) }}"
                        alt="{{ config('global.SITE_TITLE', '') }}" title="{{ config('global.SITE_TITLE', '') }}"
                        width="200px" />
                </a>
            </div>
        </footer>
        @endif

    @section('body_end')

    @stack('footer_styles')

    <!-- ========= All Javascript files linkup ======== -->
    <script src="{{ asset('common-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.inputmask.bundle.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.validation-additional-methods.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('common-assets/bootstrap/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('common-assets/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/datatables.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.bulma.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.dataTables.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.foundation.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.jqueryui.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/buttons.semanticui.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('common-assets/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('common-assets/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.mask.js')}}"></script>

    <script src="{{ asset('dashboard-assets/js/Chart.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/dynamic-pie-chart.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/fullcalendar.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/jvectormap.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/world-merc.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/polyfill.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/main.js')}}"></script>

    <script src="{{ asset('frontend-assets/js/common.js') }}"></script>

    @stack('footer_scripts')
 
  </body>
</html>