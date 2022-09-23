@extends('layouts.admin')
@section('content')

    <head>
        <link id="pagestyle" href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-sm"><a class="text-dark" href="/admin/skelbimai">Skelbimai</a></li>
                <li class="breadcrumb-item text-sm active" aria-current="page">{{ $category_name }}</li>
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>
        
        <div class="container-fluid mt-2">
            <div class="d-flex row">
                <div class="col-md-9 gap-2">
                    @foreach ($listings as $listing)
                        <div class="row p-2 mb-2 bg-white border rounded">
                            <div class="col-md-3 mt-1"><img class="img-fluid img-responsive rounded h-100"
                                    src="{{ asset('/storage/listing/' . $listing->image) }}"></div>
                            <div class="col-md-6 mt-1">
                                <h5>{{ $listing->title }}</h5>
                                <div class="mt-1 mb-1 spec-1">
                                    @foreach ($listing->products as $product)
                                        <span class="fw-bold">{{ $product->category->category_name }} | </span>
                                    @endforeach
                                </div>
                                <p class="text-justify para text-wrap mb-0">{{ $listing->description }}<br><br></p>
                                <span class="text-sm-start text-uppercase fw-bold"><i class="fa-solid fa-tag"></i>
                                    {{ $listing->listing_type }}</span><br>
                            </div>
                            <div class="align-items-center align-content-center col-md-3 border-left mt-1">                               
                                <h6 class="text-success"><i class="fa-solid fa-location-dot"></i>
                                    {{ $listing->location }}</h6>
                                <div class="d-flex flex-column mt-8">
                                    <a href="{{ route('admin.specificListing.index', $listing->id) }}" class="btn btn-primary btn-sm"
                                        role="button">Peržiūrėti</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-1">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-row">
                        </div>
                    </div>
                </div>
                @if(count($categories) > 0)
                <div class="col-md-2 bg-gradient-dark ps border rounded">
                    <div class="d-flex flex-column text-center mt-1">
                        <h5 class="text-white">Kategorijų parinktys</h5>
                    </div>
                    <div class="accordion">
                        @foreach ($categories as $category)
                            <div class="accordion-item">
                                <button class="accordion-header">
                                    <a href="{{ route('admin.category.index', $category->category_name) }}"
                                        class="btn btn-link text-white">{{ $category->category_name }}
                                    </a>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
