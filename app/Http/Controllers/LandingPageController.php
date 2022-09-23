<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use App\Models\MaterialCategory;


class LandingPageController extends Controller
{
    public function index()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->paginate(5);
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        
        return view('main.guest.listings', compact('listings'))->withCategories($categories);
    }

    public function show($id)
    {
        $listing = Listing::findOrFail($id);
        $products = Product::where('listing_id', $id)->get();
        $comments = Comment::where('listing_id', $id)->get();

        return view('main.guest.listing', compact('listing', 'products', 'comments'));
    }

    public function search(Request $request)
    {
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        $search = $request->input('search');

        if($search) {
            $listings = Listing::with('products.category')->where('title', 'LIKE', '%' . $search . '%')
                        ->where('status', '=', 'galiojantis')
                        ->paginate(10);
        } 
        
        return view('main.guest.search', compact('listings'))->withCategories($categories);
    }

    public function buyFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti')
                    ->paginate(5);
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('main.guest.filter.buy', compact('listings'))->withCategories($categories);
    }

    public function tradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('main.guest.filter.trade', compact('listings'))->withCategories($categories);
    }

    public function buyOrTradeFilter()
    {
        $listings = Listing::with('products.category')
                    ->where('status', '=', 'galiojantis')
                    ->where('listing_type', 'parduoti arba mainyti')
                    ->paginate(5);

        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        
        return view('main.guest.filter.buyOrTrade', compact('listings'))->withCategories($categories);
    }

    public function searchCategory(Request $request, $category_name)
    {
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        $search = $request->input('search');
        $category_id = MaterialCategory::where('category_name', $category_name)->first()->id;

        $category_children = MaterialCategory::where('parent_id', $category_id)->get();

        $listings = Listing::with('products.category')->whereHas('products', function($query) use ($category_id, $category_children) {
            $query->where('category_id', $category_id);
            foreach($category_children as $category_child) {
                $query->orWhere('category_id', $category_child->id);
            }
        })
        ->where('status', '=', 'galiojantis')
        ->paginate(10);
        
        return view('main.guest.filter.category', compact('listings', 'category_name'))->withCategories($category_children);
    }

}
