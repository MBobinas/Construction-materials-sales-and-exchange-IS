<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\MaterialCategory;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user|administrator');
    }

    public function index(Request $request)
    {
        $user = User::all();
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
    
        return view('user.index', compact('user', 'listings'))->withCategories($categories);
    }

    public function buyFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti')
                    ->paginate(5);
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('user.filter.buy', compact('listings'))->withCategories($categories);
    }

    public function tradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('user.filter.trade', compact('listings'))->withCategories($categories);
    }

    public function buyOrTradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti arba mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        
        return view('user.filter.buyOrTrade', compact('listings'))->withCategories($categories);
    }

}
