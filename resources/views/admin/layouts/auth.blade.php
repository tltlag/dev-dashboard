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

    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/lineicons.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/materialdesignicons.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/fullcalendar.css')}}" />
    <link rel="stylesheet" href="{{ asset('backend-assets/css/auth.css')}}" />
    <link rel="stylesheet" href="{{ asset('dashboard-assets/css/main.css?v='.time())}}" />
    @stack('head_styles')

    @stack('head_scripts')
  </head>
<body id="@yield('body_id', 'page-top')" class="@yield('body_class', auth('admin')->user()->theme??'')">
    <!-- ======== Preloader =========== -->
    <div id="preloader">
      <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->

    @section('body_start')

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper" style="margin-left: 0; margin-top: 50px;">
        @yield('content')
    </main>
    <!-- ======== main-wrapper end =========== -->

    @section('body_end')

    @stack('footer_styles')

    <!-- ========= All Javascript files linkup ======== -->
    <script src="{{ asset('common-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('common-assets/js/jquery.validation-additional-methods.min.js') }}"></script>

    <script src="{{ asset('dashboard-assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/Chart.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/dynamic-pie-chart.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/fullcalendar.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/jvectormap.min.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/world-merc.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/polyfill.js')}}"></script>
    <script src="{{ asset('dashboard-assets/js/main.js')}}"></script>

    <script src="{{ asset('backend-assets/js/auth.js') }}"></script>

    @stack('footer_scripts')

  </body>
</html>