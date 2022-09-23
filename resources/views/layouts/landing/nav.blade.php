<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/"><img src="{{ asset('images/logo-resize.png') }}"
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
                            <a class="nav-link {{ Request::is('/') ? 'active':''}}" aria-current="page" href="{{ url('/') }}">Pradžia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('susisiekti') ? 'active':''}}" href="{{ route('main.landingPage.contact') }}">Kontaktai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('apie-mus') ? 'active':''}}" href="{{ route('main.landingPage.about') }}">Apie mus</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user') ? 'active':''}}" href="{{ route('user.index') }}">Skelbimai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user') ? 'active':''}}" href="{{ url()->previous() }}">Grįžti atgal</a>
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
                            <a class="nav-link {{ Request::is('register') ? 'active bg-primary':''}}" href="{{ route('register') }}">Registruotis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('login') ? 'active bg-primary':''}}" href="{{ route('login') }}">Prisijungti</a>
                        </li>
                    </ul>
                </div>
            @endauth
    </div>
    @endif
    </div>
</nav>