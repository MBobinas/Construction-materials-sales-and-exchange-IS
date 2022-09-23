<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StatusChange;
use App\Models\Order;
use App\Models\User;
use App\Models\Listing;


class ActiveOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)
                ->where('status', 'apmokėtas')
                ->where('order_status', '!=', 'užsakymas atšauktas')
                ->where('order_status', '!=', 'šalinti')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('user.activeOrders.index', compact('orders'));
    }

    public function orders()
    {
        $orders = Order::where('listing_author_id', Auth::user()->id)
                ->where('status', 'apmokėtas')
                ->where('order_status', '!=', 'užsakymas atšauktas')
                ->where('order_status', '!=', 'šalinti')
                ->where('user_id', '!=', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);


        return view('user.activeOrders.orders', compact('orders'));
    }

    public function start($id)
    {
        $order_notf = Order::find($id);
        $user_id = $order_notf->user_id;
        $user = User::findOrFail($user_id);

        $notificationData = [
            'body' => 'Jūsų užsakymo būsena pakito!',
            'notificationText' => 'Atidaryti svetainę',
            'url' => url('/'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        if(isset($_POST['startOrder']))
        {
            $order = Order::findOrFail($id);
            $order->order_status = 'užsakymo vykdymas pradėtas';
            $order->save();
            $user->notify(new StatusChange($notificationData));

            return redirect()->back()->with('success', 'Užsakymas pradėtas');
        }
        else if (isset($_POST['cancelOrder']))
        {
            $order = Order::findOrFail($id);
            $order->order_status = 'užsakymas atšauktas';
            $order->save();
            $user->notify(new StatusChange($notificationData));

            return redirect()->back()->with('success', 'Užsakymas atšauktas');
        }
    }

    public function sent($id)
    {
        $order = Order::findOrFail($id);

        $user_id = $order->user_id;
        $user = User::findOrFail($user_id);
        $notificationData = [
            'body' => 'Statybinės medžiagos buvo išsiųstos!',
            'notificationText' => 'Atidaryti svetainę',
            'url' => url('/'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];
        $order->order_status = 'medžiagos išsiųstos';
        $order->save();

        $user->notify(new StatusChange($notificationData));

        return redirect()->route('user.activeOrders.orders')->with('success', 'Skelbimo būsena pakeista: medžiagos išsiųstos');
    }

    public function complete($id)
    {
        $order = Order::findOrFail($id);

        $user_id = $order->user_id;
        $user = User::findOrFail($user_id);
        $notificationData = [
            'body' => 'Užsakymas buvo baigtas! Jeigu kilo nesklandumu prašome susisiekti su mumis.',
            'notificationText' => 'Atidaryti pagalbos puslapį',
            'url' => route('main.landingPage.contact'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        $order->order_status = 'užsakymas atliktas';
        $order->save();

        $user->notify(new StatusChange($notificationData));

        return redirect()->back()->with('success', 'Skelbimo būsena pakeista: užsakymas atliktas');
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        $user_id = $order->user_id;
        $user = User::findOrFail($user_id);
        $notificationData = [
            'body' => 'Užsakymas buvo atšauktas! Jeigu kilo nesklandumu prašome susisiekti su mumis.',
            'notificationText' => 'Atidaryti pagalbos puslapį',
            'url' => route('main.landingPage.contact'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        $order->order_status = "užsakymas atšauktas";
        $order->save();

        $user->notify(new StatusChange($notificationData));

        return redirect()->back()->with('success', 'Užsakymas atšauktas');
    }

    public function confirm(Request $request, $id)
    {
        if(request('radioButton') == 'yes')
        {
            $order = Order::findOrFail($id);

            $user_id = $order->listing_author_id;
            $user = User::findOrFail($user_id);
            $notificationData = [
                'body' => 'Gavėjas patvirtino gavęs medžiagas. Jeigu kilo nesklandumu prašome susisiekti su mumis.',
                'notificationText' => 'Atidaryti pradžios puslapį',
                'url' => url('/'),
                'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
            ];
    
            $order->order_status = "medžiaga gauta";
            $order->save();
            $user->notify(new StatusChange($notificationData));


            return redirect()->back()->with('success', 'Patvirtinote, kad gavote medžiagą');
        }
        else if(request('radioButton') == 'no')
        {
            return redirect()->back()->with('success', 'Medžiagos vis dar negavote, jeigu kilo nesklandumu susisiekite per kontaktų formą');
        }
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->order_status = "šalinti";
        $order->save();

        return redirect()->back()->with('success', 'Užsakymas pašalintas');
    }

}
