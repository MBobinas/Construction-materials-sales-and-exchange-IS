@include('layouts.landing.head')
@include('layouts.landing.nav')

<style>
    a {
        text-decoration: none;
        color: black;
    }

</style>

<section class="listings">

    <nav>
        <div class="nav-wrapper">
            <div class="ms-3">
                {{ Breadcrumbs::render('main.guest.listings') }}
            </div>
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
            <div class="mt-2 ms-3">
                {{ $listings->links('pagination::bootstrap-4') }}
            </div>
        </div>  
    </nav>

    <div class="container-fluid my-2">
        <div class="d-flex row">
            <div class="col-md-9 gap-2">
                @foreach ($listings as $listing)
                    @if ($listing->status != 'deaktyvuotas')
                        <div class="row p-2 mt-2 ms-1 bg-white border rounded">
                            <div class="col-md-3 mt-1"><img
                                    class="img-fluid img-responsive rounded product-image h-100"
                                    src="{{ asset('/storage/listing/' . $listing->image) }}"></div>
                            <div class="col-md-6 mt-1">
                                <h5>{{ $listing->title }}</h5>
                                <div class="mt-1 mb-1 spec-1">
                                    @foreach ($listing->products as $product)
                                        <span class="fw-bold">{{ $product->category->category_name }} | </span>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-row">
                                    <p class="text-justify para text-wrap mt-1">{{ $listing->description }}<br><br>
                                    </p>
                                </div>
                                <div class="d-flex mt-auto">
                                    <span class="text-sm-start text-uppercase fw-bold"><i class="fa-solid fa-tag"></i>
                                        {{ $listing->listing_type }}</span>
                                </div>
                            </div>

                            <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                                <h6 class="text-success"><i class="fa-solid fa-location-dot"></i>
                                    <span class="text-dark"> Medžiagos būvimo vieta: </span>
                                    {{ $listing->location }}
                                </h6>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('main.guest.listing', $listing->id) }}"
                                        class="btn btn-primary btn-sm border-0" role="button"
                                        style="background-color: #EA3C77;">Peržiūrėti</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="mt-2">
                    {{ $listings->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row">
                    </div>
                </div>
            </div>
            <div class="col-md-2 border rounded" style="background-color: #2B2B2E">
                <div class="text-center mt-auto">
                    <h5 class="text-white">Kategorijų parinktys</h5>
                </div>
                <div class="accordion">
                    @foreach ($categories as $category)
                        <div class="accordion-item h-100">
                            <button class="accordion-header">
                                <a href="{{ route('main.guest.filter.category', $category->category_name) }}"
                                    class="btn btn-link text-white"
                                    style="text-decoration: none">{{ $category->category_name }}
                                </a>
                                <i class="fas fa-angle-down"></i>
                            </button>
                            @if ($category->children)
                                @foreach ($category->children as $child)
                                    <div class="accordion-body">
                                        <a href="{{ route('main.guest.filter.category', $child->category_name) }}"
                                            class="btn btn-link text-white"
                                            style="text-decoration: none">{{ $child->category_name }}
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/accordion.js') }}"></script>

@include('layouts.landing.footer')
