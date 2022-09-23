@extends('layouts.user')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('user.activeOrders.index') }}
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
                <h4>Aktyvūs užsakymai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('user.activeOrders.index') }}" class="btn btn-outline-primary active"
                        aria-current="page">Pateikti užsakymai</a>
                    <a href="{{ route('user.activeOrders.orders') }}" class="btn btn-outline-primary">Gauti užsakymai</a>
                </div>
            </div>

            @if ($orders->count() == 0)
                <div class="row p-2 bg-white border rounded">
                    <div class="col-md-11 mt-1 text-center">
                        <h5>Šiuo metu užsakymų nėra...</h5>
                    </div>
                </div>
            @endif
            <div class="d-flex row">
                <div class="col-md-11 d-grid gap-2">
                    @foreach ($orders as $order)
                        <div class="row p-2 bg-white border rounded">
                            <div class="row g-0">
                                <div class="col-6 col-md-6 col-sm-6 text-sm-start">
                                    <div class="ms-3">
                                        <a href="/skelbimai/{{ $order->listing->id }}">
                                            <h5>{{ $order->listing->title }}
                                        </a></h5>
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

                            <div class="col-md-3 col-sm-3"><img class="img-fluid rounded h-100"
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
                                        Užsakytas kiekis:
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

                            <div class="align-items-center align-content-center col-md-3 col-sm-3 border-left mt-1">
                                <div class="d-flex flex-row align-items-center">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <h6 class="mr-1">Skelbimo sąvininkas:
                                                {{ $order->listing->user->name }} </h6>
                                        </li>
                                        <li class="list-group-item">
                                            El. Paštas:<span class="text-info">
                                                {{ $order->listing->user->email }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Tel. Nr:<span class="text-info">
                                                {{ $order->listing->user->profile->phone }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="text-info"><i class="fa-solid fa-location-dot"></i>
                                                {{ $order->listing->location }}</h6>
                                        </li>
                                    </ul>
                                </div>
                                @if ($order->order_status == 'užsakymas perduotas vykdymui')
                                    <div class="mt-2 text-md-end text-sm-end">
                                        <form action="{{ route('user.activeOrders.cancel', $order->id) }}" method="POST"
                                            onsubmit="return submitCancel(this);">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">Atšaukti užsakymą</button>
                                        </form>
                                        <div class="text-center">
                                            <span class="badge bg-success text-wrap">Užsakymas perduotas vykdyti</span>
                                        </div>
                                    </div>
                                @elseif($order->transportation_needed == 1 && $order->order_status == 'pateiktas pervežimo kompanijai')
                                    <div class="mt-2 text-md-center text-sm-center">
                                        <form action="{{ route('user.activeOrders.complete', $order->id) }}"
                                            method="POST" onsubmit="return submitConfirm(this);">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary">Medžiagos gautos</button>
                                        </form>
                                        <div class="text-center">
                                            <span class="badge bg-warning text-wrap text-dark">Pervežimas užsakytas, gavūs medžiagas
                                                patvirtinkite</span>
                                        </div>
                                    </div>                                   
                                @elseif($order->order_status == 'užsakymo vykdymas pradėtas')
                                    <div class="mt-2 text-md-center text-sm-center">
                                        <label class="badge bg-success text-wrap">Užsakymas pradėtas vykdyti.
                                            Galbūt norite užsakyti pervežimo paslaugą?</label>
                                    </div>
                                    <div class="mt-2 text-md-center text-sm-center">
                                        <a href="{{ route('user.activeOrders.transportationOrder.index', $order->id) }}"
                                            class="btn btn-primary btn-sm" role="button">Užsakyti pervežimo paslaugą</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="badge bg-warning text-dark">Laukiama medžiagos išsiuntimo</span>
                                    </div>
                                @elseif($order->order_status == 'medžiagos išsiųstos')
                                    <form action="{{ route('user.activeOrders.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-check text-end">
                                            <span class="text-primary me-2"> Ar užsakymas baigtas? </span>
                                            <input class="form-check-input" type="radio" name="radioButton" id="yes"
                                                value="yes" checked>
                                            <label class="form-check-label" for="yes">
                                                Taip
                                            </label>
                                        </div>
                                        <div class="form-check text-end me-2">
                                            <input class="form-check-input" type="radio" name="radioButton" id="no"
                                                value="no">
                                            <label class="form-check-label" for="no">
                                                Ne
                                            </label>
                                        </div>
                                        <div class="mt-2 text-md-center text-sm-center">
                                            <button type="submit" class="btn btn-primary btn-sm" role="button">Pateikti
                                                atsakymą
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <span class="badge bg-success text-wrap">Laukiama medžiagų pristatymo</span>
                                        </div>
                                    </form>
                                @elseif($order->order_status == 'užsakymas atliktas' || $order->order_status == 'medžiaga gauta')
                                    <div class="mt-2 text-md-end text-sm-end">
                                        <form action="{{ route('user.activeOrders.delete', $order->id) }}" method="POST"
                                            onsubmit="return submitDelete(this);">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">Šalinti užsakymą</button>
                                        </form>
                                        <div class="text-center">
                                            <span class="badge bg-warning text-wrap"> Užsakymas pabaigtas, laukiama
                                                pašalinimo.</span>
                                        </div>
                                    </div>                                   
                                @endif
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

        <script>
            function submitCancel(form) {
                Swal.fire({
                        title: "Ar tikrai norite atšaukti užsakymą?",
                        text: "Patvirtinus užsakymas bus atšauktas, tolimesniuose žingsniuose užsakymo atšaukti nebegalėsite.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Atšaukti užsakymą',
                        cancelButtonText: 'Grįžti'
                    })
                    .then(function(result) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Užsakymas atmestas!',
                                icon: 'success',
                                showConfirmButton: false,
                            });
                            form.submit();
                        }
                    });
                return false;
            }
        </script>

        <script>
            function submitConfirm(form) {
                Swal.fire({
                        title: "Ar gavote medžiagas?",
                        icon: "question",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Medžiagas gavau',
                        cancelButtonText: 'Uždaryti'
                    })
                    .then(function(result) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Medžiagos gautos!',
                                text: 'Užsakymas baigtas.',
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: true,
                                confirmButtonText: 'Gerai',
                            }).then(function() {
                                form.submit();
                            });
                        }
                    });
                return false;
            }
        </script>

        <script>
            function submitDelete(form) {
                Swal.fire({
                        title: "Ar norite pašalinti užbaigtą užsakymą?",
                        text: "Patvirtinus, užsakymą galėsite mėnesį matyti archyvų puslapyje.",
                        icon: "question",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Taip',
                        cancelButtonText: 'Ne'
                    })
                    .then(function(result) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Užsakymas sėkmingai pašalintas!',
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: true,
                                confirmButtonText: 'Gerai',
                            }).then(function() {
                                form.submit();
                            });
                        }
                    });
                return false;
            }
        </script>


    </body>
@endsection
@section('scripts')
    @parent
@endsection
