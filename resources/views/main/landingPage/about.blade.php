@include('layouts.landing.head')
@include('layouts.landing.nav')

<section class="feature gradient text-light" id="features">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('images/Construction.svg') }}" alt="Why choose us">
            </div>
            <div class="col-md-6">
                <h1>Pristatome statybinių medžiagų valdymo sistemą</h1>
                <p class="my-4">
                    Teikiame galimybę pirkti, parduoti bei keistis atlikusiomis statybinėmis medžiagomis.
                    Taip skatiname atsakingai bei naudingai valdyti statybinėmis žaliavomis.
                </p>
                <ul>
                    <li>Greita bei patogi medžiagų paieška</li>
                    <li>Paprastas bei nesudėtingas skelbimų patalpinimas</li>
                    <li>Jokių reklamų</li>
                    <li>Teikiame medžiagų pervežimo paslaugas</li>
                </ul>
            </div>
        </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#fff" fill-opacity="1"
            d="M0,160L40,154.7C80,149,160,139,240,149.3C320,160,400,192,480,218.7C560,245,640,267,720,256C800,245,880,203,960,176C1040,149,1120,139,1200,117.3C1280,96,1360,64,1400,48L1440,32L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
        </path>
    </svg>
</section>

@include('layouts.landing.aboutUs')
@include('layouts.landing.footer')

