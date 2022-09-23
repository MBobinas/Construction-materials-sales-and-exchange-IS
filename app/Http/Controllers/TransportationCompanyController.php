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
use Dompdf\Dompdf;
use Dompdf\Options;

class TransportationCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:transportation_company');
    }

    public function index()
    {
        $orders = Order::where('transportation_needed', 1)
                        ->get();
        $trades = Trade::where('transportation_needed', 1)->get();

        $todays_orders = Order::where('created_at', '>=', now()->startOfDay())->get();

        return view('user.transportationCompany.index', compact('orders', 'trades', 'todays_orders'));
    }

    public function active()
    {
        $ts_orders = TransportationOrder::where('status', '!=', 'užbaigta')
                                        ->where('trade_id', "=" ,null)
                                        ->where('status', '!=', 'atmesta')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(5);

        return view('user.transportationCompany.active', compact('ts_orders'));
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

        $options = new Options();
        $options->set('defaultFont', 'Courier');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('transportationPDF', compact('ts_order', 'transportation_company')));
        $dompdf->render();
        $dompdf->stream('Užsakymas.pdf');
    }

}
