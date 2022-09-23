@extends('layouts.user')
@section('content')
    <style>
        body {
            font-family: roboto;
        }
        @media only screen and (max-width:800px) {
            #no-more-tables tbody,
            #no-more-tables tr,
            #no-more-tables td {
                display: block;
            }
            #no-more-tables thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            #no-more-tables td {
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #eee;
            }
            #no-more-tables td:before {
                content: attr(data-title);
                position: absolute;
                left: 6px;
                font-weight: bold;
            }
            #no-more-tables tr {
                border-bottom: 1px solid #ccc;
            }
        }
    </style>

    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    </head>

    <body>
        {{ Breadcrumbs::render('user.userlistings.edit', $listing) }}
        <div class="container-fluid">
            <section>
                <form action="/asmeniniai_skelbimai/{{ $listing->id }}" enctype="multipart/form-data" method="post" onsubmit="return submitForm(this);">
                    @csrf
                    @method('PATCH')
                    <h4 class="text-center"> Redaguoti skelbimą </h4>
                    <hr class="border border-1 border-dark">

                    <div class="row">
                        <div class="col-4">
                            <img class="img-fluid img-thumbnail" src="{{ '/storage/listing/' . $listing->image }}"
                                alt="{{ $listing->title }}">
                            <label for="image" class="col-form-label">Keisti nuotrauką</label>
                            <input type="file" class="form-control-file" id="image" name="image" multiple>
                            @if ($errors->has('image'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-8">
                            <div class="form-row">
                                <div class="col-md-7">
                                    <label for="title" class="font-weight-bold">Skelbimo pavadinimas</label>
                                    <input id="title" name="title" type="text"
                                        class="form-control {{ $errors->has('title') ? 'is-invalid' : 'Įveskite pavadinimą' }}"
                                        value="{{ old('title') ?? $listing->title }}"
                                        required>
                                    @if ($errors->has('title'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-text">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row ">
                                <div class="col-md-7">
                                    <label for="description">Skelbimo Aprašas</label>
                                    <textarea id="description" name="description" cols="40" rows="5" required
                                        class="form-control {{ $errors->has('description') ? 'is-invalid' : 'Nėra pateikta aprašo' }}"
                                        aria-describedby="descriptionHelpBlock" style="resize: none;"
                                        autocomplete="description"> {{ old('description') ?? $listing->description }}                                      
                                    </textarea>
                                    @if ($errors->has('description'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-text">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-7">
                                    <label for="location">Adresas</label>
                                    <input id="location" name="location" type="text"
                                        class="form-control {{ $errors->has('location' ? 'is-invalid' : 'Pateikite vietą') }}"
                                        value="{{ old('location') ?? $listing->location }}"
                                        aria-describedby="locationHelpBlock" autocomplete="location" autofocus required>
                                    @if ($errors->has('location'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-text">
                                                <strong>{{ $errors->first('location') }}</strong>
                                            </span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-7">
                                    <label for="listing_type">Skelbimo tipas</label>
                                    <select id="listing_type" name="listing_type" class="custom-select" required>
                                        <option value="parduoti">Parduoti</option>
                                        <option value="mainyti">Mainyti</option>
                                        <option value="parduoti arba mainyti">Parduoti arba Mainyti</option>
                                    </select>
                                    @if ($errors->has('listing_type'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <span class="alert-text">
                                                <strong>{{ $errors->first('listing_type') }}</strong>
                                            </span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product list start -->
                    <hr class="border border-1 border-dark">
                    <h4 class="text-center">Redaguoti produktų sąrašą </h4>
                    <div class="table-responsive" id="no-more-tables">
                        <table class="table table-bordered table-hover caption-top">
                            <caption>Skelbime esančių produktų sąrašas</caption>
                            <thead class="table-dark">
                                <tr>
                                    <th>Nuotrauka</th>
                                    <th>Pavadinimas</th>
                                    <th>Aprašas</th>
                                    <th>Kategorija</th>
                                    <th>Min. Kiekis</th>
                                    <th>Kiekis</th>
                                    <th>Matavimo vienetas</th>
                                    <th>Būklė</th>
                                    <th>Kaina</th>
                                </tr>
                            </thead>
                            @foreach ($products as $product)
                                <tbody>
                                    <tr class="align-middle">
                                        <td data-title="Nuotrauka">
                                            <img src="{{ '/storage/product/' . $product->image }}"
                                                alt="{{ $product->title }}" width="100" height="100">
                                            <input type="file" class="form-control-file" id="product_image"
                                                name="product_image[]" multiple>
                                            @if ($errors->has('product_image'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('product_image') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle" data-title="Pavadinimas">
                                            <input id="product_name" type="text"
                                                class="form-control{{ $errors->has('product_name') ? 'is-invalid' : '' }}"
                                                name="product_name[]"
                                                value="{{ old('product_name') ?? $product->product_name }}"
                                                autocomplete="product_name" autofocus required>

                                            @if ($errors->has('product_name'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('product_name') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="align-middle" data-title="Aprašas">
                                            <textarea class="form-control" rows="2" id="product_description" type="text" style="resize: none;" name="product_description[]"
                                            class="form-control {{ $errors->has('product_description') ? 'is-invalid' : '' }}"
                                            autocomplete="product_description" autofocus> {{ old('product_description') ?? $product->description }}
                                            </textarea>

                                            @if ($errors->has('product_description'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('product_description') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="align-middle" data-title="Kategorija">
                                            <select id="category" name="category[]" class="custom-select" required>                                              
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                    @if ($category->children)
                                                        @foreach ($category->children as $child)
                                                            <option value="{{ $child->id }}"
                                                                {{ $product->category->id === $child->id ? 'selected' : '' }}
                                                                {{ $category->category_name }}>
                                                                &nbsp;&nbsp;{{ $child->category_name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle" data-title="Min. Kiekis">
                                            <input id="min_quantity" type="number" step="any" min="0"
                                                class="form-control{{ $errors->has('min_quantity') ? 'is-invalid' : '' }}"
                                                name="min_quantity[]" value="{{ old('min_quantity') ?? $product->min_quantity }}"
                                                autocomplete="min_quantity" autofocus required>

                                            @if ($errors->has('min_quantity'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('min_quantity') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle" data-title="Kiekis">
                                            <input id="quantity" type="number" step="any" min="0"
                                                class="form-control{{ $errors->has('quantity') ? 'is-invalid' : '' }}"
                                                name="quantity[]" value="{{ old('quantity') ?? $product->quantity }}"
                                                autocomplete="quantity" autofocus required>

                                            @if ($errors->has('quantity'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('quantity') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle" data-title="Matavimo vienetas">
                                            <select id="measurment_unit" name="measurment_unit[]" required
                                                class="custom-select">
                                                <option value="{{ $product->measurment_unit }}">
                                                    {{ $product->id === old('measurment_unit') ? 'selected' : '' }}
                                                    {{ $product->measurment_unit }}</option>
                                                </option>
                                        </td>
                                        <td class="align-middle" data-title="Būklė">
                                            <input id="condition" type="text"
                                                class="form-control{{ $errors->has('condition') ? 'is-invalid' : '' }}"
                                                name="condition[]" value="{{ old('condition') ?? $product->condition }}"
                                                autocomplete="condition" required autofocus>

                                            @if ($errors->has('condition'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('condition') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        @if($product->price == 0 || $product->price == null)
                                            <td class="align-middle" data-title="Kaina">
                                                <input class="form-control" type="text" placeholder="0.00" disabled>
                                            </td>
                                        @else
                                        <td class="align-middle" data-title="Kaina">
                                            <input id="price" type="number" step="any" min="0"
                                                class="form-control{{ $errors->has('price') ? 'is-invalid' : '' }}"
                                                name="price[]" value="{{ old('price') ?? $product->price }}"
                                                autocomplete="price" required autofocus>

                                            @if ($errors->has('price'))
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <span class="alert-text">
                                                        <strong>{{ $errors->first('price') }}</strong>
                                                    </span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                        @endif
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="btn-ok" class="btn btn-success" value="Pateikti" />
                        <a href="/asmeniniai_skelbimai" class="btn btn-secondary">Atšaukti</a>
                    </div>
                </form>
            </section>
        </div>

        <script>
            function submitForm(form) {
                Swal.fire({
                    title: "Ar tikrai norite pakeisti skelbimo informaciją?",
                    text: "Jums patvirtinus skelbimo informacija bus pakeista.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Pakeisti informaciją',
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
                            title: "Skelbimo informacija nebuvo pakeista.",
                            text: "Nusprendėte paredaguoti skelbimą.",
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
