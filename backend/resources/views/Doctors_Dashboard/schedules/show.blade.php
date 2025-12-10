@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Schedule Details')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Doctor Schedule</h1>
            <p class="text-gray-600 mt-1">View and manage availability schedule</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('schedules.edit', $schedule->id) }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit"></i>
                Edit Schedule
            </a>
            {{-- <form action="{{ route('schedules.destroy', $schedule->id) }}"
                  method="POST"
                  class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this schedule? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-trash"></i>
                    Delete Schedule
                </button>
            </form> --}}
            {{-- trigger delete modal --}}
            <x-admin.button type="danger" id="delete-button">Delete appointment</x-admin.button>

            <!-- Confirmation Modal (Initially Hidden) -->
            <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
                    <h2 class="text-xl mb-4">Are you sure you want to delete this schedule?</h2>
                    <div class="flex justify-end gap-4">
                        <!-- Cancel Button -->
                        <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                        {{-- delete form --}}
                        <form id="delete-form" action="{{ route('schedules.destroy', $schedule->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                        </form>
                    </div>
                </div>
            </div>
            <a href="{{ route('schedules.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>
</div>

<!-- Doctor Info Card -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <img
                    src="{{ $schedule->doctor->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($schedule->doctor->user->name) . '&background=4F46E5&color=fff&size=100' }}"
                    alt="Doctor"
                    class="w-20 h-20 rounded-full border-4 border-blue-100"
                />
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Dr. {{ $schedule->doctor->user->name }}</h2>
                    <p class="text-gray-600 mt-1">
                        @if($schedule->doctor->specialty)
                            Specialist in {{ $schedule->doctor->specialty->name }}
                        @else
                            General Practitioner
                        @endif
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $schedule->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            {{ $schedule->is_active ? 'Active Schedule' : 'Inactive Schedule' }}
                        </span>
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ ucfirst($schedule->day_of_week) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Schedule Created</p>
                <p class="text-lg font-semibold text-gray-900">{{ $schedule->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">Last updated: {{ $schedule->updated_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Day of Week</p>
                <p class="text-2xl font-bold text-gray-900">{{ ucfirst($schedule->day_of_week) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Slots</p>
                @php
                    $start = \Carbon\Carbon::parse($schedule->start_time);
                    $end = \Carbon\Carbon::parse($schedule->end_time);
                    $totalMinutes = $start->diffInMinutes($end);
                    $totalSlots = floor($totalMinutes / $schedule->appointment_duration);
                @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $totalSlots }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Appointment Duration</p>
                <p class="text-2xl font-bold text-gray-900">{{ $schedule->appointment_duration }} min</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Working Hours</p>
                <p class="text-2xl font-bold text-gray-900">{{ floor($totalMinutes / 60) }}h {{ $totalMinutes % 60 }}m</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-half text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Details -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Schedule Details</h3>
        <p class="text-sm text-gray-600 mt-1">Complete information about this schedule</p>
    </div>
    <div class="p-6">
        <div class="border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 {{ $schedule->is_active ? 'bg-blue-100' : 'bg-gray-200' }} rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-xs {{ $schedule->is_active ? 'text-blue-600' : 'text-gray-500' }} font-semibold uppercase">
                                {{ substr($schedule->day_of_week, 0, 3) }}
                            </p>
                            <p class="text-lg font-bold {{ $schedule->is_active ? 'text-blue-600' : 'text-gray-500' }}">
                                <i class="fas fa-calendar-day"></i>
                            </p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold {{ $schedule->is_active ? 'text-gray-900' : 'text-gray-500' }}">
                            {{ ucfirst($schedule->day_of_week) }}
                        </h4>
                        <p class="text-sm {{ $schedule->is_active ? 'text-gray-600' : 'text-gray-500' }}">
                            <i class="fas fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Available Slots</p>
                        <p class="text-xl font-bold text-gray-900">{{ $totalSlots }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        {{ $schedule->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                        <i class="fas {{ $schedule->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                        {{ $schedule->is_active ? 'Available' : 'Not Available' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Time Breakdown -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Time Breakdown</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Working Hours</h4>
                    <i class="fas fa-business-time text-blue-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Start Time:</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">End Time:</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Total Duration:</span>
                        <span class="font-bold text-blue-600">{{ floor($totalMinutes / 60) }} hours {{ $totalMinutes % 60 }} minutes</span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Appointment Details</h4>
                    <i class="fas fa-user-clock text-green-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Per Appointment:</span>
                        <span class="font-medium text-gray-900">{{ $schedule->appointment_duration }} minutes</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Slots:</span>
                        <span class="font-medium text-gray-900">{{ $totalSlots }} appointments</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="font-bold {{ $schedule->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Time Slots -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Available Time Slots</h3>
        <p class="text-sm text-gray-600 mt-1">All appointment slots for this schedule</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @php
                $currentTime = \Carbon\Carbon::parse($schedule->start_time);
                $endTime = \Carbon\Carbon::parse($schedule->end_time);
                $slotNumber = 1;
            @endphp

            @while($currentTime->lt($endTime))
                <div class="border border-gray-300 rounded-lg p-3 text-center hover:bg-blue-50 hover:border-blue-500 transition-all cursor-pointer">
                    <p class="text-xs text-gray-600 mb-1">Slot {{ $slotNumber }}</p>
                    <p class="font-semibold text-gray-900">{{ $currentTime->format('h:i A') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $currentTime->copy()->addMinutes($schedule->appointment_duration)->format('h:i A') }}
                    </p>
                </div>
                @php
                    $currentTime->addMinutes($schedule->appointment_duration);
                    $slotNumber++;
                @endphp
            @endwhile
        </div>
    </div>
</div>

<!-- Doctor Information -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Doctor Information</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-md text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Doctor Name</p>
                    <p class="text-lg font-semibold text-gray-900">Dr. {{ $schedule->doctor->user->name }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $schedule->doctor->user->email }}</p>
                </div>
            </div>

            @if($schedule->doctor->user->phone)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-phone text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phone</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $schedule->doctor->user->phone }}</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->years_experience)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-award text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Experience</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $schedule->doctor->years_experience }} years</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->consultation_fee)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-dollar-sign text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Consultation Fee</p>
                    <p class="text-lg font-semibold text-gray-900">${{ number_format($schedule->doctor->consultation_fee, 2) }}</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->available_for_online !== null)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-video text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Online Consultation</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $schedule->doctor->available_for_online ? 'Available' : 'Not Available' }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Special Notes -->
@if($schedule->doctor->bio)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">About Doctor</h3>
    </div>
    <div class="p-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-gray-700">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $schedule->doctor->bio }}
            </p>
        </div>
    </div>
</div>
@endif

<!-- Bottom Actions -->
<div class="mt-6 flex items-center justify-between bg-white p-4 rounded-lg shadow">
    <a href="{{ route('schedules.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        Back to Schedules List
    </a>
    <div class="flex items-center gap-3">
        <button
            onclick="window.print()"
            class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-print"></i>
            Print Schedule
        </button>
        <button
            onclick="alert('Share feature coming soon!')"
            class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-share-alt"></i>
            Share Schedule
        </button>
    </div>
</div>

{{-- @if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif --}}
@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButton = document.getElementById("delete-button");
        const cancelButton = document.getElementById("cancel-button");
        const modal = document.getElementById("modal");
        const deleteForm = document.getElementById("delete-form");

        // Show Modal
        deleteButton.addEventListener("click", function() {
            modal.classList.remove("hidden");
        });

        // Hide Modal (Cancel)
        cancelButton.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

    });
</script>
@endsection
