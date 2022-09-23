<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\MaterialCategory;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $user = User::all();
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        $search = $request->input('search');

        if($search) {
            $listings = Listing::with('products.category')->where('title', 'LIKE', '%' . $search . '%')
                        ->where('status', '=', 'galiojantis')
                        ->paginate(10);
        } 
        
        return view('user.index', compact('user', 'listings'))->withCategories($categories);
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
        
        return view('user.category.index', compact('listings', 'category_name'))->withCategories($category_children);
    }

    public function searchCategoryAdmin(Request $request, $category_name)
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
        
        return view('admin.category.index', compact('listings', 'category_name'))->withCategories($category_children);
    }
}
