<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:40', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'name.required' => 'Prašome įvesti paskyros vardą',
            'name.string' => 'Prašome įvesti teisingą paskyros vardą be spacialiųjų simbolių',
            'name.max' => 'Prašome įvesti paskyros vardą, kurio ilgis neviršytu 20 simbolių',
            'email.required' => 'Prašome įvesti elektroninio pašto adresą',
            'email.string' => 'Prašome įvesti teisingą elektroninio pašto adresą',
            'email.email' => 'Prašome įvesti teisingą elektroninio pašto adresą',
            'email.max' => 'Prašome įvesti elektroninio pašto adresą, kurio ilgis neviršytu 40 simbolių',
            'email.unique' => 'Toks elektroninio pašto adresas jau yra užregistruotas',
            'password.required' => 'Prašome įvesti slaptažodį',
            'password.string' => 'Prašome įvesti teisingą slaptažodį',
            'password.min' => 'Prašome įvesti slaptažodį, kurio ilgis butų sudarytas iš mažiausiai 8 simbolių',
            'password.confirmed' => 'Prašome įvesti tokį patį slaptažodį',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->attachRole('user');
        return $user;
    }
}
