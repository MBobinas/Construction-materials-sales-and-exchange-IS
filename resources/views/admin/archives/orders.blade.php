@extends('layouts.admin')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('admin.archives.orders') }}
                </li>
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>


        <div class="container-fluid">
            <div class="col-md-11 text-center">
                <h4>Įvykdyti užsakymai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('admin.archives.index') }}" class="btn btn-outline-primary">Archyvuoti skelbimai</a>
                    <a href="{{ route('admin.archives.orders') }}" class="btn btn-outline-primary active" aria-current="page">Archyvuoti sandoriai</a>
                </div>
            </div>

            <div class="d-flex row">
                <div class="col-md-11 d-grid gap-2">
                    @foreach ($archives as $order)
                        <div class="row p-2 bg-white border rounded">
                            <div class="row g-0">
                                <div class="col-6 col-md-6 col-sm-6 text-sm-start">
                                    <div class="ms-3">
                                        <h5>{{ $order->listing->title }}</h5>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 text-sm-end">
                                    <span class="badge bg-secondary text-wrap">Užklausos data
                                        {{ $order->created_at->format('m/d/Y H:i') }} </span>
                                    <span class="badge bg-info text-wrap">Prieš:
                                        {{ $order->created_at->diffForhumans(null, true, true) }}</span>
                                    <a href="/skelbimai/{{ $order->listing->id }}"> Peržiūrėti skelbimą <i
                                            class="fa-solid fa-magnifying-glass"></i></a>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-3"><img class="img-fluid responsive rounded"
                                    src="{{ asset('/storage/product/' . $order->product->image) }}">
                            </div>

                            <div class="col-6 col-md-6 col-sm-6">
                                <div class="flex-row text-center">
                                    <h5>Užsakyta medžiaga</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        Medžiagos pavadinimas:
                                        <span class="text-info">
                                            {{ $order->product->product_name }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        Užsakytas medžiagos kiekis:
                                        <span class="text-info">
                                            {{ $order->quantity }} {{ $order->product->measurment_unit }}
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        Bendra medžiagų kaina:
                                        <span class="text-info">
                                            {{ $order->price }} €
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="align-items-center align-content-center col-md-3 col-sm-3 mt-1">
                                <div class="d-flex flex-row align-items-center">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <h6 class="mr-1">Užsakymo pateikėjas:
                                                {{ $order->user->name }} </h6>
                                        </li>
                                        <li class="list-group-item">
                                            El. Paštas:<span class="text-info"> {{ $order->user->email }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Tel. Nr: <span class="text-info">{{ $order->phone }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="text-info"><i class="fa-solid fa-location-dot"></i>
                                                {{ $order->city }}, {{ $order->address }}</h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!--trades end-->
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>
        <script src="{{ asset('assets/js/accordion.js') }}"></script>

    </body>
@endsection
@section('scripts')
    @parent
@endsection
