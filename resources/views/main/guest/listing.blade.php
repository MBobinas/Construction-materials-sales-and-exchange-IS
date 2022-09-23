@extends('layouts.landingPage')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
        <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
    </head>
    <style>
        .overlay-image {
            background-size: cover;
            background-position: center;
        }

        .textarea_danger {
            border: 1px solid red !important
        }

        .centered {
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>

    <body style="background-color: #F0F2F5">
        {{ Breadcrumbs::render('main.guest.listing', $listing) }}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="text-left text-break text-wrap border-bottom" style="color:#28282B">
                        {{ $listing->title }}
                    </h2>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-3 text-end mb-1">
                            @if ($listing->user->profile->image)
                                <img src="/storage/{{ $listing->user->profile->image }}"
                                    class="img-responsive; rounded-circle w-60">
                            @endif
                        </div>
                        <div class="col-sm-9 text-start">
                            <span>Skelbimo autorius: {{ $listing->user->name }}</span><br>
                            <span>Susisiekti paštu: {{ $listing->user->email }}</span><br>
                            @if ($listing->user->profile->phone)
                                <span>Telefono numeris: {{ $listing->user->profile->phone }}</span><br>
                            @endif
                            <span>Paskyra sukurta: {{ $listing->user->created_at->format('m/d/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product slideshow section -->
            <div class="row">
                <div class="col-sm-5">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($products as $key => $product)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="overlay-image d-block min-vh-50 rounded"
                                        style="background-image: url('{{ asset('/storage/listing/' . $product->image) }}');">
                                        <span class="mask" style="background-color: rgba(0, 0, 0, 0.2)"></span>
                                        <div class="carousel-caption d-none d-md-block">
                                            <h4 class="text-white fst-italic">{{ $product->product_name }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon position-absolute bottom-50" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon position-absolute bottom-50" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-7 border border-dark rounded" style="background-color: #DBE0E7">
                    <span class="badge badge-info">Skelbimo tipas: {{ $listing->listing_type }}</span><br>
                    <div class="border border-dark rounded p-2 mb-1" style="color:#000">
                        <span> Skelbimo aprašas </span><br>
                        <p class="text-left fs-4 fw-bold text-break text-wrap" style="color:#000">
                            {{ $listing->description }}
                        </p>
                    </div>
                    <h4 style="color:#28282B">Produktų sąrašas</h4>
                    <hr class="border border-dark">
                    <div class="col-sm-12">
                        @if ($listing->listing_type == 'parduoti' || $listing->listing_type == 'parduoti arba mainyti')
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                    Medžiagos pav.</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 align-middle text-center">
                                                    Būklė</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                    Kiekis</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                    Kaina</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                    Kiekis užsakymui</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 text-xs">{{ $product->product_name }}</h6>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $product->condition }}</p>
                                                    </td>
                                                    <td class="align-middle text-center text-sm"><span
                                                            class="text-secondary text-xs font-weight-normal">{{ $product->quantity }}</span>
                                                        <span
                                                            class="text-secondary text-xs font-weight-normal">{{ $product->measurment_unit }}</span>
                                                    </td>
                                                    <td class="align-middle text-center text-sm"><span
                                                            class="text-secondary text-xs font-weight-normal">{{ $product->price }}
                                                            €</span></td>
                                                    <td class="align-middle">
                                                        <div class="form-group centered">
                                                            <div class="col-sm-4">
                                                                <input
                                                                    class="form-control form-control-sm border border-dark rounded"
                                                                    type="number" name="quantity[]" min="0" value="0" disabled />
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input class="form-check-input border border-dark" type="checkbox"
                                                            name="selected_product[]" id="arPirkti"
                                                            value="{{ $product->id }}" aria-label="..."  disabled/>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <span class="fw-bold text-warning"> Skelbimo pirkime arba mainyme gali dalyvauti tik registruoti naudotojai. </span><br>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm"
                                    role="button">Registruotis</a>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm"
                                    role="button">Prisijungti</a>
                            </div>
                        @endif

                        @if ($listing->listing_type == 'mainyti' || $listing->listing_type == 'parduoti arba mainyti')
                            <form action="{{ route('user.trade.index', $listing->id) }}" enctype="multipart/form-data"
                                method="GET">
                                @csrf
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Medžiagos pav.</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 align-middle text-center">
                                                        Būklė</th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Kiekis</th>
                                                    @if ($listing->listing_type == 'parduoti arba mainyti')
                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                            Kaina</th>
                                                    @endif
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                                        Kiekis užsakymui</th>
                                                    <th class="text-secondary opacity-7"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 text-xs">{{ $product->product_name }}</h6>
                                                        </td>
                                                        <td class="align-middle text-center">{{ $product->condition }}
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            {{ $product->quantity }}
                                                            {{ $product->measurment_unit }}</td>
                                                        @if ($listing->listing_type == 'parduoti arba mainyti')
                                                            <td class="align-middle text-center text-sm">
                                                                {{ $product->price }} €</td>
                                                        @endif
                                                        <td class="align-middle">
                                                            <div class="form-group centered">
                                                                <div class="col-sm-4">
                                                                    <input
                                                                        class="form-control form-control-sm border border-dark rounded"
                                                                        type="number" name="quantity[]" min="0" value="0" />
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input border border-dark"
                                                                type="checkbox" name="selected_product[]" id="arPirkti"
                                                                value="{{ $product->id }}" aria-label="..." />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <span class="fw-bold text-warning"> Skelbimo pirkime arba mainyme gali dalyvauti tik registruoti naudotojai. </span><br>
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm"
                                        role="button">Registruotis</a>
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm"
                                        role="button">Prisijungti</a>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
                <!-- Comments section -->
                <div class="row">
                    <div class="row d-flex my-1 py-1">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h4 class="text-center mb-2 pb-2" style="color:#28282B;">Komentarai</h4>
                                    <div class="row">
                                        <div class="col">
                                            @if (Auth::guest())
                                                <!-- comment content start -->
                                                @if ($comments->count() == 0)
                                                    <p class="card-text text-center">
                                                        Šiuo metu nėra komentarų.
                                                    </p>
                                                @else
                                                    @foreach ($comments as $comment)
                                                        <div class="d-flex flex-start">
                                                            <a href="{{ route('user.profile.details', $comment->user_id) }}">
                                                                <img class="rounded-circle shadow-1-strong me-3 mb-1"
                                                                    src="{{ asset('/storage/' . $comment->user->profile->image) }}"
                                                                    alt="profilio nuotrauka" width="65" height="65" />
                                                            </a>
                                                            <div class="flex-grow-1 flex-shrink-1">
                                                                <div class="border-bottom">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <p class="mb-1">
                                                                            <a
                                                                                href="{{ route('user.profile.details', $comment->user_id) }}">
                                                                                {{ $comment->author }} </a>
                                                                            <span class="small">-
                                                                                {{ $comment->created_at->diffForHumans() }}</span>
                                                                        </p>
                                                                    </div>
                                                                    <p class="small mb-0">
                                                                        {{ $comment->text }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- reply part of comment-->
                                                        <div class="d-flex flex-start mt-2 mb-2 ms-6">
                                                            <a class="me-3" href="#">
                                                                <img class="rounded-circle shadow-1-strong"
                                                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(11).webp"
                                                                    alt="avatar" width="65" height="65" />
                                                            </a>
                                                            <div class="flex-grow-1 flex-shrink-1">
                                                                <div>
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <p class="mb-1">
                                                                            Skelbimo autorius <span
                                                                                class="small">- 3 hours ago</span>
                                                                        </p>
                                                                    </div>
                                                                    <p class="small mb-0">
                                                                        letters, as opposed to using 'Content here, content
                                                                        here',
                                                                        making it look like readable English.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- reply completed -->
                                                    @endforeach
                                                @endif
                                            @endif
                                            <!-- comment content end -->
                                        </div>
                                    </div>
                                    <hr class="border border-2 border-dark">
                                    <div class="row mt-1">
                                        <h5 class="text-center text-wrap" style="color:#28282B;">Komentuoti gali tik
                                            prisijungę nariai</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>
        <script src="{{ asset('assets/js/accordion.js') }}"></script>

        <script>
            $('.carousel').carousel({
                interval: 10000;
            });
        </script>
        <!-- Comments box validation -->
        <script>
            function validate() {
                const areabox = document.querySelector('#test');
                const areatext = document.querySelector("#comment").value.length;
                const textcount = document.querySelector("#textcount");
                const wordcount = document.querySelector("#words_count");
                textcount.innerHTML = areatext;


                if (areatext > 70) {
                    textcount.classList.add("text-danger");
                    areabox.classList.add("border-danger");
                } else {
                    textcount.classList.remove("text-danger");
                    areabox.classList.remove("border-danger");
                }

                if (areatext < 1) {
                    wordcount.classList.add("d-none");
                } else {
                    wordcount.classList.remove("d-none");
                }
            }
        </script>

    </body>
@endsection
@section('scripts')
    @parent
@endsection
