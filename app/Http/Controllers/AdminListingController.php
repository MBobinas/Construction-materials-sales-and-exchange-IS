<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;


class AdminListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrator');
    }

    public function index()
    {
        $listings = Listing::where('status', '=', 'suformuotas')
                    ->where('status', '!=', 'deaktyvuotas')
                    ->paginate(5);

        return view('admin.temporaryListings.index', compact('listings'));
    }

    public function update(Listing $listing)
    {
        $listing->status = 'galiojantis';
        $listing->save();

        return redirect()->back()->with('success', 'Skelbimas buvo patvirtintas');
    }
}
