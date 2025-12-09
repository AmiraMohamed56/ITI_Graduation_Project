<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications();

        // Filter by read/unread
        if ($request->has('unread') && $request->unread == 1) {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(15);

        return response()->json([
            'status' => true,
            'data' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $this->getNotificationType($notification->type),
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'appointment_id' => $notification->data['appointment_id'] ?? null,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_full' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'unread_count' => $user->unreadNotifications()->count(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notification deleted',
        ]);
    }

    public function destroyAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json([
            'status' => true,
            'message' => 'All notifications deleted',
        ]);
    }

    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    private function getNotificationType($type)
    {
        if (strpos($type, 'Appointment') !== false) return 'appointment';
        if (strpos($type, 'Reminder') !== false) return 'reminder';
        if (strpos($type, 'Payment') !== false) return 'payment';
        return 'general';
    }



}
