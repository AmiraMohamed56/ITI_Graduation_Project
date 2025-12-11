<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Mail;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10); // Paginate for better UX
        return view('admin.contacts.index', compact('contacts'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);

        // Send email
        Mail::to($contact->email)->send(new ContactReply($contact, $request->reply_message));

        // Optional: Mark as replied
        $contact->update(['replied' => true]);

        return redirect()->route('admin.contacts.index')->with('success', 'Reply sent successfully');
    }
}
