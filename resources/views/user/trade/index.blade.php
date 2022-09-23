@extends('layouts.user')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="pageColumn">
                    <nav>
                        <ol class="breadcrumb">
                            {{ Breadcrumbs::render('user.trade.index', $listing) }}
                        </ol>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </nav>
                    <div class="row">
                        <div class="col-md-6 border-end border-2 border-dark" id="columnTrade">
                            <h3 class="text-center mb-6">
                                Pasirinkto skelbimo duomenys
                            </h3>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($products as $key => $product)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    <div class="overlay-image d-block min-vh-50 rounded"
                                                        style="background-image: url('{{ asset('/storage/listing/' . $product->image) }}');">
                                                        <span class="mask"
                                                            style="background-color: rgba(0, 0, 0, 0.2)"></span>
                                                        <div class="carousel-caption d-none d-md-block">
                                                            <h4 class="text-white fst-italic">{{ $product->product_name }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon position-absolute bottom-50"
                                                aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon position-absolute bottom-50"
                                                aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-transparent fw-bold">
                                            Skelbimo kūrėjas: <span
                                                class="text-info">{{ $listing->user->name }}</span>
                                        </li>
                                        <li class="list-group-item bg-transparent fw-bold">
                                            Paskelbimo data: <span
                                                class="text-info">{{ $listing->created_at->format('Y/m/d H:i') }}</span>
                                        </li>
                                        <li class="list-group-item bg-transparent fw-bold">
                                            Produktų skaičius: <span
                                                class="text-info">{{ $products->count() }}</span>
                                        </li>
                                        <li class="list-group-item bg-transparent fw-bold">
                                            Produkto(-ų) pavadinimas:
                                            <ol class="list-group list-group-numbered">
                                                @for($i = 0; $i < count($products); $i++)
                                                    <li class="list-group-item bg-transparent"> <span
                                                            class="text-info"> {{ $products[$i]->product_name }},
                                                            {{ $products[$i]->quantity }} {{ $products[$i]->measurment_unit }}
                                                        </span>
                                                        <span class="text-primary" style="float: right">
                                                            Norimas kiekis: {{ $quantity[$i] }} {{ $products[$i]->measurment_unit }}
                                                        </span>
                                                        <br>
                                                        <span class="text-secondary"> Produkto aprašas </span>
                                                    </li>
                                                @endfor
                                            </ol>
                                        </li>
                                   <li class="list-group-item bg-transparent fw-bold"> 
                                       <address class="fw-bold">
                                        Medžiagos buvimo vieta: <span class="text-info"> {{ $listing->location }}
                                        </span><br />
                                        @if ($listing->user->phone)
                                            Telefono numeris:<span class="text-primary"> {{ $listing->user->phone }}
                                            </span> <br />
                                        @endif
                                    </address>
                                   </li>
                                    <li class="list-group-item bg-transparent fw-bold">
                                        <p>
                                            <span class="text-secondary fw-bold">
                                                Skelbimo aprašas: </span> <span class="text-info text-wrap"> {{ $listing->description }}
                                            </span>
                                        </p>
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-center">
                                Mainų užklausos forma
                            </h3>
                            <div class="col-md-12" style="text-align: right;">
                                <button type="button" class="btn bg-gradient-info" data-container="body"
                                    data-bs-toggle="popover" data-color="primary" data-bs-placement="left"
                                    data-bs-content="Čia yra mainymo forma, prašome pateikti, jūsų, siūlomus produktus ir spauskite siūlyti mainus.">
                                    <i class="fas fa-question-circle"></i>
                            </div>
                            <form action="{{ route('user.trade.offer', $listing->id) }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label>El. paštas*</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') ?? Auth::User()->email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Telefono numeris*</label>
                                        <input type="tel" class="form-control" name="phone" value="{{ old('phone') ?? Auth::User()->profile->phone }}">
                                    </div>
                                </div>
                                <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Siūlomų medžiagų sąrašas*</label>
                                        <textarea class="form-control border" rows="4" placeholder="Pateikite medžiagų sąrašą, kurį siūlote kitam naudotojui."
                                            id="offer" spellcheck="false" style="height: 100px; resize: none;"
                                            name="offered_material" required></textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label for="product_offer_url">Nuoroda į jūsų skelbimą</label>
                                        <input type="text" class="form-control" name="product_offer_url" id="product_offer_url" 
                                        placeholder="Jeigu norite galite pateikti nuorodą į egzistuojantį skelbimą">
                                    </div>
                                </div> --}}
                                <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Medžiagų būvimo vieta*</label>
                                        <input type="text" class="form-control" name="address" placeholder="Adresas">
                                    </div>
                                </div>          
                                {{-- <div class="col-md-10 mx-auto">
                                    <div class="input-group input-group-static mb-4">
                                        <label for="ts_company_select" class="ms-0">Pervežimo įmonės</label>
                                        <select class="form-control" id="ts_company_select">
                                          <option selected>Pervežimo paslauga nereikalinga</option>
                                          <option>DPD</option>
                                          <option>Omniva</option>
                                          <option>Lietuvos Paštas</option>
                                          <option>Express</option>
                                        </select>
                                      </div>
                                </div> --}}
                                <div class="col-md-10 mx-auto">
                                    <label class="form-label" for="images">Produktų nuotraukos</label>
                                    <div class="input-group input-group-dynamic mb-4">
                                        <input type="file" class="form-control-file" name="images[]" id="images">
                                    </div>
                                </div>

                                @for($i = 0; $i < count($products); $i++)
                                    <input type="hidden" name="desired_quantity[]" value="{{ $quantity[$i] }}" />
                                    <input type="hidden" name="wanted_material[]" value="{{ $products[$i]->id }}" /> 
                                @endfor
                                    <input type="hidden" name="receiver" value="{{ $listing->user->id }}" />

                                <div class="col-md-12" style="text-align: right;">
                                <button type="submit" class="btn btn-primary" style="text-align: right;">
                                    Siūlyti mainus
                                </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script>

<script>
    $(function() {
        $('[data-bs-toggle="popover"]').popover()
    })
</script>

@section('scripts')
@endsection
