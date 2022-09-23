<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $metaTitle ?? config('app.name', 'StatybuIS') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" ; />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" ; />

    <style>
        

        .navbarCustom {
            margin-top: 15rem;
        }
    </style>
</head>

<body>
    <div id="app">

        <section class="navbarCustom">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
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
                                    <a class="nav-link {{ Request::is('/') ? 'active bg-primary':''}}" aria-current="page" href="{{ url('/') }}">Pradžia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('susisiekti') ? 'active bg-primary':''}}" href="{{ route('main.landingPage.contact') }}">Kontaktai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('apie-mus') ? 'active bg-primary':''}}" href="{{ route('main.landingPage.about') }}">Apie mus</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('user') ? 'active bg-primary':''}}" href="{{ route('user.index') }}">Skelbimai</a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('/') ? 'active':''}}" aria-current="page" href="{{ url('/') }}">Pradžia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('susisiekti') ? 'active':''}}" href="{{ route('main.landingPage.contact') }}">Kontaktai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('apie-mus') ? 'active':''}}" href="{{ route('main.landingPage.about') }}">Apie mus</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('svečias/skelbimai', 'svečias/*') ? 'active':''}}" href="{{ route('main.guest.listings') }}">Skelbimai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('register') ? 'active':''}}" href="{{ route('register') }}">Registruotis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('login') ? 'active':''}}" href="{{ route('login') }}">Prisijungti</a>
                                </li>
                            </ul>
                        </div>
                    @endauth
            </div>
            @endif
            </div>
        </nav>
    </section>
        

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
