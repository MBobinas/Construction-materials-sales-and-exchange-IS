<!doctype html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Užsakymas - {{ $ts_order->id }}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: DejaVu Sans !important;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
            border-collapse: collapse;
            text-align: center;
	        vertical-align: middle;
        }

        .invoice th tr {
            margin: 2rem;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table  {
            padding: 10px;
        }

        .fee {
            margin-top: 2rem;
            margin-left: 1rem;
        }

        .invoice #tableBlet th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        thead th {
            width: 25%;
        }

    </style>

</head>

<body>

    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" class="information" style="width: 40%;">
                    <h3>Užsakovas: {{ $ts_order->order->user->name }}</h3>
                    <pre>
{{ $ts_order->address }}
{{ $ts_order->city }}
{{ $ts_order->order->phone }}
<br /><br />
{{ $ts_order->created_at }}
Užsakymo numeris: {{ $ts_order->id }}
Būsena: Apmokėtas
</pre>


                </td>
                <td align="right" class="information" style="width: 40%;">

                    <h3>ConstructIS</h3>
                    <pre>
                    http://matasbob.isk.ktu.lt/
                </pre>
                </td>
            </tr>

        </table>
    </div>


    <br />

    <div class="invoice">
        <h3>Užsakymas #{{ $ts_order->id }}</h3>
        <table class="invoice">
            <thead>
                <tr class="invoice">
                    <th>Vardas</th>
                    <th>Kiekis</th>
                    <th>Aprašas</th>
                    <th>Miestas</th>
                    <th>Adresas</th>
                </tr>
            </thead>
            <tbody>
                <tr class="invoice">
                    <td>{{ $ts_order->order->product->product_name }}</td>
                    <td>{{ $ts_order->order->quantity }} {{ $ts_order->order->product->measurment_unit }}</td>
                    <td>{{ $ts_order->order->product->description }}</td>
                    <td>{{ $ts_order->city }}</td>
                    <td>{{ $ts_order->address }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="fee">
        <h5>Pristatymo kaina: {{ $ts_order->order->transportationCompany->service_fee }} Eur</h5>
    </div>

    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
                </td>
                <td align="right" style="width: 50%;">
                    Construct ir KO
                </td>
            </tr>

        </table>
    </div>
</body>

</html>
