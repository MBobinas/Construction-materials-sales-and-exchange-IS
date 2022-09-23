<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\Order;
use App\Models\Trade;
use App\Models\TransportationCompany;
use App\Models\TransportationOrder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facedes\Pdf;

class TransportationCompanyTradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:transportation_company');
    }

    public function index()
    {
        $ts_orders = TransportationOrder::where('status', '!=', 'užbaigta')
                                        ->where('order_id', '=', null)
                                        ->where('status', '!=', 'atmesta')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(5);

        return view('user.transportationCompany.activeTradeOrders', compact('ts_orders'));
    }

    public function confirm($id)
    {
        $ts_order = TransportationOrder::findOrFail($id);
        $ts_order->status = 'priimta';
        $ts_order->save();

        return redirect()->back()->with('success', 'Užsakymas patvirtintas');
    }

    public function cancel($id)
    {
        $ts_order = TransportationOrder::findOrFail($id);
        $ts_order->status = 'atmesta';
        $ts_order->save();
        
        return redirect()->back()->with('success', 'Užsakymas atmestas');
    }

    public function complete($id)
    {
        $ts_order = TransportationOrder::findOrFail($id);
        $ts_order->status = 'užbaigta';
        $ts_order->save();

        return redirect()->back()->with('success', 'Užsakymas užbaigtas');
    }
    
    public function invoice($id)
    {
        $ts_order = TransportationOrder::findOrFail($id);
        
        $user = User::find($ts_order->user_id);
        $order = Order::find($ts_order->order_id);
        $trade = Trade::find($ts_order->trade_id);
        $transportation_company = TransportationCompany::find($ts_order->transportation_company_id);

        $pdf = \PDF::loadView('user.transportationCompany.invoice', compact('user', 'order', 'trade', 'transportation_company'));
        return $pdf->download('invoice.pdf');
        
    }
}
