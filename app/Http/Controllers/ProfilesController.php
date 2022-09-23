<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Intervention\Image\Facades\Image;
use App\Models\User;
use App\Models\Role;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user|administrator', ['except' => ['index', 'show']]);
    }

    public function index(User $user)
    {
        if($user->hasRole('user') == true) {
            return view('user.profile.index', compact('user'));
        }
        elseif($user->hasRole('administrator') == true) {
            return view('admin.profile.index', compact('user'));
        }

        return redirect()->back();
    }

    public function details(User $user)
    {
       return view('user.profile.details', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        if($user->hasRole('user') == true) {
            return view('user.profile.edit', compact('user'));
        }
        elseif($user->hasRole('administrator') == true) {
            return view('admin.profile.edit', compact('user'));
        }

        return redirect()->back();
    }

    public function update(User $user, ProfileRequest $request)
    {
        $this->authorize('update', $user->profile);

        $data = $request->validated();
        
        $userData = request()->validate([
            'name' => 'required',
            'email'=> 'required|email',
        ], [
            'name.required' => 'Prašome įvesti vardą.',
            'name.max' => 'Vardas negali būti ilgesnis nei 50 simbolių.',
            'email.required' => 'Prašome įvesti el. pašto adresą.',
            'email.email' => 'Neteisingai įvestas el. pašto adresas.',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            $image->save();


            auth()->user()->profile->update(array_merge(
                $data,
                ['image' => $imagePath]
            ));
        }
        else
        {
            auth()->user()->profile->update($data);
            auth()->user()->update($userData);
        }
        
        if($user->hasRole('user') == true) {
            return redirect()->route('user.profile.index', compact('user'));
        }
        elseif($user->hasRole('administrator') == true) {
            return redirect()->route('admin.profile.index', compact('user'));
        }

    }
}
