<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', config('app.name', 'Laravel'))</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/metismenu/dist/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/switchery-npm/index.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') }}">
    <!-- ======================= LINE AWESOME ICONS ===========================-->
    <link rel="stylesheet" href="{{ asset('assets/css/icons/line-awesome.min.css') }}">
    <!-- ======================= DRIP ICONS ===================================-->
    <link rel="stylesheet" href="{{ asset('assets/css/icons/dripicons.min.css') }}">
    <!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
    <link rel="stylesheet" href="{{ asset('assets/css/icons/material-design-iconic-font.min.css') }}">
    <!-- ======================= GLOBAL COMMON STYLES ============================-->
    <link rel="stylesheet" href="{{ asset('assets/css/common/main.bundle.css') }}">
    <!-- ======================= LAYOUT TYPE ===========================-->
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/vertical/core/main.css') }}">
    <!-- ======================= MENU TYPE ===========================================-->
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/vertical/menu-type/default.css') }}">
    <!-- ======================= THEME COLOR STYLES ===========================-->
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/vertical/themes/theme-o.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    @stack('custom-css')
</head>

<body>
    <div id="app">
        <aside class="sidebar sidebar-left">
            @auth
                @include('layouts.sidebar_nav')
            @endauth
        </aside>
        <div class="content-wrapper">
            @auth
                @include('layouts.top_navbar')
            @endauth
            @yield('content')
        </div>
        <!-- ================== GLOBAL VENDOR SCRIPTS ==================-->
        <script src="{{ asset('assets/vendor/modernizr/modernizr.custom.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/js-storage/js.storage.js') }}"></script>
        <script src="{{ asset('assets/vendor/js-cookie/src/js.cookie.js') }}"></script>
        <script src="{{ asset('assets/vendor/pace/pace.js') }}"></script>
        <script src="{{ asset('assets/vendor/metismenu/dist/metisMenu.js') }}"></script>
        <script src="{{ asset('assets/vendor/switchery-npm/index.js') }}"></script>
        <script src="{{ asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}">
        </script>
        <!-- ================== PAGE LEVEL VENDOR SCRIPTS ==================-->
        <script src="{{ asset('assets/vendor/countup.js/dist/countUp.min.js') }}"></script>
        <script src="{{ asset('assets/js/components/countUp-init.js') }}"></script>
        <script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
        <!-- ================== GLOBAL APP SCRIPTS ==================-->
        <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
        <script src="{{ asset('assets/js/global/app.js') }}"></script>
        <script src="{{ asset('assets/js/common.js') }}"></script>
        <script src="{{ asset('assets/js/custom/users.js') }}"></script>
        <script src="{{ asset('assets/js/confirmation-sweetalert.js') }}"></script>
        <script src="{{ asset('assets/vendor/ckeditor/ckeditor.js') }}"></script>
        @stack('custom-scripts')
        <script>
            @if (Session::has('message'))
                var type = "{{ Session::get('alert-type', 'info') }}";
                switch (type) {
                    case 'info':
                        toastr.info("{{ Session::get('message') }}");
                        break;

                    case 'warning':
                        toastr.warning("{{ Session::get('message') }}");
                        break;

                    case 'success':
                        toastr.success("{{ Session::get('message') }}");
                        break;

                    case 'error':
                        toastr.error("{{ Session::get('message') }}");
                        break;
                }
            @endif
            $(function() {
                $('.bs4-table').DataTable({
                    "order": []
                });
            });
        </script>
</body>

</html>
