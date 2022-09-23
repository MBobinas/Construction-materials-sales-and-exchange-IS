@include('layouts.landing.head')
@include('layouts.landing.nav')

<head>
    <link id="pagestyle" href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>

    <style>
        a {
            text-decoration: none;
            color: black;
        }

    </style>
</head>

<section class="filter">

    <nav>
        <div class="nav-wrapper">
            <ol class="breadcrumb ms-3">
                <li class="breadcrumb-item text-sm"><a class="text-dark"
                        href="{{ route('main.guest.listings') }}">Pradžia</a></li>
                <li class="breadcrumb-item text-sm active" aria-current="page">{{ $category_name }}</li>
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="col-md-2 col-sm-2 mx-2 mt-1">
                <form id="search" action="/svečias/paieška" method="GET">
                    @csrf
                    <div class="input-group input-group-outline ms-2">
                        <input type="text" class="form-control" placeholder="Įveskite ieškomo skelbimo pav."
                            name="search" id="search" onfocus="focused(this)" onfocusout="defocused(this)">
                    </div>
                </form>
            </div>
            <div class="ms-4 mt-2">
                <a href="{{ route('main.guest.filter.trade') }}">
                    <i class="fa-solid fa-filter"></i>
                    <span class=""> Mainyti </span>
                </a>
                <a href="{{ route('main.guest.filter.buy') }}">
                    <i class="fa-solid fa-filter ms-2"></i>
                    <span class=""> Pirkti </span>
                </a>
                <a href="{{ route('main.guest.filter.buyOrTrade') }}">
                    <i class="fa-solid fa-filter ms-2"></i>
                    <span class=""> Pirkti arba mainyti </span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-2">
        <div class="d-flex row">
            <div class="col-md-9 gap-2">
                @foreach ($listings as $listing)
                    <div class="row p-2 mb-2 bg-white border rounded">
                        <div class="col-md-3 mt-1"><img class="img-fluid img-responsive rounded product-image h-100"
                                src="{{ asset('/storage/listing/' . $listing->image) }}"></div>
                        <div class="col-md-6 mt-1">
                            <h5>{{ $listing->title }}</h5>
                            <div class="d-flex flex-row">
                                <span>{{ $listing->ranking }}</span>
                                <div class="ratings mr-2"><i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="mt-1 mb-1 spec-1">
                                @foreach ($listing->products as $product)
                                    <span class="fw-bold">{{ $product->category->category_name }} | </span>
                                @endforeach
                            </div>
                            <p class="text-justify para text-wrap mb-0">{{ $listing->description }}<br><br></p>
                            <span class="text-sm-start text-uppercase fw-bold"><i class="fa-solid fa-tag"></i>
                                {{ $listing->listing_type }}</span><br>
                        </div>
                        <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                            @if ($listing->total_sum != 0)
                                <div class="d-flex flex-row align-items-center">
                                    <h4 class="mr-1">{{ $listing->total_sum }} €</h4>
                                </div>
                            @else
                                <div class="d-flex flex-row align-items-center fst-italic">
                                    <h6 class="mr-1">Nenurodyta kaina</h6>
                                </div>
                            @endif
                            <h6 class="text-success"><i class="fa-solid fa-location-dot"></i>
                                {{ $listing->location }}</h6>
                            <div class="d-flex flex-column mt-8">
                                <a href="{{ route('main.guest.listing', $listing->id) }}" class="btn btn-primary btn-sm"
                                    role="button" style="background-color: #EA3C77;">Peržiūrėti</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-1">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row">
                    </div>
                </div>
            </div>
            @if (count($categories) > 0)
                <div class="col-md-2 bg-gradient-dark ps border rounded" style="background-color: #2B2B2E">
                    <div class="d-flex flex-column text-center mt-1">
                        <h5 class="text-white">Kategorijų parinktys</h5>
                    </div>
                    <div class="accordion">
                        @foreach ($categories as $category)
                            <div class="accordion-item">
                                <button class="accordion-header">
                                    <a href="{{ route('main.guest.filter.category', $category->category_name) }}"
                                        class="btn btn-link text-white">{{ $category->category_name }}
                                    </a>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/accordion.js') }}"></script>

@include('layouts.landing.footer')
