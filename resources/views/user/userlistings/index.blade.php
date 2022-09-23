@extends('layouts.user')
@section('content')
    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('user.userlistings.show')}}
                </li>
                <li class="mx-2 my-2">
                    <a href="{{ route('user.userlistings.create') }}" class="btn btn-primary">Pridėti naują skelbimą</a>
                </li>
            </ol>
            {{ $listings->links('pagination::bootstrap-4') }}
            @if (session('success'))
                <div class="alert alert-success text-white">
                    {{ session('success') }}
                </div>
            @endif
        </nav>

        <div class="container-fluid content-row">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @if($listings->count() == 0)
                    <div class="col-md-11 mt-1 text-center">
                        <div class=card>
                            <div class=card-body>
                                <h5>Šiuo metu skelbimų nėra...</h5>
                            </div>
                        </div>
                    </div>
                @endif
                @foreach ($listings as $listing)
                    <div class="col">
                        <div class="card m-1 h-100">
                            <img class="card-img-top img-fluid w-100" src="{{ asset('/storage/listing/' . $listing->image) }}" />
                            <div class="card-body">
                                <h3 class="card-title">{{ $listing->title }}</h3>
                                <h6 class="card-text"><span class="label label-default">Būsena:
                                    </span>{{ $listing->status }}</h6>
                                <p class="card-text"><span class="label label-default">Aprašas:
                                    </span>{{ $listing->description }}</p>
                                <p class="card-text"><span class="label label-default">Produktai:
                                    @foreach($listing->products as $product)
                                        {{ $product->product_name }}
                                    @endforeach
                                </p>
                                <p class="card-text"><span class="label label-default">Adresas:
                                </span>{{ $listing->location }}</p>
                                <form action="{{ url('/asmeniniai_skelbimai/istrinti', $listing->id) }}" method="POST" onsubmit="return submitForm(this);">
                                    @method('DELETE')
                                    @csrf
                                    <a href="/asmeniniai_skelbimai/{{ $listing->id }}/redaguoti"
                                        class="btn btn-outline-secondary">Redaguoti</a>
                                    <button type="submit" class="btn btn-outline-danger">Pašalinti</button>
                                </form>
                            </div>
                            <div class="card-footer text-center fw-bold text-uppercase">
                                {{ $listing->listing_type }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                {{ $listings->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <script>
            function submitForm(form) {
                Swal.fire({
                    title: "Ar tikrai norite pašalinti skelbimą?",
                    text: "Jūsų skelbimas bus pašalintas iš sistemos.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Pašalinti',
                    cancelButtonText: 'Atšaukti'
                })
                .then(function (result){
                    if(result.isConfirmed)
                    {
                        form.submit();
                    }
                    else
                    {
                        Swal.fire({
                            title: "Skelbimas nepašalintas",
                            text: "Jūs atšaukėte pašalinimą.",
                            icon: "error",
                            confirmButtonColor: '#198754',
                            confirmButtonText: 'Gerai'
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
