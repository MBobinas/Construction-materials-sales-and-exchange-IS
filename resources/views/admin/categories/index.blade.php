@extends('layouts.admin')
@section('content')
    <!doctype html>
    <html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <script src="{{ asset('js/app.js') }}" defer></script>

    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                {{ Breadcrumbs::render('admin.categories.index') }}
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger fw-bold text-dark" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
        </nav>

        <div class="container">
            <div class="modal" tabindex="-1" role="dialog" id="editCategoryModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Redaguoti kategorijÄ…</h5>

                            <button type="button" class="btn btn-danger close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="" method="POST"  onsubmit="return submitEdit(this);">
                            @csrf
                            @method('PATCH')

                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name="category_name" class="form-control border rounded ps-1"
                                        value="" placeholder="Kategorijos pavadinimas" required>
                                    @if ($errors->has('category_name'))
                                        <div class="alert alert-danger text-white text-sm">
                                            <div class="error">
                                                {{ $errors->first('category_name') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">UÅ¾daryti</button>
                                <button type="submit" class="btn btn-primary">Atnaujinti</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Kategorijos</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($categories as $category)
                                    <li class="list-group-item text-dark fw-bold">
                                        <div class="d-flex justify-content-between">
                                            {{ $category->category_name }}

                                            <div class="button-group d-flex">
                                                <button type="button" class="btn btn-sm btn-info me-1 mt-2 edit-category"
                                                    data-toggle="modal" data-target="#editCategoryModal"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->category_name }}">Redaguoti</button>

                                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger mt-2">PaÅ¡alinti</button>
                                                </form>
                                            </div>
                                        </div>

                                        @if ($category->children)
                                            <ul class="list-group">
                                                @foreach ($category->children as $child)
                                                    <li class="list-group-item fst-italic">
                                                        <div class="d-flex justify-content-between">
                                                            {{ $child->category_name }}

                                                            <div class="button-group d-flex">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info me-1 mt-2 edit-category"
                                                                    data-toggle="modal" data-target="#editCategoryModal"
                                                                    data-id="{{ $child->id }}"
                                                                    data-name="{{ $child->category_name }}">Redaguoti</button>

                                                                <form
                                                                    action="{{ route('admin.categories.destroy', $child->id) }}"
                                                                    method="POST" onsubmit="return submitDelete(this);">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger mt-2">PaÅ¡alinti</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>PridÄ—ti naujÄ… kategorijÄ…</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.categories.store') }}" method="POST"
                                onsubmit="return submitForm(this);">
                                @csrf

                                <div class="form-group mt-2">
                                    <select class="form-select ps-1 border" name="parent_id">
                                        <option value="">Pasirinkite kategorijÄ…</option>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-static mt-2">
                                    <label>Kategorijos pavadinimas</label>
                                    <input type="text" name="category_name" class="form-control ms-1"
                                        value="{{ old('category_name') }}"
                                        placeholder="Ä®veskite kategorijos/pakategorÄ—s pavadinimÄ…" required>
                                    @if ($errors->has('category_name'))
                                        <div class="alert alert-danger text-white text-sm">
                                            <div class="error">
                                                {{ $errors->first('category_name') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">PridÄ—ti</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script type="text/javascript">
            $('.edit-category').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var url = "{{ url('medÅ¾iagos_kategorija') }}/" + id;

                $('#editCategoryModal form').attr('action', url);
                $('#editCategoryModal form input[name="category_name"]').val(name);
            });
        </script>
    </body>

    <script>
        function submitForm(form) {
            Swal.fire({
                title: "PridÄ—ti naujÄ… kategorijÄ…?",
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
                        title: 'Kategorija pridÄ—ta',
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
        function submitDelete(form) {
            Swal.fire({
                title: "Ar norite paÅ¡alinti kategorijÄ…?",
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
                        title: 'Kategorija paÅ¡alinta.',
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
    function submitEdit(form) {
        Swal.fire({
            title: "Ar patvirtinate korekcijÄ…?",
            icon: "question",
            buttons: true,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Taip',
            cancelButtonText: 'Atgal'
        }).then(function(result) {
            if (result.isConfirmed) {
                    form.submit();
                }
            });
        return false;
    }
</script>

    </html>
@endsection
