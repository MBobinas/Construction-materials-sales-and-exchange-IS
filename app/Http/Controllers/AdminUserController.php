<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Listing;
use App\Models\Trade;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrator');
    }
    
    public function index()
    {
        $users = User::all();
        return view('admin.accountControl.index', compact('users'));
    }

    public function block($id)
    {
        $user = User::find($id);
        $user->blocked_at = now();
        $user->save();

        // $listings = Listing::where('user_id', $id)->get();
        // foreach ($listings as $listing) {
        //     $listing->status = 'deaktyvuotas';
        //     $listing->save();
        // }
        // $orders = Order::where('user_id', $id)->get();
        // foreach ($orders as $order) {
        //     $order->status = 'šalinti';
        //     $order->save();
        // }
        // $trades = Trade::where('user_id', $id)->get();
        // foreach ($trades as $trade) {
        //     $trade->status = 'šalinti';
        //     $trade->save();
        // }

        return redirect()->route('admin.accountControl.index')->with('success', 'Naudotojas'. ' '. $user->name . ' ' . 'buvo užblokuotas!');
    }

    public function unblock($id)
    {
        $user = User::find($id);
        $user->blocked_at = null;
        $user->save();

        return redirect()->route('admin.accountControl.index')->with('success', 'Naudotojas'. ' '. $user->name . ' ' . 'buvo atblokuotas!');
    }

}
