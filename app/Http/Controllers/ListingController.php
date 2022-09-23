<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ListingRequest;
use App\Models\User;
use App\Models\Listing;
use App\Models\MaterialCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\Comment;
use App\Notifications\SendOrderNotification;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(Auth::user()->hasRole('user') == true) {
            $listing = Listing::findOrFail($id);
            $products = Product::where('listing_id', $id)->get();
            $comments = Comment::where('listing_id', $id)->get();

            return view('user.listing.index', compact('listing', 'products', 'comments'));
        }
        elseif(Auth::user()->hasRole('administrator') == true) {
            $listing = Listing::findOrFail($id);
            $products = Product::where('listing_id', $id)->get();
            $comments = Comment::where('listing_id', $id)->get();

            return view('admin.specificListing.index', compact('listing', 'products', 'comments'));
        }
    }

    public function comment(ListingRequest $request, $id)
    {   
        $comment = new Comment;
        $comment->text = $request->comment;
        $comment->author = $request->author;
        $comment->user_id = Auth::user()->id;
        $comment->listing_id = $id;
        $comment->save();

        return redirect()->back();
    }

    public function destroyComment($listing_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $comment->delete();

        return redirect()->back();
    }

    public function checkout(Request $request, $id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $quantity = request('quantity');
        $selectedProducts = request('selected_product');
        $user = Auth::user();
        $listing_author_id = Listing::findOrFail($id)->user_id;
        
        $selectedProducts = array_filter($selectedProducts, function($value) {
            return $value !== null;
        });
        $quantity = array_values(array_filter($quantity, function($value) {
            return $value !== '0' && $value !== null;
        }));
        $quantity = array_values($quantity);
        $selectedProducts = array_values($selectedProducts);

        $amount = 0;
        $amount *= 100;
        $ordersIds = [];
        
        if ($request->has('selected_product') && $request->has('quantity')) {
            $products = Product::whereIn('id', $selectedProducts)->get();

            for($i = 0; $i < sizeof($selectedProducts); $i++) {
                    $product = Product::findOrFail($selectedProducts[$i]);

                    if ($product->quantity < $quantity[$i]) {
                        return redirect()->back()->with('error', 
                                                        'Apgailestaujame, pardavejas gali pasiūlyti tik: ' 
                                                        . $product->quantity . ' ' 
                                                        . $product->product_name . ' likusių produktų.');
                    }

                    $amount += $product->price * $quantity[$i] * 100;

                    $order = new Order();
                    $order->user_id = Auth::user()->id;
                    $order->listing_id = $product->listing_id;
                    $order->product_id = $product->id;
                    $order->listing_author_id = $listing_author_id;
                    $order->quantity = $quantity[$i];
                    $order->price = $product->price * $quantity[$i];
                    $order->order_status = 'užklausa gauta';
                    $order->save();
                    
                    $ordersIds[] = $order->id;
                    
                    $amount = (int)$amount;
                }
                
                try{
                    $description = 'Pirkimo užklausa iš ' . Auth::user()->name;
                    $payment_intent = \Stripe\PaymentIntent::create([
                        'description' => 'Stripe Test Payment',
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
        }
        else{
            return redirect()->back()->with('error', 'Pasirinkite prekę');
        }

		return view('user.checkout.credit-card',compact('intent', 'products', 'amount', 'quantity', 'ordersIds'));
    }
    

    public function afterPayment(Request $request)
    {
        $ordersIds = $request->input('order_id');
        $user = Auth::user();

        try{
            $paymentMethod = $request->input('payment_method');
            $stripeCustomer = $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
        
            foreach($ordersIds as $orderId) {
                $order = Order::findOrFail($orderId);
                $order->paid_at = now();
                $order->status = 'apmokėtas';
                $order->order_status = 'užsakymas perduotas vykdymui';
                $order->city = $request->input('city');
                $order->address = $request->input('address');
                $order->phone = $request->input('phone');
                $order->more_info = $request->input('moreInfo');
                $order->save();
            
                $product = Product::findOrFail($order->product_id);
                if($product->quantity - $order->quantity > 0){
                    $product->quantity -= $order->quantity;
                    $product->save();
                }

                //send email using notification 
                //$user->notify(new \App\Notifications\SendOrderNotification());
                $order->update(['delivered_at' => now()]);
            }           
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Klaida mokėjimo procese');
        }

        $notificationData = [
                'body' => 'Sveikiname nusipirkus statybinę medžiagą!',
                'notificationText' => 'Atidaryti aktyvių užsakymų puslapį',
                'url' => route('user.activeOrders.index'),
                'thankyou' => 'Ačiū, kad naudojates mūsų svetaine.'
        ];
        $user->notify(new SendOrderNotification($notificationData));
        

        return redirect()->route('user.index')->with('success', 'Mokėjimas sėkmingai atliktas! 
                                                                 Užsakymo būseną galite stebėti aktyvių užsakymo skiltyje.');
    }
}
