<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\User;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;

class ArchivesController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;

        $listings = Listing::where('user_id', $user_id)
                            ->where(function ($query) {
                                $query->where('status', 'negaliojantis')
                                    ->orWhere('status', 'deaktyvuotas');
                            })                         
                            ->paginate(5);

        return view('user.archives.index', compact('listings'));
    }

    public function indexOrders()
    {
        $user_id = Auth::user()->id;

        $archives = Order::where('user_id', $user_id)
                            ->where('order_status', 'šalinti')
                            ->paginate(5);

        return view('user.archives.orders', compact('archives'));
    }

    public function delete($id)
    {
        $comments = Comment::where('listing_id', $listing->id)->get();
        $comments->each->delete();
        $listing->products()->detach();
        $listing->delete();

        return redirect()->route('user.archives.index');
    }
}
