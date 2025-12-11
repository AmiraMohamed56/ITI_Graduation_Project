<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\contact\ContactResource;
use App\Http\Requests\Api\Contact\StoreContactRequest;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return response()->json([
            'message' => 'Thank you! Your message has been sent successfully.',
            'data'    => new ContactResource($contact),
        ], 201);
    }
}
