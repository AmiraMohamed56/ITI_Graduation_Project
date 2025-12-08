<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notificationsController extends Controller
{
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

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }


     public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }


     public function update($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read');
    }


    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }


    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        return redirect()->back()->with('success', 'All notifications deleted successfully');
    }

}
