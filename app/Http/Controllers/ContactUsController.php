<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactUsRequest;
use App\Models\Contact;

class ContactUsController extends Controller
{
    public function index()
    {
        return view('main.landingPage.contact', ['metaTitle' => 'Kontaktai']);
    }

    public function store(ContactUsRequest $request) {
        
        Contact::create($request->validated());
        
        \Mail::send('mail', array(
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'messages' => $request->get('message'),
        ), function($message) use ($request){
            $message->from($request->email);
            $message->to('matas.bobinas@gmail.com', 'Admin')->subject($request->get('subject'));
        });

        return back()->with('success', 'Mes gavome, jūsų, žinutę! Netrukus susisieksime.');
    }


}
