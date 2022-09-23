@extends('layouts.user')
@section('content')

    <head>
        <link id="pagestyle" href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>

        <style>
            a{
                text-decoration: none;
                color:black;
            }
        </style>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item my-auto">
                    {{ Breadcrumbs::render('user.archives.index') }}
                </li>               
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>
        <div class="container-fluid mt-3">
            <div class="col-md-11 text-center">
                <h4>Pabaigti skelbimai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('user.archives.index') }}" class="btn btn-outline-primary active"
                        aria-current="page">Archyvuoti skelbimai</a>
                    <a href="{{ route('user.archives.orders') }}" class="btn btn-outline-primary">Archyvuoti sandoriai</a>
                </div>
            </div>

            <div class="d-flex row">
                <div class="col-md-9 d-grid gap-2">
                    @foreach ($listings as $listing)
                        <div class="row p-2 bg-white border rounded">
                            <div class="col-md-3 mt-1"><img class="img-fluid img-responsive rounded product-image h-100"
                                    src="{{ asset('/storage/listing/' . $listing->image) }}"></div>
                            <div class="col-md-6 mt-1">
                                <h5>{{ $listing->title }}</h5>
                                <div class="mt-1 mb-1 spec-1 border-top">
                                    @foreach ($listing->products as $product)
                                        <span class="fw-bold">{{ $product->category->category_name }} | </span>
                                    @endforeach
                                </div>
                                <p class="text-justify para text-wrap mb-0">{{ $listing->description }}<br><br></p>
                                <span class="text-sm-start text-uppercase fw-bold"><i class="fa-solid fa-tag"></i>
                                    {{ $listing->listing_type }}</span><br>
                            </div>
                            <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                                @if($listing->user_id == Auth::user()->id)
                                    <div class="d-flex flex-row text-center">
                                        <span class="badge text-info bg-light"> Jūsų skelbimas </span>
                                    </div>
                                @endif
                                <h6 class="text-success"><i class="fa-solid fa-location-dot"></i>
                                    {{ $listing->location }}</h6>
                                <div class="d-flex flex-column mt-8">
                                <form action ="{{ route('user.listings.destroy', $listing->id) }}" method="POST" onsubmit="return submitForm(this);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Pašalinti</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $listings->links('pagination::bootstrap-4') }}
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
                        title: "Ar tikrai norite pašalinti šį skelbimą?",
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
                                title: 'Skelbimas pašalintas',
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
