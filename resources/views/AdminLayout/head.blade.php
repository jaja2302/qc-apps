<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>QC Panel </title>
     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/CBI-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/testing.css') }}">

<style>
        @media (max-width: 767px) {
            .login-box {
                width: 80%;
                max-width: 400px;
                padding: 40px 30px;
            }
        }
    </style>
<body>

    <div class="container-lg">
        @yield('content')




    </div>
    {{-- <footer class="main-footer">
        <strong>Copyright © 2021-2026 <a href="https://srs-ssms.com">SRS-SSMS.COM</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.5
        </div>
    </footer> --}}
    @stack('scripts')
    <script src="{{ asset('js/js_tabel/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('js/js_tabel/jquery.dataTables.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</body>

</html>