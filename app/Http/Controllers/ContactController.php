<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            Mail::to(config('mail.contact_recipient'))->send(new ContactFormMail(
                $validated['name'],
                $validated['email'],
                $validated['message'],
            ));

            return back()->with('success', 'Your message has been sent.');
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            report($e);

            return back()->with('error', 'Unable to send your message right now. Please try again later.');
        }
    }
}