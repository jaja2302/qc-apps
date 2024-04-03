<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Login QC Apps') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style type="text/css">
        i {
            font-size: 50px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <!-- Login 13 - Bootstrap Brain Component -->
    <section class="bg-light py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-2 p-xl-5">
                            <div class="text-center mb-3">
                                <a href="#!">
                                    <img src="{{asset('img/Logo-SSS.png')}}" alt="BootstrapBrain Logo" width="300" height="150">
                                </a>
                            </div>
                            <div class="text-center mt-4">
                                <p class="text-secondary text-center" style="margin:0 0 30px;
                                    font-style: normal;
                                    font-size: 14px;
                                    font-family: Arial, Helvetica, sans-serif;
                                    font-weight: 600 ; color: #1e1e1f">
                                    Silakan masukkan Nama atau Email dan Password yang ada miliki untuk mengakses portal <span style="color: #4CAF50">QC Apps SRS</span>
                                </p>
                            </div>
                            <form class="text-center mt-4" action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="email" required id="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                            @error('error')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                            @error('error')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" id="loginButton" value="Submit" type="submit">Submit</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <a href="#!" class="flex items-center">
                                            <img src="{{asset('img/LOGO-SRS.png')}}" alt="BootstrapBrain Logo" width="50" height="30">
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>