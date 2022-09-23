@extends('layouts.user')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('user.activeOrders.orders') }}
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
                    <a href="{{ route('user.activeOrders.index') }}" class="btn btn-outline-primary" aria-current="page">Pateikti užsakymai</a>
                    <a href="{{ route('user.activeOrders.orders') }}" class="btn btn-outline-primary active">Gauti užsakymai</a>
                </div>
            </div>

            @if($orders->count() == 0)
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
                                @if ($order->order_status == 'užsakymas perduotas vykdymui')
                                    <div class="mt-2 text-md-end text-sm-end">
                                        <form action="{{ route('user.activeOrders.start', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="startOrder" class="btn btn-success btn-sm">Vykdyti
                                                užsakymą</button>
                                            <button type="submit" name="cancelOrder" class="btn btn-danger btn-sm">Atšaukti užsakymą</button>
                                        </form>
                                    </div>
                                @elseif($order->order_status == 'užsakymo vykdymas pradėtas')
                                    <div class="mt-2 text-md-end text-sm-end">
                                        <form action="{{ route('user.activeOrders.sent', $order->id) }}" method="POST" onsubmit="return submitForm(this);">
                                            @csrf
                                            @method('PUT')
                                            <label class="text-primary"> Ar medžiaga paruošta išsiuntimui?</label>
                                            <button type="submit" class="btn btn-success btn-sm" role="button">Taip</button>
                                        </form>
                                        <div class="text-center">
                                            <span class="badge bg-warning text-dark">Laukiama medžiagos išsiuntimo</span>
                                        </div>
                                    </div>
                                @elseif($order->order_status == 'medžiagos išsiųstos')
                                    <label class="text-primary text-lg"> Ar užsakymas baigtas? </label>
                                    <div class="mt-2 text-md-center text-sm-center">
                                        <form action="{{ route('user.activeOrders.complete', $order->id) }}" method="POST" onsubmit="return submitComplete(this);">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary btn-sm" role="button">
                                                Užbaigti užsakymą
                                            </button>
                                        </form>
                                        <div class="text-center">
                                            <span class="badge bg-warning text-dark text-wrap">Medžiagos išsiųstos, laukiama
                                                medžiagų gavimo patvirtinimo</span>
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
            function submitForm(form) {
                Swal.fire({
                        title: "Ar medžiaga paruošta siuntimui?",                  
                        icon: "question",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Taip',
                        cancelButtonText: 'Atgal'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Medžiaga paruošta siuntimui!',
                                timer: 2500,
                                icon: 'success',
                                showConfirmButton: false,
                            }).then(function() {
                                form.submit();
                            })
                        }
                    });
                return false;
            }
        </script>

<script>
    function submitComplete(form) {
        Swal.fire({
                title: "Ar užsakymas pabaigtas?",     
                text: "Patvirtinus sutinkate, kad gavote medžiagas iš kito naudotojo, o skelbimą galėsite rasti archyvuose.",             
                icon: "question",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Taip',
                cancelButtonText: 'Atgal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Užsakymas pabaigtas!',
                        text: 'Pabaigtus užsakymus galite rasti archyvų puslapyje.',
                        timer: 3000,
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonText: 'Gerai',
                    }).then(function() {
                        form.submit();
                    });
                }
                else
                {
                    Swal.fire({
                        title: 'Užsakymas nepabaigtas!',
                        text: 'Kilus nesklandumamas susisiekite su mumis per kontaktų formą.',
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false,
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
