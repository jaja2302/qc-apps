<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@latest"></script>

    <script src="{{asset('apexchart/dist/apexcharts.js')}}"></script>
    <script src="{{asset('leaflet/leaflet.js')}}"></script>\
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script> -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>



<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="hover"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link">Selamat datang, {{ session('user_name') }} </a>
                </li>
            </ul>

        </nav>
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <a href="{{ asset('rekap') }}" class="brand-link">
                <img src="{{ asset('img/CBI-removebg-preview.png') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Dashboard</span>
            </a>
            <div class="sidebar">
                <nav class="" style="height: 100%">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="height: 100%">
                        <!-- USER LAB -->

                        <!-- Include Lottie library -->

                        <style>
                            .lottie-animation {
                                width: 40px;
                                /* adjust the width as per your preference */
                                height: 40px;
                                /* adjust the height as per your preference */
                                margin-right: 8px;
                                /* add some spacing between the icon and the text */
                                display: inline-block;
                                /* make the icon and the text appear on the same line */
                                vertical-align: middle;
                                /* align the icon vertically with the text */
                            }

                            .nav-link p {
                                display: inline-block;
                                vertical-align: middle;
                            }
                        </style>
                        <li class="nav-item">
                            <a href="{{ asset('/rekap') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="{{ asset('img/ALLREKAP.json') }}"></div>
                                <p>ALL SKOR PANEN </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ asset('/dashboard_inspeksi') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="https://assets10.lottiefiles.com/packages/lf20_w4hwxwuq.json"></div>
                                <p>PANEN REGULAR</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <!-- uses solid style -->
                            <a href="{{ asset('/dashboardtph') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="https://assets10.lottiefiles.com/packages/lf20_Lpuvp7YT5K.json">
                                </div>

                                <p>
                                    SIDAK MUTU TRANSPORT
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ asset('/dashboard_mutubuah') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="https://assets1.lottiefiles.com/packages/lf20_bENSfZ37DY.json">
                                </div>

                                <p>
                                    SIDAK MUTU BUAH

                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ asset('/dashboard_gudang') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="https://assets9.lottiefiles.com/temp/lf20_vBnbOW.json"></div>
                                <p>
                                    GUDANG

                                </p>
                            </a>
                        </li>





                        <li class="nav-item">
                            <a href="{{ asset('/dashboard_perum') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="{{ asset('img/homejson.json') }}">
                                </div>

                                <p>
                                    PERUMAHAN

                                </p>
                            </a>
                        </li>
                        @if (strpos(session('departemen'), 'QC') !== false)
                        <li class="nav-item">
                            <a href="{{ asset('/dashboardabsensi') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="https://lottie.host/237bc051-94b1-45d6-89da-3144341616a8/i4uJsopUfQ.json"></div>
                                <p>Absensi QC</p>
                            </a>
                        </li>
                        @endif

                        @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten')
                        <li class="nav-item">
                            <a href="{{ asset('/userqcpanel') }}" class="nav-link">
                                <div class="nav-icon lottie-animation" data-animation-path="{{ asset('img/homejson.json') }}"></div>
                                <p>Management User QC</p>
                            </a>
                        </li>
                        @endif



                        <div class="fixed-bottom mb-3" style="position: absolute;">

                            @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten')
                            <li class="nav-item">
                                <a href="{{ route('user.show') }}" class="nav-link">

                                    <div class="nav-icon lottie-animation" data-animation-path="https://assets9.lottiefiles.com/packages/lf20_8y92hieq.json">
                                    </div>
                                    <p>
                                        User QC
                                    </p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="nav-icon fa fa-sign-out-alt"></i>
                                    <p>
                                        Logout
                                    </p>
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>

                    </ul>
                </nav>


            </div>
        </aside>

        <div class="content-wrapper">
            <div class="container-fluid px-4 py-8">
                {{$slot}}
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright © 2021-2026 <a href="https://srs-ssms.com">SRS-SSMS.COM</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 4.0.1
            </div>
        </footer>


        <!-- Add the necessary JavaScript files for the AdminLTE template -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/adminlte.min.js') }}"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- Other JavaScript files -->
        <!-- DataTables CSS -->



        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                var currentUrl = window.location.href;
                var navLinks = document.querySelectorAll('.nav-link');

                // Define the mapping of URL segments to classes
                var urlClassMapping = {
                    '/dashboard_inspeksi': 'bg-warning',
                    '/dashboardtph': 'bg-warning',
                    '/dashboard_mutubuah': 'bg-warning',
                    '/dashboard_gudang': 'bg-primary',
                    '/dashboard_perum': 'bg-success'
                };

                // Default class if no match is found
                var defaultClass = 'bg-light';

                navLinks.forEach(function(link) {
                    // Check if any URL segment matches the current URL
                    var found = Object.keys(urlClassMapping).find(function(segment) {
                        return currentUrl.endsWith(segment) && link.href.endsWith(segment);
                    });

                    // Assign the class based on the match or default class
                    link.classList.add(found ? urlClassMapping[found] : defaultClass);
                });


            });
            document.addEventListener('DOMContentLoaded', function() {
                var lottieElements = document.querySelectorAll('.lottie-animation');
                lottieElements.forEach(function(element) {
                    var animationPath = element.getAttribute('data-animation-path');
                    lottie.loadAnimation({
                        container: element,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: animationPath
                    });
                });
            });
        </script>

</body>

</html>