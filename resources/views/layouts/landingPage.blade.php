<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pradinis puslapis</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" ; />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" ; />

    <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    <style>
        a {
            color: #000;
        }

    </style>
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" ; />

</head>

<body style="background-color: #F0F2F5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo-resize.png') }}"
                    class="img-fluid fit-image w-100" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            @if (Route::has('login'))
                @auth
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Pradžia</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main.landingPage.contact') }}">Kontaktai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main.landingPage.about') }}">Apie mus</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.index') }}">Skelbimai</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Pradžia</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main.landingPage.contact') }}">Kontaktai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main.landingPage.about') }}">Apie mus</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('main.guest.listings') }}">Skelbimai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registruotis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Prisijungti</a>
                            </li>
                            </li>
                        </ul>
                    </div>
                @endauth
        </div>
        @endif
        </div>
    </nav>

<main>
    @yield('content')
</main>

@include('layouts.landing.footer') 

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script>
<script src="{{ asset('assets/js/accordion.js') }}"></script>

</body>

</html>
