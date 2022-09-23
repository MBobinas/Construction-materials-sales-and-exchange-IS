@extends('layouts.user')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('user.activeTrade.sentTrades') }}
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
                <h4>Aktyvūs mainų pasiūlymai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('user.activeTrade.index') }}" class="btn btn-outline-primary"
                        aria-current="page">Gauti
                        Pasiūlymai</a>
                    <a href="{{ route('user.activeTrade.sentTrades') }}" class="btn btn-outline-primary active">Išsiųsti
                        pasiūlymai</a>
                </div>
            </div>

            <div class="d-flex row">
                <div class="col-md-11 d-grid gap-2">
                    @foreach ($trades as $trade)
                        @if ($trade->status == 'laukiama' || $trade->status == 'priimta' || $trade->status == 'atšaukta')
                            <div class="row p-2 bg-white border rounded">
                                <div class="row g-0">
                                    <div class="col-6 col-md-6 col-sm-6 text-sm-start border-end">
                                        <div class="ms-3">
                                            <h5>{{ $trade->listing->title }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 text-sm-end">
                                        <span class="badge bg-secondary text-wrap">Užklausos data
                                            {{ $trade->created_at->format('m/d/Y H:i') }} </span>
                                        <a href="/skelbimai/{{ $trade->listing->id }}"> Peržiūrėti skelbimą <i
                                                class="fa-solid fa-magnifying-glass"></i></a>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-3"><img class="img-fluid rounded h-100"
                                        src="{{ asset('/storage/listing/' . $trade->listing->image) }}">
                                </div>

                                <div class="col-3 col-md-3 col-sm-3 border-end">
                                    <div class="flex-row text-center">
                                        <h5>Mainoma į</h5>
                                    </div>
                                    <h6 class="mr-1">Užklausos gavėjas: {{ $trade->listing->user->name }} </h6>
                                    <ol class="list-group list-group-numbered list-group-flush">
                                        Produkto(-ų) pavadinimas:
                                        @for ($i = 0; $i < count($trade->offers); $i++)
                                            <li class="list-group-item">
                                                <span class="text-info">
                                                    {{ $trade->offers[$i]->wanted_materials }}
                                                    {{ $trade->offers[$i]->quantity_wanted }}
                                                    {{ $trade->offers[$i]->measurment_unit }}
                                                </span>
                                            </li>
                                        @endfor
                                    </ol>
                                </div>

                                <div class="col-3 col-md-3 col-sm-3">
                                    <div class="flex-row text-center">
                                        <h5>Siūloma</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">                                      
                                        <li class="list-group-item border-0">
                                            <p><span class="fw-bold">Pasiūlymas: </span>{{ $trade->offered_materials }}</p>
                                        </li>
                                        @for ($i = 0; $i < count($trade->offers); $i++)
                                            @if ($trade->offers[$i]->quantity_offered != null)
                                                <li class="list-group-item border-0">Siūloma -
                                                    {{ $trade->offers[$i]->quantity_offered }}
                                                    {{ $trade->offers[$i]->measurment_unit }}
                                                </li>
                                            @endif
                                        @endfor
                                    </ul>
                                </div>

                                <div class="align-items-center align-content-center col-md-3 col-sm-3 border-left mt-1">
                                    <div class="d-flex flex-row align-items-center">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <h6 class="mr-1">Užklausos siuntėjas: {{ $trade->user->name }}
                                                </h6>
                                            </li>
                                            <li class="list-group-item">{{ $trade->email }}</li>
                                            @if ($trade->phone)
                                                <li class="list-group-item">{{ $trade->phone }}</li>
                                            @else
                                                <li class="list-group-item">Numeris nepateiktas</li>
                                            @endif
                                            @if ($trade->address)
                                                <li class="list-group-item">
                                                    <h6 class="text-info"><i class="fa-solid fa-location-dot"></i>
                                                        {{ $trade->address }}</h6>
                                                </li>
                                            @else
                                                <li class="list-group-item">
                                                    <h6 class="text-danger"><i class="fa-solid fa-location-dot"></i>
                                                        Nėra adreso</h6>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    @if ($trade->status == 'laukiama')
                                        <div class="mt-2 text-md-end text-sm-end">
                                            <form action="{{ route('user.trade.cancel', $trade->id) }}" method="POST"
                                                onsubmit="return submitCancel(this);">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="ms-2 btn btn-danger">Nutraukti
                                                    pasiūlymą</button>
                                            </form>
                                            <div class="text-center">
                                                <span class="badge bg-warning text-white text-wrap">Laukiama pasiūlymo patvirtinimo iš kito
                                                    naudotojo</span>
                                            </div>
                                        </div>
                                    @elseif($trade->transportation_needed == 1 && $trade->status == 'priimta')
                                        <div class="mt-2 text-md-center text-sm-center">
                                            <form action="{{ route('user.trade.complete', $trade->id) }}" method="POST"
                                                onsubmit="return submitComplete(this);">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-primary btn-sm">Užbaigti
                                                    Mainus</button>
                                                <div class="text-center">
                                                    <span class="badge bg-success text-wrap">Pervežimas užsakytas, gavūs medžiagas
                                                        pabaikite mainus</span>
                                                </div>        
                                        </div>
                                    @elseif($trade->status == 'priimta')
                                        <div class="mt-2 text-md-center text-sm-center">
                                            {{-- <a href="#" class="btn btn-info btn-sm" role="button">Sumokėti</a> --}}
                                            <a href="{{ route('user.activeTrade.transportationOrder.index', $trade->id) }}"
                                                class="btn btn-primary btn-sm" role="button">Užsakyti pervežimo
                                                paslaugą</a>
                                        </div>
                                        <div class="mt-2 text-md-center text-sm-center">
                                            <form action="{{ route('user.trade.complete', $trade->id) }}" method="POST"
                                                onsubmit="return submitComplete(this);">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-primary btn-sm">Užbaigti
                                                    Mainus</button>
                                            </form>
                                            <div class="text-center">
                                                <span class="badge bg-success text-wrap">Pasiūlymas buvo priimtas, laukiama
                                                    mainų pabaigos</span>
                                            </div>
                                        </div>
                                    @elseif($trade->status == 'atšaukta')
                                        <div class="mt-2 text-md-end text-sm-end">
                                            <form action="{{ route('user.trade.delete', $trade->id) }}" method="POST"
                                                onsubmit="return submitDelete(this);">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Pašalinti
                                                    pasiūlymą</button>
                                            </form>
                                            <div class="text-center">
                                                <span class="badge bg-danger text-wrap"> Jūsų pasiūlymas buvo atmestas, laukiama
                                                    užklausos pašalinimo.</span>
                                            </div>
                                        </div>                                      
                                    @endif
                                </div>
                            </div>
                        @elseif($trade == null)
                            <div class="col-md-12 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Jūs neturite aktyvių mainymo užklausų</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
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

        <script>
            function submitCancel(form) {
                Swal.fire({
                    title: "Ar tikrai norite atmesti pasiūlymą?",
                    icon: "warning",
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
                            title: 'Pasiūlymas atmestas',
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
                        title: "Ar mainai baigti?",
                        text: "Patvirtinus sutinkate, kad mainai yra pabaigti ir esate gavęs medžiagas",
                        icon: "question",
                        buttons: true,
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Taip, mainai baigti',
                        cancelButtonText: 'Atgal'
                    })
                    .then(function(result) {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Mainai baigti!',
                                text: 'Mainai baigti bei perkelti į archyvą',
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
                    title: "Ar tikrai norite pašalinti mainų pasiūlymą?",
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
                            title: 'Mainų pasiūlymas pašalintas',
                            timer: 3500,
                            icon: 'success',
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
