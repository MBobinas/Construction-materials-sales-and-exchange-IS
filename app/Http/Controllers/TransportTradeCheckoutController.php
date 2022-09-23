<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trade;
use App\Models\User;
use App\Models\Listing;
use App\Models\TransportationCompany;
use App\Models\TransportationOrder;
use App\Notifications\TransportOrder;

class TransportTradeCheckoutController extends Controller
{
    public function index($id)
    {
        $trade = Trade::findOrFail($id);
        $transportationCompanies = TransportationCompany::all();

        return view('user.activeTrade.transportationOrder.index', compact('trade', 'transportationCompanies'));
    }

    public function payment(Request $request)
    {
        $this->validate($request, [
            'ts_company' => 'required',
            'city' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'receiver_address' => 'required',
        ],[
            'ts_company_id.required' => 'Pasirinkite transporto paslaugą',
            'city.required' => 'Įveskite miestą',
            'address.required' => 'Įveskite savo adresą',
            'phone.required' => 'Įveskite savo telefono numerį',
            'receiver_address.required' => 'Įveskite gavėjo adresą',
        ]);

        $trade_id = $request->input('trade_id');
        $receiver_address = $request->receiver_address;
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

        return view('user.activeTrade.transportationOrder.payment',compact('intent', 'ts_company_id', 'trade_id', 'amount', 'city', 'address', 'phone', 'receiver_address'));
    }

    public function afterPayment(Request $request)
    {
        $this->validate($request, [
            'ts_company_id' => 'required',
            'city' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'receiver_address' => 'required',
        ],[
            'ts_company_id.required' => 'Pasirinkite transporto paslaugą',
            'city.required' => 'Įveskite miestą',
            'address.required' => 'Įveskite savo adresą',
            'phone.required' => 'Įveskite savo telefono numerį',
            'receiver_address.required' => 'Įveskite gavėjo adresą',
        ]);

        $user = Auth::user();
        $trade_id = $request->input('trade_id');
        $city = $request->input('city');
        $ts_company_id = $request->input('ts_company_id');
        $address = $request->input('address');
        $phone = $request->input('phone');
        $receiver_address = $request->input('receiver_address');

        $trade = Trade::findOrFail($trade_id);
        $trade->transportation_company_id = $ts_company_id;
        $trade->transportation_needed = 1;
        $trade->save();
       
        try{
            $paymentMethod = $request->input('payment_method');
            $stripeCustomer = $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            
            $ts_order = new TransportationOrder();
            $ts_order->order_id = null;
            $ts_order->trade_id = $trade_id;
            $ts_order->status = 'laukiama';
            $ts_order->city = $city;
            $ts_order->address =  $address;
            $ts_order->phone = $phone;
            $ts_order->receiver_address = $receiver_address;
            $ts_order->save();
        }     
         catch(\Exception $e){
             return redirect()->route('user.activeTrade.transportationOrder.payment')->with('error', 'Klaida paslaugos pirkimo užklausoje. Bandykite dar kartą.');
        }

        $user = Auth::user();
        $notificationData = [
            'body' => 'Sveikiname užsakius pervežimo paslaugą' . ' ' . $trade->wanted_materials . ' ' . 'statybinems medžiagoms.',
            'notificationText' => 'Atidaryti užsakymų puslapį',
            'url' => route('user.activeOrders.index'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];
        $user -> notify(new TransportOrder($notificationData));
        
        return redirect()->route('user.activeTrade.index')->with('success', 'Mokėjimas sėkmingai atliktas! 
        Detalesnę informaciją gausite iš pervežimo įmonės.');
    }

}
