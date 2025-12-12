<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\contact\ContactResource;
use App\Http\Requests\Api\Contact\StoreContactRequest;
use App\Notifications\NewContactMessage;
use Illuminate\Support\Facades\Log;



class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());
        // Notify all admins about the new contact message
        $this->notifyAdmins($contact);

        return response()->json([
            'message' => 'Thank you! Your message has been sent successfully.',
            'data'    => new ContactResource($contact),
        ], 201);
    }

    /**
     * Notify all admins about new contact message
     */
    private function notifyAdmins($contact)
    {
        try {
            // Get all admin users
            $admins = User::where('role', 'admin')->get();

            // Send notification to each admin
            foreach ($admins as $admin) {
                $admin->notify(new NewContactMessage($contact));
            }

            Log::info("Contact message #{$contact->id} received - Notifications sent to admins");
        } catch (\Exception $e) {
            Log::error("Failed to send admin notifications for contact #{$contact->id}: " . $e->getMessage());
        }
    }
}
