<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="http://disbun.jabarprov.go.id/tmplts/disbun/assets/images/favicon.ico" />

    <title>@yield('meta_title') - {{ config('app.name', 'Sinanas') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/metronic/plugins/global/plugins.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/metronic/css/style.bundle.css') }}">
    {!! ReCaptcha::htmlScriptTagJsApi() !!}

    @stack('styles')


</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

    @yield('content')

</body>

<!-- Scripts -->
<script src="{{ asset('themes/metronic/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/js/script.js') }}"></script>

@stack('scripts')

@include('layouts.flash_message')

</html>
