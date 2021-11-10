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
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                <div id="kt_header" style="" class="header align-items-stretch">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                            <a href="{{ route('home') }}">
                                <img alt="Logo"
                                    src="https://petanimilenial.jabarprov.go.id/_nuxt/img/disbun.3540c8d.png"
                                    class="h-20px h-lg-30px" />
                            </a>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Container-->
                </div>


                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container">
                            @yield('content')
                        </div>
                    </div>
                </div>

                @include('layouts.partial._footer')

            </div>
        </div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.5" x="11" y="10" width="2" height="10" rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
        </span>
    </div>

    @yield('modals')

</body>

<!-- Scripts -->
<script src="{{ asset('themes/metronic/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('themes/metronic/js/script.js') }}"></script>

@stack('scripts')

@include('layouts.flash_message')

</html>
