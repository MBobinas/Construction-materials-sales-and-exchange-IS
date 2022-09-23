<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Listing;
use App\Models\MaterialCategory;
use App\Models\Product;
use App\Models\Comment;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user');
    }

    public function index()
    {       
        $id = Auth::id();
        $listings = Listing::where('user_id', $id)
        ->paginate(6);

        return view('user.userlistings.index', compact('listings'));
    }

    public function create()
    {
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();

        return view('user.userlistings.create')->withCategories($categories);
    }

    public function store(Request $request)
    {
        $user_id = ['user_id' => auth()->id()];
        
        request()->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'total_sum' => 'nullable|max:8',
            'ranking' => 'nullable',
            'location' => 'required',
            'listing_type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            /*
            'name' => 'required|max:255',
            'product_description' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'quantity' => 'required',
            'measurement' => 'required',
            'condition' => 'required',
            'price' => 'required',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',*/
        ]);

        $listing = [
            'title' => request('title'),
            'description' => request('description'),
            'total_sum' => request('total_sum'),
            'ranking' => request('ranking'),
            'location' => request('location'),
            'listing_type' => request('listing_type'),
            'user_id' => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            $destination_path = 'public/listing';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($destination_path, $image_name);
            $listing['image'] = $image_name;
        }

        Listing::create(array_merge($listing, $user_id));
        
        // Product creation part
        $images = array();
        if($files = $request->file('product_image')){
            $destination_path = 'public/product';
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $file->storeAs($destination_path, $name);
                $images[] = $name;
            }
        }

        for($i = 0; $i < count(request('name')); $i++) {
            $product[] = [
                'product_name' => request('name')[$i],
                'description' => request('product_description')[$i],
                'price' => request('price')[$i],
                'min_quantity' => request('min_quantity')[$i],
                'quantity' => request('quantity')[$i],
                'measurment_unit' => request('measurement')[$i],
                'condition' => request('condition')[$i],
                'listing_id' => Listing::latest()->first()->id,
                'category_id' => request('category')[$i],
                'image' => $images[$i],
            ];
        }
        Product::insert($product);

        $listing_id = Listing::latest()->first()->id;
        $products = Product::where('listing_id', $listing_id)->get();
        foreach($products as $product) {
            $listing = Listing::find($listing_id);
            $listing->products()->attach($product->id);
        }
        
        return redirect("/asmeniniai_skelbimai")->with('success', 'Skelbimas: '. $listing['title'].' išsaugotas');
    }

    public function edit(Listing $listing, Product $products)
    {
        $categories = MaterialCategory::with('children')->whereNull('parent_id')->get();
        $products = Product::where('listing_id', $listing->id)->get();

        return view('user.userlistings.edit', compact('listing', 'categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        $user_id = ['user_id' => auth()->id()];
        $data = request()->validate([       
                'title' => 'required|max:255',
                'description' => 'required|max:255',
                'total_sum' => 'nullable|max:8',
                'ranking' => 'nullable',
                'location' => 'required',
                'listing_type' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_name' => 'required|max:255|array',
                'product_name.*' => 'required|max:255',
                'category' => 'required|array',
                'category.*' => 'required',
                'min_quantity' => 'required|array',
                'min_quantity.*' => 'required',
                'quantity' => 'required|array',
                'quantity.*' => 'required',
                'measurment_unit'=> 'required|array',
                'measurment_unit.*' => 'required',
                'condition' => 'required|array',
                'condition.*' => 'required',
                'price' => 'required|array',
                'price.*' => 'required',
                'product_image' => 'nullable|array',
                'product_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_description' => 'required|array',
                'product_description.*' => 'required|max:255',
                ]);

        //Listing update part 
        $listing = Listing::find($id);
        $imageName = '';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $destination_path = 'public/listing';
                $path = $request->file('image')->storeAs($destination_path, $imageName);
                $listing['image'] = $imageName;
                if($listing->image) {
                    Storage::delete('public/listing' . $listing->image);
                    
                    $imageName = $image->getClientOriginalName();
                    $path = $request->file('image')->storeAs($destination_path, $imageName);
                    $listing['image'] = $imageName;
                }
                else{
                    $imageName = $listing->image;
                }            
            }
            $imageName = $listing->image;

        $listing->update([
            'title' => request('title'),
            'description' => request('description'),
            'total_sum' => request('total_sum'),
            'ranking' => request('ranking'),
            'location' => request('location'),
            'listing_type' => request('listing_type'),
            'image' => $imageName,
        ]);

    // Product update part
        $products = Product::where('listing_id', $listing->id)->get();
        if($request->hasFile('product_image')){
            $images = array();
            foreach($products as $product) {
                Storage::delete('public/product' . $product->image);
            }
            $destination_path = 'public/product';
            foreach($request->file('product_image') as $file){
                $name = $file->getClientOriginalName();
                $file->storeAs($destination_path, $name);
                $images[] = $name;
            }
        }
        else{
            $images = array();
            foreach($products as $product) {
                $images[] = $product->image;
            }
        }

        for($i = 0; $i < count(request('product_name')); $i++) {
            $products[$i]->update([
                'product_name' => request('product_name')[$i],
                'price' => request('price')[$i],
                'min_quantity' => request('min_quantity')[$i],
                'quantity' => request('quantity')[$i],
                'measurment_unit' => request('measurment_unit')[$i],
                'description' => request('product_description')[$i],
                'condition' => request('condition')[$i],
                'listing_id' => $listing->id,
                'category_id' => request('category')[$i],
                'image' => $images[$i],
            ]);
        }
        
    return redirect("/asmeniniai_skelbimai")->with('success', 'Skelbimo: '. $listing['title'].' informacija pakeista');
}

    public function destroy(Listing $listing)
    {
        $comments = Comment::where('listing_id', $listing->id)->get();
        $comments->each->delete();
        $listing->products()->detach();
        $listing->delete();

        return redirect('/asmeniniai_skelbimai')->with('success', 'Skelbimas: '. $listing['title'].' pašalintas');
    }
}
