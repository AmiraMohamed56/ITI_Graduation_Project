{{-- resources/views/Doctors_Dashboard/notifications/list.blade.php --}}
@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Notifications')

@section('content')
  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
    <div class="flex items-center text-sm text-gray-500 mt-2">
      <span>Home</span>
      <span class="mx-2">»</span>
      <span>Notifications</span>
    </div>
  </div>

  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  <!-- Notifications Card -->
  <div class="bg-white rounded-lg shadow">

    <!-- Card Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
          @if($unreadCount > 0)
            <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">
              {{ str_pad($unreadCount, 2, '0', STR_PAD_LEFT) }}
            </span>
          @endif
        </div>

        <div class="flex items-center gap-3">
          <!-- Filter -->
          <form method="GET" action="{{ route('doctor.notifications.index') }}" class="flex gap-2">
            <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
              <option value="">All Notifications</option>
              <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
              <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
            </select>
          </form>

          @if($unreadCount > 0)
            <form action="{{ route('doctor.notifications.mark-all-as-read') }}" method="POST">
              @csrf
              <button type="submit" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                Mark all as read
              </button>
            </form>
          @endif

          @if($notifications->total() > 0)
            <form action="{{ route('doctor.notifications.delete-all') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete all notifications?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="flex items-center gap-2 text-sm text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition-colors">
                Delete All
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>

    <!-- Notifications List -->
    <div class="divide-y divide-gray-200">
      @forelse($notifications as $notification)
        <div class="p-6 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition-colors">
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-4 flex-1">
              <!-- Icon -->
              {{-- <div class="w-12 h-12 rounded-full flex items-center justify-center
                {{ $this->getNotificationColor($notification->type) }}">
                {!! $this->getNotificationIcon($notification->type) !!}
              </div> --}}

              <div class="flex-1">
                <p class="text-gray-900">
                  <span class="font-semibold">{{ $notification->data['title'] ?? 'Notification' }}</span>
                </p>
                <p class="text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>

                <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                  <span>⏱</span>
                  <span>{{ $notification->created_at->diffForHumans() }}</span>
                  @if(is_null($notification->read_at))
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                  @endif
                </div>
              </div>
            </div>

            <div class="flex items-center gap-2">
              @if(is_null($notification->read_at))
                <form action="{{ route('doctor.notifications.mark-as-read', $notification->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="text-xs text-blue-600 bg-blue-100 px-3 py-1.5 rounded hover:bg-blue-200">
                    Mark as read
                  </button>
                </form>
              @endif

              <form action="{{ route('doctor.notifications.destroy', $notification->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this notification?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200">
                  Delete
                </button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="p-6 text-center text-gray-500">
          No notifications found
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
      <div class="p-6 border-t">
        {{ $notifications->links() }}
      </div>
    @endif
  </div>
@endsection

@php
  function getNotificationColor($type) {
    if (strpos($type, 'Appointment') !== false) return 'bg-blue-100 text-blue-600';
    if (strpos($type, 'Reminder') !== false) return 'bg-yellow-100 text-yellow-600';
    if (strpos($type, 'Payment') !== false) return 'bg-green-100 text-green-600';
    return 'bg-gray-100 text-gray-600';
  }

  function getNotificationIcon($type) {
    if (strpos($type, 'Appointment') !== false) return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
    if (strpos($type, 'Reminder') !== false) return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>';
    return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
  }
@endphp
