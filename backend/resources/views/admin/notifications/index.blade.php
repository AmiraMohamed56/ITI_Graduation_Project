{{-- File: resources/views/admin/notifications/index.blade.php --}}
@extends('layouts.admin')

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

  <!-- Success Message -->
  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
      <span class="block sm:inline">{{ session('success') }}</span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Close</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
        </svg>
      </span>
    </div>
  @endif

  <!-- Notifications Card -->
  <div class="bg-white rounded-lg shadow">

    <!-- Card Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
          @if($unreadCount > 0)
            <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">
              {{ str_pad($unreadCount, 2, '0', STR_PAD_LEFT) }}
            </span>
          @endif
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- Filter Dropdown -->
          <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex gap-2">
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Notifications</option>
              <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
              <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
            </select>
          </form>

          <!-- Mark All as Read -->
          @if($unreadCount > 0)
            <form action="{{ route('admin.notifications.mark-all-as-read') }}" method="POST" class="inline">
              @csrf
              <button type="submit"
                      class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mark all as read
              </button>
            </form>
          @endif

          <!-- Delete All -->
          @if($notifications->total() > 0)
            <form action="{{ route('admin.notifications.delete-all') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete all notifications? This action cannot be undone.')">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="flex items-center gap-2 text-sm text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
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
            <!-- Left Side: Icon + Content -->
            <div class="flex items-start gap-4 flex-1">
              <!-- Icon -->
              <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 {{ getNotificationColor($notification->type) }}">
                {!! getNotificationIcon($notification->type) !!}
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <h3 class="text-gray-900 font-semibold text-base">
                  {{ $notification->data['title'] ?? 'Notification' }}
                </h3>
                <p class="text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>

                <!-- Additional Info -->
                @if(isset($notification->data['appointment_id']))
                  <a href="{{ route('admin.appointments.show', $notification->data['appointment_id']) }}"
                     class="inline-flex items-center gap-1 mt-2 text-sm text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    View Appointment Details
                  </a>
                @endif

                <!-- Time -->
                <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>{{ $notification->created_at->diffForHumans() }}</span>
                  @if(is_null($notification->read_at))
                    <span class="w-2 h-2 bg-red-500 rounded-full ml-2"></span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Right Side: Actions -->
            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2">
              @if(is_null($notification->read_at))
                <form action="{{ route('admin.notifications.mark-as-read', $notification->id) }}" method="POST">
                  @csrf
                  <button type="submit"
                          class="text-xs text-blue-600 bg-blue-100 px-3 py-1.5 rounded hover:bg-blue-200 transition-colors whitespace-nowrap">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mark as read
                  </button>
                </form>
              @endif

              <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this notification?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200 transition-colors">
                  <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  Delete
                </button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="p-12 text-center text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
          <h3 class="text-lg font-semibold text-gray-700 mb-2">No notifications found</h3>
          <p class="text-gray-500">You're all caught up! Check back later for updates.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
      <div class="px-6 py-4 border-t border-gray-200">
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
    if (strpos($type, 'Patient') !== false) return 'bg-purple-100 text-purple-600';
    return 'bg-gray-100 text-gray-600';
  }

  function getNotificationIcon($type) {
    if (strpos($type, 'Appointment') !== false) {
      return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
    }
    if (strpos($type, 'Reminder') !== false) {
      return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>';
    }
    if (strpos($type, 'Payment') !== false) {
      return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    if (strpos($type, 'Patient') !== false) {
      return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
    }
    return '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
  }
@endphp
