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

  <!-- Notifications Card -->
  <div class="bg-white rounded-lg shadow">

    <!-- Card Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
          <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">
            02
          </span>
        </div>

        <div class="flex items-center gap-3">
          <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
            Mark all as read
          </button>

          <button class="flex items-center gap-2 text-sm text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition-colors">
            Delete All
          </button>
        </div>
      </div>


    </div>

    <!-- Notifications List -->
    <div class="divide-y divide-gray-200">

      <!-- Notification 1 -->
      <div class="p-6 bg-blue-50 hover:bg-gray-50 transition-colors">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-4 flex-1">
            <img src="https://ui-avatars.com/api/?name=John+Doe&background=4F46E5&color=fff"
              class="w-12 h-12 rounded-full" />

            <div>
              <p class="text-gray-900">
                <span class="font-semibold">John Doe</span>
                <span class="text-gray-600">added new patient</span>
                <span class="font-semibold">appointment booking</span>
              </p>

              <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                <span>⏱</span>
                <span>4 min ago</span>
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button class="text-xs text-blue-600 bg-blue-100 px-3 py-1.5 rounded hover:bg-blue-200">
              Mark as read
            </button>
            <button class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200">
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Notification 2 -->
      <div class="p-6 bg-blue-50 hover:bg-gray-50 transition-colors">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-4 flex-1">
            <img src="https://ui-avatars.com/api/?name=Thomas+William&background=059669&color=fff"
              class="w-12 h-12 rounded-full" />

            <div>
              <p class="text-gray-900">
                <span class="font-semibold">Thomas William</span>
                <span class="text-gray-600">booked a new appointment.</span>
              </p>

              <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                <span>⏱</span> 15 min ago
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button class="text-xs text-blue-600 bg-blue-100 px-3 py-1.5 rounded hover:bg-blue-200">
               Mark as read
            </button>
            <button class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200">
               Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Notification 3 -->
      <div class="p-6 hover:bg-gray-50 transition-colors">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-4 flex-1">
            <img src="https://ui-avatars.com/api/?name=Sarah+Anderson&background=DC2626&color=fff"
              class="w-12 h-12 rounded-full" />
            <div>
              <p class="text-gray-900">
                <span class="font-semibold">Sarah Anderson</span>
                <span class="text-gray-600">has been successfully booked for</span>
                <span class="font-semibold">April 5 at 10:00 AM.</span>
              </p>
              <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                <span>⏱</span> 45 min ago
              </div>
            </div>
          </div>

          <button class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200">
            Delete
          </button>
        </div>
      </div>

      <!-- Notification 4 -->
      <div class="p-6 hover:bg-gray-50 transition-colors">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-4 flex-1">
            <img src="https://ui-avatars.com/api/?name=Ann+McClure&background=9333EA&color=fff"
              class="w-12 h-12 rounded-full" />
            <div>
              <p class="text-gray-900">
                <span class="font-semibold">Ann McClure</span>
                <span class="text-gray-600">cancelled her appointment scheduled for</span>
                <span class="font-semibold">February 5, 2024</span>
              </p>
              <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                <span>⏱</span> 58 min ago
              </div>
            </div>
          </div>

          <button class="text-xs text-red-600 bg-red-100 px-3 py-1.5 rounded hover:bg-red-200">
            Delete
          </button>
        </div>
      </div>

    </div>
  </div>
  @endsection


