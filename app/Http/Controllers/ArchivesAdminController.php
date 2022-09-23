<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\User;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;

class ArchivesAdminController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;

        $listings = Listing::where('status', 'negaliojantis')
                            ->orWhere('status', 'deaktyvuotas')                      
                            ->paginate(5);

        return view('admin.archives.index', compact('listings'));
    }

    public function indexOrders()
    {
        $user_id = Auth::user()->id;

        $archives = Listing::where('status', 'negaliojantis')
                            ->orWhere('status', 'deaktyvuotas')                      
                            ->paginate(5);

        return view('admin.archives.orders', compact('archives'));
    }

    public function delete($id)
    {
        $comments = Comment::where('listing_id', $listing->id)->get();
        $comments->each->delete();
        $listing->products()->detach();
        $listing->delete();

        return redirect()->route('admin.archives.index');
    }
}
