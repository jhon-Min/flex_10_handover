<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
    <!-- ======================= GLOBAL VENDOR STYLES ========================-->
    <link rel="stylesheet" href="{{asset('assets/css/vendor/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/metismenu/dist/metisMenu.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/switchery-npm/index.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    <!-- ======================= LINE AWESOME ICONS ===========================-->
    <link rel="stylesheet" href="{{asset('assets/css/icons/line-awesome.min.css')}}">
    <!-- ======================= DRIP ICONS ===================================-->
    <link rel="stylesheet" href="{{asset('assets/css/icons/dripicons.min.css')}}">
    <!-- ======================= MATERIAL DESIGN ICONIC FONTS =================-->
    <link rel="stylesheet" href="{{asset('assets/css/icons/material-design-iconic-font.min.css')}}">
    <!-- ======================= GLOBAL COMMON STYLES ============================-->
    <link rel="stylesheet" href="{{asset('assets/css/common/main.bundle.css')}}">
    <!-- ======================= LAYOUT TYPE ===========================-->
    <link rel="stylesheet" href="{{asset('assets/css/layouts/vertical/core/main.css')}}">
    <!-- ======================= MENU TYPE ===========================================-->
    <link rel="stylesheet" href="{{asset('assets/css/layouts/vertical/menu-type/default.css')}}">
    <!-- ======================= THEME COLOR STYLES ===========================-->
    <link rel="stylesheet" href="{{asset('assets/css/layouts/vertical/themes/theme-o.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/toastr.min.css')}}">
</head>

<body>
    @yield('content')
    <!-- ================== GLOBAL VENDOR SCRIPTS ==================-->
    <script src="{{asset('assets/vendor/modernizr/modernizr.custom.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/js-storage/js.storage.js')}}"></script>
    <script src="{{asset('assets/vendor/js-cookie/src/js.cookie.js')}}"></script>
    <script src="{{asset('assets/vendor/pace/pace.js')}}"></script>
    <script src="{{asset('assets/vendor/metismenu/dist/metisMenu.js')}}"></script>
    <script src="{{asset('assets/vendor/switchery-npm/index.js')}}"></script>
    <script src="{{asset('assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- ================== GLOBAL APP SCRIPTS ==================-->
    <script src="{{asset('assets/js/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/global/app.js')}}"></script>
    <script>
        @if(session('status'))
        toastr.success(" {{ session('status') }}");
        @endif
        @if(Session::has('message'))
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
    </script>
</body>

</html>