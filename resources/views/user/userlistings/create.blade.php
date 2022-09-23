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
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    </head>

    <body>
        {{ Breadcrumbs::render('user.userlistings.create')}}
        <div class="container-fluid">
            <section>
                <form action="/asmeniniai_skelbimai" enctype="multipart/form-data" method="post" onsubmit="return submitForm(this);">
                    @csrf
                    <h4 class="text-center"> Skelbimo forma </h4>
                    <hr class="border border-1 border-dark">
                    <div class="form-row justify-content-center ">
                        <div class="col-md-5">
                            <label for="title" class="font-weight-bold">Skelbimo pavadinimas</label>
                            <input id="title" name="title" type="text" class="form-control">
                            @if ($errors->has('title'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="col-md-5">
                            <label for="description" class="font-weight-bold">Skelbimo aprašas</label>
                            <textarea id="description" name="description" cols="10" rows="3" class="form-control"
                                aria-describedby="descriptionHelpBlock" style="resize: none">
                            </textarea>
                            @if ($errors->has('description'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="col-md-5">
                            <label for="listing_type" class="font-weight-bold">Skelbimo tipas</label>
                            <select id="listing_type" name="listing_type" class="custom-select">
                                <option value="parduoti">Parduoti</option>
                                <option value="mainyti">Mainyti</option>
                                <option value="parduoti arba mainyti">Parduoti arba Mainyti</option>
                            </select>
                            @if ($errors->has('listing_type'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('listing_type') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="col-md-5">
                            <label for="location" class="font-weight-bold">Adresas</label>
                            <input id="location" name="location" type="text" class="form-control"
                                aria-describedby="locationHelpBlock">
                            @if ($errors->has('location'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="col-md-5">
                            <label for="listing_image" class="font-weight-bold">Skelbimo nuotraukos</label>
                            <input type="file" class="form-control-file" id="image" name="image" multiple>
                            @if ($errors->has('listing_image'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">
                                        <strong>{{ $errors->first('listing_image') }}</strong>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Product list start -->
                    <hr class="border border-1 border-dark">
                    <h4 class="text-center"> Produktų sąrašas </h4>
                    <div class="table-responsive" id="no-more-tables">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="col-sm-1">Nuotrauka</th>
                                <th class="col-sm-2">Pavadinimas</th>
                                <th class="col-sm-2">Aprašas</th>
                                <th class="col-sm-1">Kategorija</th>
                                <th class="col-sm-1">Min. Kiekis</th>
                                <th class="col-sm-1">Kiekis</th>
                                <th class="col-sm-1">Matavimo vienetas</th>
                                <th class="col-sm-1">Būklė</th>
                                <th class="col-sm-1">Kaina</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr>
                                <td data-title="Nuotrauka">
                                    <input type="file" class="form-control-file" id="product_image" name="product_image[]"
                                        multiple>
                                </td>
                                <td data-title="Pavadinimas">
                                    <input id="name" name="name[]" type="text" class="form-control" required="required">
                                </td>
                                <td data-title="Aprašas">
                                    <textarea id="product_description" name="product_description[]" rows="2" cols="20" class="form-control"
                                        aria-describedby="descriptionHelpBlock" style="resize: none" placeholder="Medžiagos išmatavimai t.t">
                                    </textarea>
                                </td>
                                <td data-title="Kategorija">
                                    <select id="category" name="category[]" class="custom-select">
                                        <option value="">Pasirinkite</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id === old('category_id') ? 'selected' : '' }}>
                                                {{ $category->category_name }}</option>
                                            @if ($category->children)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ $category->id === old('category_id') ? 'selected' : '' }}
                                                        {{ $category->category_name }}>
                                                        {{ $child->id === old('category_id') ? 'selected' : '' }}
                                                        &nbsp;&nbsp;{{ $child->category_name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td data-title="Min. Kiekis">
                                    <input id="min_quantity" name="min_quantity[]" min="1" type="number" class="form-control">
                                </td>
                                <td data-title="Kiekis">
                                    <input id="quantity" name="quantity[]" min="1" type="number" class="form-control"
                                        required="required">
                                </td>
                                <td data-title="Matavimo vienetas">
                                    <select id="measurement" name="measurement[]" class="custom-select">
                                        <option value="vnt">Vienetai (vnt)</option>
                                        <option value="kg">Kilogramai (kg)</option>
                                        <option value="pakuočių">Pakuotė(-ės)</option>
                                        <option value="litrų">Litrai (l)</option>
                                        <option value="m3">Kubinių metrų (m3)</option>
                                    </select>
                                </td>
                                <td data-title="Būklė">
                                    <input id="condition" name="condition[]" type="text" class="form-control"
                                        required="required">
                                </td>
                                <td data-title="Kaina">
                                    <input id="price" name="price[]" step="any" min="0" type="number" class="form-control">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="add_product"><i
                                            class="fa-solid fa-plus"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                        <input type="submit" id="btn-ok" class="btn btn-success" value="Pateikti" />
                        <a href="/asmeniniai_skelbimai" class="btn btn-secondary">Atšaukti</a>
                    </div>
                </form>
            </section>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#add_product').on('click', function() {
                    var html = '';
                    html += '<tr>';
                    html +=
                        '<td data-title="Nuotrauka"><input type="file" class="form-control-file" id="product_image_1" name="product_image[]" multiple></td>';
                    html +=
                        '<td data-title="Pavadinimas"><input id="product" name="name[]" type="text" class="form-control" required="required"></td>';
                    html += 
                        '<td data-title="Aprašas"><textarea id="product_description" name="product_description[]" rows="2" cols="25" class="form-control"aria-describedby="descriptionHelpBlock" style="resize: none" placeholder="Medžiagos išmatavimai t.t"></textarea></td>';
                    html +=
                        '<td data-title="Kategorija"><select id="category" name="category[]" class="custom-select"> <option value="">Pasirinkite kategoriją</option>' +
                        '@foreach ($categories as $category)' +
                            '<option value="{{ $category->id }}"' +
                                ' {{ $category->id === old('category_id') ? 'selected' : '' }}>' +
                                ' {{ $category->category_name }}</option>' +
                            '@if ($category->children)' +
                                '@foreach ($category->children as $child)' +
                                    '<option value="{{ $child->id }}"' +
                                        '{{ $category->id === old('category_id') ? 'selected' : '' }} {{ $category->category_name }}>' +
                                        '{{ $child->id === old('category_id') ? 'selected' : '' }}' +
                                        '&nbsp;&nbsp;{{ $child->category_name }}</option>' +
                                    '@endforeach' +
                            '@endif' +
                        '@endforeach' +
                        '</select>' +
                        '</td>';
                    html += 
                        '<td data-title="Min. Kiekis"><input id="min_quantity" name="min_quantity[]" min="1" type="number" class="form-control" required="required"></td>'
                    html +=
                        '<td data-title="Kiekis"> <input id="quantity" name="quantity[]" min="1" type="number" class="form-control"required="required"></td>';
                    html +=
                        '<td data-title="Matavimo vienetas"><select id="measurment_unit" name="measurement[]" class="custom-select">  <option value="vnt">Vienetai (vnt)</option><option value="kg">Kilogramai (kg)</option><option value="pakuočių">Pakuotė(-ės)</option><option value="litrų">Litrai (l)</option><option value="litrų">Kubinių metrų (m3)</option></select></td>'
                    html +=
                        '<td data-title="Būklė"><input id="product_condition" name="condition[]" type="text" class="form-control" required="required"></td>';
                    html +=
                        '<td data-title="Kaina"><input id="product_price" name="price[]" type="text" class="form-control"></td>';
                    html +=
                        '<td><button type="button" class="btn btn-danger" id="remove_product"><i class="fa-solid fa-xmark"></i></button></td>';
                    html += '</tr>';
                    $('#tbody').append(html);
                })
            });

            $(document).on('click', '#remove_product', function() {
                $(this).closest('tr').remove();
            });
        </script>

<script>
function submitForm(form) {
    Swal.fire({
        title: "Ar tikrai norite pateikti skelbimą?",
        text: "Jūsų skelbimas bus patalpintas aktyvių skelbimų puslapyje po patvirtinimo.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Taip',
        cancelButtonText: 'Atšaukti'
    })
    .then(function (result){
        if(result.isConfirmed)
        {
            form.submit();
        }
        else
        {
            swal("Skelbimas nebuvo pateiktas");
        }
    });
    return false;
}
</script>

    </body>
    </html>
@endsection
@section('scripts')
    @parent
@endsection
