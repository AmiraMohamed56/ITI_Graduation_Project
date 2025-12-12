<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Notifications\Notification;

class TestNotificationController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'error' => 'User not authenticated',
            ], 401);
        }
        
        // Create a test notification
        $user->notify(new \App\Notifications\TestNotification());
        
        return response()->json([
            'status' => true,
            'message' => 'Test notification created successfully',
        ]);
    }
}
