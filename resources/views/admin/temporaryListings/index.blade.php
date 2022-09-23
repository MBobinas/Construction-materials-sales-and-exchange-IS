@extends('layouts.admin')
@section('content')

    <head>
        <link id="pagestyle" href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>

        <style>
            a {
                color: #000;
            }

            form {
                display: inline;
            }

        </style>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-default">
                    {{ Breadcrumbs::render('admin.temporaryListings.index') }}
                </li>
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>
        <div class="container-fluid mt-1">
            @if ($listings->count() == 0)
                <div class="col">
                    <div class="card text-center text-white">
                        <div class="card-body">
                            <h4>Šiuo metu nėra jokių skelbimų, kuriuos reikėtų patvirtinti.</h4>
                        </div>
                    </div>
                </div>
            @else
                <div class="d-flex row">
                    <div class="col">
                    </div>
                    <div class="col-9 col-md-9 col-sm-9 d-grid gap-2">
                        @foreach ($listings as $listing)
                            <div class="row p-2 bg-white border rounded">
                                <div class="row g-0">
                                    <div class="col-6 col-md-6 col-sm-6 text-sm-start">
                                        <div class="ms-3">
                                            <a href="/skelbimai/{{ $listing->id }}">
                                                <h5>{{ $listing->title }}
                                            </a></h5>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-6 text-sm-end">
                                        <span class="badge bg-secondary text-wrap">Skelbimas patalpintas
                                            {{ $listing->created_at->format('m/d/Y H:i') }} </span>
                                        <a href="/skelbimai/{{ $listing->id }}"><i class="fa-solid fa-eye"></i></a>
                                    </div>
                                </div>


                                <div class="col-3 col-md-3 col-sm-3 mt-1"><img
                                        class="img-fluid img-responsive rounded h-100"
                                        src="{{ asset('/storage/listing/' . $listing->image) }}">
                                </div>

                                <div class="col-4 col-md-4 col-sm-4 mt-1">
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


                                <div
                                    class="align-items-center align-content-center col-5 col-md-5 col-sm-5 border-left mt-1">
                                    @if (Auth::user()->isAdministrator())
                                        <div class="d-flex flex-row align-items-center ms-6">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <h6 class="mr-1">Skelbimo sąvininkas:
                                                        {{ $listing->user->name }} </h6>
                                                </li>
                                                <li class="list-group-item">
                                                    El. Paštas:<span class="text-info">
                                                        {{ $listing->user->email }}</span>
                                                </li>
                                                <li class="list-group-item">
                                                    Tel. Nr:<span class="text-info">
                                                        {{ $listing->user->profile->phone }}</span>
                                                </li>
                                                <li class="list-group-item">
                                                    <h6 class="text-info"><i class="fa-solid fa-location-dot"></i>
                                                        {{ $listing->location }}</h6>
                                                </li>
                                            </ul>
                                        </div>

                                        @if ($listing->listing_type == 'parduoti' || $listing->listing_type == 'parduoti arba mainyti')
                                            @if ($listing->total_sum != 0)
                                                <div class="d-flex flex-row align-items-center">
                                                    <h4 class="mr-1">{{ $listing->total_sum }} €</h4>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="d-grid gap-2 d-md-block ms-6">
                                            <form action="{{ route('admin.temporaryListings.update', $listing->id) }}"
                                                method="POST" onsubmit="return submitAccept(this);">
                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-icon btn-3 btn-success" type="submit">
                                                    <span class="btn-inner--text">Patvirtinti skelbimą </span>
                                                    <span class="btn-inner--icon ms-1"><i class="fa-solid fa-check fa-lg"
                                                            title="Patvirtinti"></i></span>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.listingDestroy', $listing->id) }}"
                                                method="POST" onsubmit="return submitDecline(this);">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-icon btn-3 btn-danger">
                                                    <span class="btn-inner--text"> Atmesti skelbimą </span>
                                                    <span class="btn-inner--icon ms-1"><i class="fa fa-trash fa-lg"
                                                            title="Atmesti"></i></span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
            @endif
            <div class="col">

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>

        <script>
            function submitDecline(form) {
                Swal.fire({
                    title: "Norite atmesti šį skelbimą?",
                    icon: "question",
                    input: 'text',
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
                            title: 'Skelbimas atmestas',
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
            function submitAccept(form) {
                Swal.fire({
                    title: "Ar patvirtinate šį skelbimą?",
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
                            title: 'Skelbimas patvirtintas',
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
