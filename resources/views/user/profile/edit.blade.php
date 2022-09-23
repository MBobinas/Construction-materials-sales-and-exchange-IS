@extends('layouts.user')
@section('content')
    <style>
        a {
            text-decoration: none;
        }

    </style>

    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        {{ Breadcrumbs::render('user.profile.edit', $user) }}
        <div class="container">
            <form action="{{ route('user.profile.update', $user->id) }}" enctype="multipart/form-data" method="post"
                onsubmit="return submitForm(this);">
                @csrf
                @method('PATCH')

                <div class="row" style="justify-content: center">
                    <div class="col-8 offset-2">
                        <div class="row">
                            <h1>Redaguoti Profilį</h1>
                        </div>
                        <div class="form-group row" style="width: 50%">
                            <label for="name" class="col-md-4 col-form-label font-weight-bold"> Vardas </label>

                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                name="name" value="{{ old('name') ?? $user->name }}" autocomplete="name" autofocus>

                            @if ($errors->has('name'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('name') }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row" style="width: 50%">
                            <label for="email" class="col-md-4 col-form-label font-weight-bold"> El. Paštas </label>

                            <input id="email" type="text"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email"
                                value="{{ old('email') ?? $user->email }}" autocomplete="email" autofocus>

                            @if ($errors->has('email'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('email') }}
                                    </div>
                                </div>
                            @endif
                        </div>


                        <div class="form-group row" style="width: 50%">
                            <label for="phone" class="col-md-4 col-form-label font-weight-bold"> Telefono Numeris </label>

                            <input id="phone" type="text"
                                class="form-control{{ $errors->has('phone') ? 'is-invalid' : '' }}" name="phone"
                                value="{{ old('phone') ?? $user->profile->phone }}" autocomplete="phone" autofocus>
                            <span class="text-muted"> <i> (Pvz.: +370 66666666)</i> </span>

                            @if ($errors->has('phone'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('phone') }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row" style="width: 50%">
                            <label for="description" class="col-md-4 col-form-label font-weight-bold"> Aprašas </label>
                            <textarea class="form-control" rows="5" id="description" type="text" style="resize: none;" name="description"
                                class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                autocomplete="description" autofocus> {{ old('description') ?? $user->profile->description }}
                            </textarea>

                            @if ($errors->has('description'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group row" style="width: 50%">
                            <label for="birth_date" class="col-md-4 col-form-label font-weight-bold"> Gimimo data </label>

                            <input id="birth_date" type="date"
                                class="form-control{{ $errors->has('birth_date') ? 'is-invalid' : '' }}"
                                name="birth_date" value="{{ old('birth_date') ?? $user->profile->birth_date }}"
                                autocomplete="birth_date" autofocus>

                            @if ($errors->has('birth_date'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('birth_date') }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <label for="image" class="col-md-4 col-form-label font-weight-bold">Pakeisti Profilio
                                Nuotrauką</label>

                            <input type="file" class="form-control-file" id="image" name="image">
                            @if ($errors->has('image'))
                                <div class="alert alert-danger text-white text-sm">
                                    <div class="error">
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row pt-4" style="width: 50%">
                            <button class="btn btn-success">Pakeisti informaciją</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <script>
            function submitForm(form) {
                Swal.fire({
                    title: "Ar tikrai norite pakeisti paskyros informaciją?",
                    text: "Paskyros informacija bus pakeista naujai įvesta informacija.",
                    icon: "question",
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
                        Swal.fire({
                            title:'Paskyros informacija pakeista',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        })
                        form.submit();
                    }
                    else
                    {
                        Swal.fire({
                            title: "Paskyros informacija nepakeista",
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
