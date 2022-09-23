@extends('layouts.user')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Medžiagų apmokėjimas</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    @php
        $stripe_key = env('STRIPE_KEY');
    @endphp
     {{ Breadcrumbs::render('user.checkout.credit-card') }}
    <div class="container mt-2 mb-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="">
                    <h4 >Jūsų perkami produktai:</h4>
                    <ul class="list-group">
                    @for($i = 0; $i < count($products); $i++)
                        <li class="list-group-item fw-bold">{{ $products[$i]->product_name }} {{ $products[$i]->price }} € už vnt. <br>
                            Perkamas medžiagos kiekis: {{ $quantity[$i] }} {{ $products[$i]->measurment_unit }}</li>
                    @endfor
                    </ul>
                    <h5 class="m-2 fw-bold">Bendra suma {{ number_format(($amount/100),2) }} €</h5>
                </div>
                <div class="card">
                    <form action="{{route('user.checkout.credit-card')}}" method="post" id="payment-form" onsubmit="return submitForm(this);">
                        @csrf                    
                        <div class="form-group">
                            <div class="card-header">
                                <label for="card-element">
                                    Prašome įvesti kreditinės kortelės informaciją
                                </label>
                            </div>
                            <div class="card-body">
                                <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                                <input type="hidden" name="payment_method" id="payment_method" value="" />
                                @foreach($ordersIds as $orderId)
                                    <input type="hidden" name="order_id[]" value="{{ $orderId }}" />
                                @endforeach

                                <hr>

                                <div class="row g-2">
                                    <div class="col-2">
                                        <div class="input-group input-group-static mt-4">
                                            <label>Miestas<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="city">
                                        </div>
                                    </div>
                                    
                                    <div class="col-5">
                                        <div class="input-group input-group-static mt-4">
                                            <label>Adresas<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="address">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group input-group-static mt-4">
                                            <label>Telefono numeris<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone') ?? Auth::User()->profile->phone }}">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="input-group input-group-static mt-4">
                                            <label>Papildoma informacija siuntėjui</label>
                                            <input type="text" class="form-control" name="moreInfo" placeholder="Kita informacija">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                          <button
                          id="card-button"
                          class="btn btn-success"
                          type="submit"
                          data-secret="{{ $intent }}"
                        > Apmokėti </button>
                        </div>
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </form>
                </div>
                <div class="mt-2 ms-3" style="text-align: right;">
                    <a href="{{ url()->previous() }}"><button id="payment-cancel" class="btn btn-danger"> Atšaukti mokėjimą </button></a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)

        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
    
        const stripe = Stripe('{{ $stripe_key }}', { locale: 'en' }); // Create a Stripe client.
        const elements = stripe.elements(); // Create an instance of Elements.
        const cardElement = elements.create('card', { style: style }); // Create an instance of the card Element.
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        const cardHolderName = {{ auth()->user()->id }};
        $paymentIntent = '{{ $intent }}';
    
        cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.
    
        // Handle real-time validation errors from the card Element.
        cardElement.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    
        // Handle form submission.
        var form = document.getElementById('payment-form');
    
        form.addEventListener('submit', function(event) {
            event.preventDefault();
    
        stripe.handleCardPayment(clientSecret, cardElement, {
            setup_future_usage: 'off_session',
                payment_method_data: {
                    billing_details: { 
                        name: cardHolderName.value,
                    },
                }
            })
            .then(function(result) {
                console.log(result);
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    $('#payment_method').val(result.paymentIntent.payment_method);
                   // console.log(result);
                    form.submit();
                }
            });
        });
    </script>

<script>
    function submitForm(form) {
        Swal.fire({
            title: "Patvirtinate apmokėjimą?",
            text: "Patvirtinus apmokėjimą, jūsų užsakymas bus išsiųstas naudotojui",
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
                    title: 'Mokėjimas patvirtintas',
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
</html>