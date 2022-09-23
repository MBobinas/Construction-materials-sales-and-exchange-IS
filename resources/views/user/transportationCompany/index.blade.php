@extends('layouts.transportation_company')
@section('content')

    <head>
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
              {{ Breadcrumbs::render('user.transportationCompany.index') }}
            </ol>
            @if (session('success'))
                <div class="alert alert-success mb-1">
                    {{ session('success') }}
                </div>
            @endif
        </nav>

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                  <div class="card">
                    <div class="card-header p-3 pt-2">
                      <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">weekend</i>
                      </div>
                      <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Gautų užsakymų</p>
                        <h4 class="mb-0">{{ $orders->count() }}</h4>
                      </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                      <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+100% </span>nei praeita savaite</p>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                  <div class="card">
                    <div class="card-header p-3 pt-2">
                      <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">person</i>
                      </div>
                      <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Šiandienos užsakymai</p>
                        <h4 class="mb-0">
                            {{ $todays_orders->count() }}
                        </h4>
                      </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                      <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+1 </span>nei praeita savaitę</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-9 mt-4 mb-4">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">Norint peržiūrėti ar atsispausdinti užsakymųs eikite į "Aktyvūs užsakymai" nuorodą</h6>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
        </div>
    </body>
@endsection
@section('scripts')
    @parent
@endsection
