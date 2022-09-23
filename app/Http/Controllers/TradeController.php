<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\MaterialCategory;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Notifications\TradeStatusChange;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $quantity = request('quantity');
        $selectedProducts = request('selected_product');
        $user = Auth::user();
        $listing = Listing::find($id);
        $user = User::find($listing->user_id);
        $material_categories = MaterialCategory::all();

        if ($request->has('selected_product') && is_array(request('selected_product')) && count(request('selected_product')) && $quantity > 0) {
        $products = Product::whereIn('id', $selectedProducts)->get();

            for($i = 0; $i < sizeof($selectedProducts); $i++) {
                if ($quantity[$i] == 0) {
                    return redirect()->back()->with('error', 'Kiekis negali būti 0');
                }

                $product = Product::findOrFail($selectedProducts[$i]);
                if ($product->quantity < $quantity[$i]) {
                    return redirect()->back()->with('error', 'Apgailestaujame, naudotojas gali pasiūlyti tik: ' . $product->quantity . ' ' . $product->product_name . ' likusių produktų.');
                }
            }
        }
        else{
            return redirect()->back()->with('error', 'Pasirinkite prekę');
        }

        return view('user.trade.index', compact('listing', 'user', 'material_categories', 'quantity', 'products'));
    }

    public function offer(Request $request, $id)
    {
        $quantity = request('desired_quantity');
        $listing = Listing::find($id);
        $wantedMaterials = request('wanted_material');
        $offeredMaterials = request('offered_material');
        $receiverId = request('receiver');
        $sender = Auth::user()->id;

        $wantedProducts = Product::whereIn('id', $wantedMaterials)->get();
        $wantedProductNames = [];
        foreach ($wantedProducts as $wantedProduct) {
            $wantedProductNames[] = $wantedProduct->product_name;
        }

        $trade = new Trade;
        $trade->wanted_materials = implode(', ', $wantedProductNames);
        $trade->offered_materials = $offeredMaterials;
        $trade->offer_sender = $sender;
        $trade->offer_receiver = $receiverId;
        $trade->email = request('email');
        $trade->phone = request('phone');
        $trade->address = request('address');
        $trade->status = 'laukiama';
        $trade->trade_for_listing = $listing->id;
        $trade->save();

        for($i = 0; $i < sizeof($wantedMaterials); $i++) {
            $offer = new Offer;
            $offer->wanted_materials = $wantedProductNames[$i];
            $offer->offered_materials = $offeredMaterials;
            $offer->quantity_wanted = $quantity[$i];
            $offer->quantity_offered = null;
            $offer->measurment_unit = $wantedProducts[$i]->measurment_unit;
            $offer->trade_id = $trade->id;
            $offer->sender = $sender;
            $offer->receiver = $receiverId;
            $offer->desired_product_id = $wantedMaterials[$i];
            $offer->offered_product_id = null;
            $offer->save();
        }

        return redirect()->route('user.index')->with('success', 'Pasiūlymas nusiųstas, laukite atsako!');
    }

    public function show()
    {
        $user = Auth::user();
        $trades = Trade::where('offer_receiver', $user->id)
                        ->where('status', '!=', 'pašalinti')
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);

        $offers = Offer::where('receiver', $user->id)->get();

        $senderTrades = Trade::where('offer_sender', $user->id)->get();
        
        return view('user.activeTrade.index', compact('trades', 'senderTrades'));
    }

    public function sentTrades()
    {
        $user = Auth::user();
        $trades = Trade::where('offer_sender', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->paginate(5);

        $offers = Offer::where('sender', $user->id)->get();

        $receiverTrades = Trade::where('offer_receiver', $user->id)->get();
        
        return view('user.activeTrade.sentTrades', compact('trades', 'receiverTrades'));
    }

    public function cancel($id)
    {
        $trade = Trade::find($id);
        $user_id = $trade->offer_sender;
        $user = User::findOrFail($user_id);

        $notificationData = [
            'body' => 'Jūsų mainų pasiūlymas buvo atmestas!',
            'notificationText' => 'Atidaryti mainų puslapį',
            'url' => route('user.activeTrade.index'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        $trade->status = 'atšaukta';
        $trade->save();

        $user->notify(new TradeStatusChange($notificationData));

        return redirect()->route('user.activeTrade.index')->with('danger', 'Pasiūlymas' . ' ' .  $trade->listing->title . ' ' . 'atšauktas!');
    }

    public function accept($id)
    {
        $trade = Trade::find($id);
        $user_id = $trade->offer_sender;
        $user = User::findOrFail($user_id);

        $notificationData = [
            'body' => 'Jūsų mainų pasiūlymas buvo priimtas!',
            'notificationText' => 'Atidaryti mainų puslapį',
            'url' => route('user.activeTrade.index'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        $trade->status = 'priimta';
        $trade->save();
        $user->notify(new TradeStatusChange($notificationData));

        $offers = Offer::where('trade_id', $trade->id)->get();
        foreach ($offers as $offer) {
            $product = Product::find($offer->desired_product_id);
            if ($product->quantity < $offer->quantity_wanted) {
                return redirect()->route('user.activeTrade.index')->with('danger', 'Apgailestaujame, naudotojas gali pasiūlyti tik: ' . $product->quantity . ' ' . $product->product_name . ' likusių produktų.');
            }
            $product->quantity = $product->quantity - $offer->quantity_wanted;
            $product->save();
        }

        return redirect()->route('user.activeTrade.index')->with('success', 'Pasiūlymas' . ' '. $trade->listing->title . ' ' . 'priimtas! Įvykdžius mainus, prašome pašalinti 
                                 užklausą.');
    }

    public function complete($id)
    {
        $trade = Trade::find($id);

        $user_id = $trade->offer_sender;
        $user = User::findOrFail($user_id);
        $notificationData = [
            'body' => 'Sveikiname, mainai pabaigti!',
            'notificationText' => 'Atidaryti mainų puslapį',
            'url' => route('user.activeTrade.index'),
            'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];

        $trade->status = 'užbaigta';
        $trade->save();

        $user->notify(new TradeStatusChange($notificationData));

        return redirect()->route('user.activeTrade.index')->with('success', 'Mainai su naudotoju'
                                 . ' ' .  $trade->listing->user->name . ' ' . 'užbaigti!');
    }

    public function delete($id)
    {
        $trade = Trade::find($id);
        $trade->status = 'pašalinti';
        $trade->save();

        return redirect()->route('user.activeTrade.index')->with('success', 'Pasiūlymas' . ' ' .  $trade->listing->title . ' ' . 'pašalintas!');
    }

}
