<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status == 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->latest()->paginate(15);
        $unreadCount = $user->unreadNotifications()->count();

        return view('Doctors_Dashboard.notifications.list', compact('notifications', 'unreadCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Mark as read when viewed
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return view('Doctors_Dashboard.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }


    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read');
    }


    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return redirect()->back()->with('success', 'All notifications deleted successfully');
    }
}
