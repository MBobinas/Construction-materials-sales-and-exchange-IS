<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Listing;
use App\Models\TransportationCompany;
use App\Models\TransportationOrder;
use App\Notifications\TransportOrder;

class TransportCheckoutController extends Controller
{
    public function index($id)
    {
        $order = Order::findOrFail($id);
        $transportationCompanies = TransportationCompany::all();

        return view('user.activeOrders.transportationOrder.index', compact('order', 'transportationCompanies'));
    }

    public function payment(Request $request)
    {      
        $this->validate($request, [
            'ts_company' => 'required',
            'city' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ],
        [
            'ts_company.required' => 'Prašome pasirinkti transporto įmonę',
            'city.required' => 'Prašome įvesti miestą',
            'address.required' => 'Prašome įvesti adresą',
            'phone.required' => 'Prašome įvesti telefono numerį',
        ]);

        $order_id = $request->input('order_id');
        $city = $request->city;
        $address = $request->address;
        $phone = $request->phone;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $ts_company_id = $request->input('ts_company');
        $ts_company = TransportationCompany::findOrFail($ts_company_id);
        $amount = $ts_company->service_fee * 100;

        try{         
            $description = 'Pervežimo paslaugos užklausa iš ' . Auth::user()->name;
            $payment_intent = \Stripe\PaymentIntent::create([
                'description' => 'Pervežimo paslaugos užsakymas',
                'amount' => $amount,
                'currency' => 'EUR',
                'description' => $description,
                'payment_method_types' => ['card'],
            ]);
            $intent = $payment_intent->client_secret;   
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Klaida produkto pirkimo užklausoje. Bandykite dar kartą.');
        }  

        return view('user.activeOrders.transportationOrder.payment',compact('intent', 'ts_company_id', 'order_id', 'amount', 'city', 'address', 'phone'));
    }

    public function afterPayment(Request $request)
    {
        $user = Auth::user();
        $order_id = $request->input('order_id');
        $city = $request->input('city');
        $ts_company_id = $request->input('ts_company_id');
        $address = $request->input('address');
        $phone = $request->input('phone');

        $order = Order::findOrFail($order_id);
        $order->transportation_company_id = $ts_company_id;
        $order->transportation_needed = 1;
        $order->order_status = 'pateiktas pervežimo kompanijai';
        $order->save();
       
            $paymentMethod = $request->input('payment_method');
            $stripeCustomer = $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            
            $ts_order = new TransportationOrder();
            $ts_order->order_id = $order_id;
            $ts_order->trade_id = null;
            $ts_order->status = 'laukiama';
            $ts_order->city = $city;
            $ts_order->address =  $address;
            $ts_order->phone = $phone;
            $ts_order->receiver_address = null;
            $ts_order->save();

        $user = Auth::user();
        $notificationData = [
                'body' => 'Sveikiname užsakius pervežimo paslaugą!',
                'notificationText' => 'Atidaryti užsakymų puslapį',
                'url' => route('user.activeOrders.index'),
                'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
            ];
        $user -> notify(new TransportOrder($notificationData));

        return redirect()->route('user.activeOrders.index')->with('success', 'Mokėjimas sėkmingai atliktas! 
        Užsakymo būseną galite stebėti aktyvių užsakymo skiltyje.');
    }

}
