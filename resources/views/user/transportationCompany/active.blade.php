@extends('layouts.transportation_company')
@section('content')

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

    <body>
        <nav>
            <ol class="breadcrumb">
                {{ Breadcrumbs::render('user.transportationCompany.active') }}
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>
        <div class="container-fluid">

            <div class="col-md-12 text-center">
                <h4>Aktyvūs užsakymai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('user.transportationCompany.active') }}" class="btn btn-outline-primary active"
                        aria-current="page">Paprasti užsakymai</a>
                    <a href="{{ route('user.transportationCompany.activeTradeOrders') }}"
                        class="btn btn-outline-primary">Mainų užsakymai</a>
                </div>
            </div>

            <div class="d-flex row">
                <div class="col-md-12 d-grid gap-2 mb-2">
                    @foreach ($ts_orders as $ts_order)
                        <div class="row p-2 bg-white border rounded">
                            <div class="row g-0">
                                <div class="col-6 col-md-6 col-sm-6 text-sm-start">
                                    <div class="ms-3">
                                        <a href="/skelbimai/{{ $ts_order->order->listing->id }}">
                                            <h5>{{ $ts_order->order->listing->title }}
                                        </a></h5>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-sm-6 text-sm-end">
                                    <span class="badge bg-secondary text-wrap">Užklausos data
                                        {{ $ts_order->created_at->format('m/d/Y H:i') }} </span>
                                    <span class="badge bg-info text-wrap">Prieš:
                                        {{ $ts_order->created_at->diffForhumans(null, true, true) }}</span>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-3"><img class="img-fluid rounded h-100"
                                    src="{{ asset('/storage/product/' . $ts_order->order->product->image) }}">
                            </div>

                            <div class="col-5 col-md-5 col-sm-5 mt-1">
                                <div class="flex-row text-center">
                                    <h5>Užsakyta medžiaga</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        Produktas:
                                        <span class="text-info">{{ $ts_order->order->product->product_name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Užsakytas kiekis:
                                        <span class="text-info">{{ $ts_order->order->quantity }}
                                            {{ $ts_order->order->product->measurment_unit }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Produkto papildoma informacija:
                                        <span class="text-info">{{ $ts_order->order->product->description }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Medžiagų būvimo vieta:
                                        <span class="text-info">{{ $ts_order->order->listing->location }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="align-items-center align-content-center col-md-2 col-sm-2 mt-1">
                                <div class="d-flex flex-row align-items-center">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <h6 class="mr-1">Skelbimo sąvininkas:
                                                {{ $ts_order->order->listing->user->name }} </h6>
                                        </li>
                                        <li class="list-group-item">
                                            El. Paštas:<span class="text-info">
                                                {{ $ts_order->order->listing->user->email }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Tel. Nr:<span class="text-info">
                                                {{ $ts_order->order->listing->user->profile->phone }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Medžiagų būvimo vieta:<span class="text-info"><br>
                                                <i class="fa-solid fa-location-dot"></i>
                                                {{ $ts_order->order->listing->location }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="align-items-center align-content-center col-md-2 col-sm-2 mt-1 border-start">
                                <div class="d-flex flex-row align-items-center">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <h6 class="mr-1">Užsakymo pateikėjas:
                                                {{ $ts_order->order->user->name }} </h6>
                                        </li>
                                        <li class="list-group-item">
                                            El. Paštas:<span class="text-info">
                                                {{ $ts_order->order->user->email }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Tel. Nr:<span class="text-info">
                                                {{ $ts_order->order->user->profile->phone }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Miestas:<span class="text-info"> {{ $ts_order->order->city }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Užsakovo adresas:<span class="text-info"><br>
                                                <i class="fa-solid fa-location-dot"></i>
                                                {{ $ts_order->order->address }}</span>
                                        </li>
                                    </ul>
                                </div>
                                @if ($ts_order->status == 'laukiama')
                                    <form action="{{ route('user.transportationCompany.confirm', $ts_order->id) }}"
                                        method="POST" onsubmit="return submitAccept(this);">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-0 mt-1">
                                            <button class="btn btn-icon btn-3 btn-success" type="submit">
                                                <span class="btn-inner--text ms-1">Priimti užsakymą</span>
                                                <span class="btn-inner--icon"><i class="fa-solid fa-check fa-lg"></i></span>
                                            </button>
                                        </div>
                                    </form>
                                    <form action="{{ route('user.transportationCompany.cancel', $ts_order->id) }}"
                                        method="POST" onsubmit="return submitDecline(this);">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-0 mt-1">
                                            <button class="btn btn-icon btn-3 btn-danger" type="submit">
                                                <span class="btn-inner--text"> Atmesti užsakymą </span>
                                                <span class="btn-inner--icon ms-1"><i
                                                        class="fa-solid fa-times fa-lg"></i></span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                                @if ($ts_order->status == 'priimta')
                                    <form action="{{ route('user.transportationCompany.invoice', $ts_order->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-0 mt-1">
                                            <button class="btn btn-icon btn-3 btn-success" type="submit">
                                                <span class="btn-inner--text"> Spausdinti užsakymą </span>
                                                <span class="btn-inner--icon ms-1"><i
                                                        class="fa-solid fa-file-invoice fa-lg"></i></span>
                                            </button>
                                        </div>
                                    </form>
                                    <form action="{{ route('user.transportationCompany.complete', $ts_order->id) }}"
                                        method="POST" onsubmit="return submitComplete(this);">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-0 mt-1">
                                            <button class="btn btn-icon btn-3 btn-primary" type="submit">
                                                <span class="btn-inner--text"> Medžiagos pristatytos </span>
                                                <span class="btn-inner--icon ms-1"><i
                                                        class="fa-solid fa-clipboard-check fa-lg"></i></span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
            {{ $ts_orders->links('pagination::bootstrap-4') }}
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>

        <script>
            function submitAccept(form) {
                Swal.fire({
                    title: "Ar tikrai norite patvirtinti šį užsakymą?",
                    icon: "question",
                    buttons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Taip',
                    cancelButtonText: 'Atgal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Užsakymas patvirtintas',
                            timer: 2500,
                            icon: 'success',
                            showConfirmButton: false,
                        }).then(function() {
                            form.submit();
                        });
                    }
                });
                return false;
            }
        </script>

        <script>
            function submitDecline(form) {
                Swal.fire({
                    title: "Ar norite atmesti šį užsakymą?",
                    input: 'textarea',
                    icon: "question",
                    buttons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Taip',
                    cancelButtonText: 'Atgal',
                    inputPlaceholder: "Atmetimo priežastis...",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Užsakymas atmestas',
                            timer: 2500,
                            icon: 'success',
                            showConfirmButton: false,
                        }).then(function() {
                            form.submit();
                        });
                    }
                });
                return false;
            }
        </script>

        <script>
            function submitComplete(form) {
                Swal.fire({
                    title: "Ar užsakymas baigtas?",
                    icon: "question",
                    buttons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Taip',
                    cancelButtonText: 'Atgal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Užsakymas pabaigtas',
                            timer: 2500,
                            icon: 'success',
                            showConfirmButton: false,
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
