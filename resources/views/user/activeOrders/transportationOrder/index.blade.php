@extends('layouts.user')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Pervežimo užsakymas</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
        {{ Breadcrumbs::render('user.checkout.credit-card') }}
        <div class="container mt-2 mb-2">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="mb-2">
                        <h4>Pervežimo paslaugos užsakymo duomenų įvedimas</h4>
                        <div class="col-md-12" style="text-align: right;">
                            <button type="button" class="btn bg-gradient-info" data-container="body"
                            data-bs-toggle="popover" data-color="primary" data-bs-placement="left"
                            data-bs-content="Pervežimo paslaugas suteikia Terminality kurjerių tarnyba, kainynas pateikiamas priklausant nuo medžiagų svorio.
                            Žemiau pateikiamos pristatymo kainos. Iškrovimo ir užnešimo paslaugos į kainą neįskaičiuotos. 
                            Jeigu reikalingos papildomos paslaugos - prašome kreiptis el. paštu constructWise@app.com">
                            <i class="fas fa-question-circle"></i>
                            </button>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item fw-bold">Užsakoma pervežimo paslauga pateiktam užsakymui:
                                {{ $order->product->product_name }}
                                {{ $order->product->price }} € už vnt. <br>
                                Perkamas medžiagos kiekis: {{ $order->quantity }} {{ $order->product->measurment_unit }}
                                <br>
                                Sumokėta užsakymo suma: {{ $order->price }} €
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <form action="{{ route('user.activeOrders.transportationOrder.payment') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="card-header">
                                    <label for="card-element">
                                        <h6>Prašome įvesti užsakymo informaciją apačioje</h6>
                                    </label>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <div class="input-group input-group-static">
                                                <label for="ts_company_select" class="ms-0">Pervežimo
                                                    įmonės pasirinkimas</label>
                                                <select class="form-control" id="ts_company_select" name="ts_company">
                                                    @foreach ($transportationCompanies as $company)
                                                        <option value="{{ $company->id }}">
                                                            {{ $company->name }} - {{ $company->service_fee }} €
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="input-group input-group-static">
                                                <label>Jūsų Miestas<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="city">

                                                @if ($errors->has('city'))
                                                    <div class="text-danger text-sm">
                                                        <div class="error">
                                                            {{ $errors->first('city') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="input-group input-group-static">
                                                <label>Jūsų Adresas<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="address">

                                                @if ($errors->has('address'))
                                                    <div class="text-danger text-sm">
                                                        <div class="error">
                                                            {{ $errors->first('address') }}
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="input-group input-group-static">
                                                <label>Jūsų Telefono Numeris<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="phone"
                                                    value="{{ old('phone') ?? Auth::User()->profile->phone }}">

                                                @if ($errors->has('phone'))
                                                    <div class="text-dager text-sm">
                                                        <div class="error">
                                                            {{ $errors->first('phone') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}" />
                            <div class="card-footer">
                                <button id="card-button" class="btn btn-success" type="submit"> Pereiti į apmokėjimą
                                </button>
                            </div>
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </form>
                    </div>
                    <div class="mt-2 ms-3" style="text-align: right;">
                        <a href="{{ url()->previous() }}"><button id="payment-cancel" class="btn btn-danger"> Atšaukti
                                mokėjimą </button></a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            $(function() {
                $('[data-bs-toggle="popover"]').popover()
            })
        </script>
        
    </body>
@endsection
@section('scripts')
    @parent
@endsection

</html>
