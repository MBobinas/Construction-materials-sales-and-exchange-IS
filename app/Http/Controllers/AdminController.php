<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use App\Models\Order;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrator');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function listings()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '!=', 'deaktyvuotas')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        
        return view('admin.listings', compact('listings'))->withCategories($categories);
    }

    public function listingDestroy($id)
    {  
        $listing = Listing::findOrFail($id);
        $listing->status = 'deaktyvuotas';
        $listing->save();

        return redirect()->back()->with('success', 'Skelbimas buvo deaktyvuotas');
    }

    public function buyFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti')
                    ->paginate(5);
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('admin.filter.buy', compact('listings'))->withCategories($categories);
    }

    public function tradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('admin.filter.trade', compact('listings'))->withCategories($categories);
    }

    public function buyOrTradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti arba mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        
        return view('admin.filter.buyOrTrade', compact('listings'))->withCategories($categories);
    }
}
