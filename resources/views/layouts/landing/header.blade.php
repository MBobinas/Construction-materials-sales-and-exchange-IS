<header class="page-header gradient">
    <div class="container pt-3">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-5">
                <h2>Sveiki atvykę!</h2>

                <p>
                     <strong>Mūsų tikslas -</strong> padėti, jums, lengvai ir efektyviai valdyti nepanaudotomis statybinėmis medžiagomis.
                </p>

                <button class="btn btn-outline-success btn-lg">
                    <a href="#">Apie mus</a>
                </button>

                <button class="btn btn-outline-warning btn-lg">
                    <a href="{{ route('register') }}">Registruokis</a>
                </button>

            </div>
            <div class="col-md-6">
                <img class="w-100" src="{{ asset('images/Students-bro.png') }}" alt="Header image" />
            </div>
        </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#FFF" fill-opacity="1"
            d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,90.7C672,64,768,64,864,96C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
        </path>
    </svg>
</header>