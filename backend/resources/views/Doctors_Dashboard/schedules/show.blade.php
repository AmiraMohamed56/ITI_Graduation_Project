@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Schedule Details')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Doctor Schedule</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">View and manage availability schedule</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('schedules.edit', $schedule->id) }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-edit"></i>
                Edit Schedule
            </a>
            <form action="{{ route('schedules.destroy', $schedule->id) }}"
                method="POST"
                class="inline-block"
                onsubmit="return confirm('Are you sure you want to delete this schedule? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-trash"></i>
                    Delete Schedule
                </button>
            </form>
            <a href="{{ route('schedules.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>
</div>

<!-- Doctor Info Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <img
                    src="{{ $schedule->doctor->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($schedule->doctor->user->name) . '&background=4F46E5&color=fff&size=100' }}"
                    alt="Doctor"
                    class="w-20 h-20 rounded-full border-4 border-blue-100 dark:border-gray-700" />
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dr. {{ $schedule->doctor->user->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                        @if($schedule->doctor->specialty)
                        Specialist in {{ $schedule->doctor->specialty->name }}
                        @else
                        General Practitioner
                        @endif
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $schedule->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            {{ $schedule->is_active ? 'Active Schedule' : 'Inactive Schedule' }}
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ ucfirst($schedule->day_of_week) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 dark:text-gray-400">Schedule Created</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $schedule->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last updated: {{ $schedule->updated_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Day of Week</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ ucfirst($schedule->day_of_week) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600 dark:text-blue-300 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Slots</p>
                @php
                $start = \Carbon\Carbon::parse($schedule->start_time);
                $end = \Carbon\Carbon::parse($schedule->end_time);
                $totalMinutes = $start->diffInMinutes($end);
                $totalSlots = floor($totalMinutes / $schedule->appointment_duration);
                @endphp
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSlots }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-green-600 dark:text-green-300 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Appointment Duration</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $schedule->appointment_duration }} min</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 dark:text-purple-300 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Working Hours</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ floor($totalMinutes / 60) }}h {{ $totalMinutes % 60 }}m</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-half text-orange-600 dark:text-orange-300 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Details -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule Details</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Complete information about this schedule</p>
    </div>
    <div class="p-6">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 {{ $schedule->is_active ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-200 dark:bg-gray-700' }} rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-xs {{ $schedule->is_active ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }} font-semibold uppercase">
                                {{ substr($schedule->day_of_week, 0, 3) }}
                            </p>
                            <p class="text-lg font-bold {{ $schedule->is_active ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}">
                                <i class="fas fa-calendar-day"></i>
                            </p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold {{ $schedule->is_active ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ ucfirst($schedule->day_of_week) }}
                        </h4>
                        <p class="text-sm {{ $schedule->is_active ? 'text-gray-600 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}">
                            <i class="fas fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Available Slots</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalSlots }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        {{ $schedule->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                        <i class="fas {{ $schedule->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                        {{ $schedule->is_active ? 'Available' : 'Not Available' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Time Breakdown -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Time Breakdown</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Working Hours</h4>
                    <i class="fas fa-business-time text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Start Time:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">End Time:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Duration:</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ floor($totalMinutes / 60) }} hours {{ $totalMinutes % 60 }} minutes</span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Appointment Details</h4>
                    <i class="fas fa-user-clock text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Per Appointment:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $schedule->appointment_duration }} minutes</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Slots:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $totalSlots }} appointments</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="font-bold {{ $schedule->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Time Slots -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Time Slots</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">All appointment slots for this schedule</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @php
            $currentTime = \Carbon\Carbon::parse($schedule->start_time);
            $endTime = \Carbon\Carbon::parse($schedule->end_time);
            $slotNumber = 1;
            @endphp

            @while($currentTime->lt($endTime))
            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-3 text-center hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-500 dark:hover:border-blue-400 transition-all cursor-pointer">
                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Slot {{ $slotNumber }}</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $currentTime->format('h:i A') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
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
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Doctor Information</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-md text-blue-600 dark:text-blue-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Doctor Name</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Dr. {{ $schedule->doctor->user->name }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope text-green-600 dark:text-green-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $schedule->doctor->user->email }}</p>
                </div>
            </div>

            @if($schedule->doctor->user->phone)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-phone text-purple-600 dark:text-purple-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $schedule->doctor->user->phone }}</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->years_experience)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-award text-yellow-600 dark:text-yellow-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Experience</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $schedule->doctor->years_experience }} years</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->consultation_fee)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-dollar-sign text-red-600 dark:text-red-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consultation Fee</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($schedule->doctor->consultation_fee, 2) }}</p>
                </div>
            </div>
            @endif

            @if($schedule->doctor->available_for_online !== null)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-video text-indigo-600 dark:text-indigo-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Online Consultation</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
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
<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700 mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">About Doctor</h3>
    </div>
    <div class="p-6">
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <p class="text-gray-700 dark:text-gray-300">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                {{ $schedule->doctor->bio }}
            </p>
        </div>
    </div>
</div>
@endif

<!-- Bottom Actions -->
<div class="mt-6 flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg shadow dark:shadow-gray-700">
    <a href="{{ route('schedules.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        Back to Schedules List
    </a>
    <div class="flex items-center gap-3">
        <button
            onclick="window.print()"
            class="flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-print"></i>
            Print Schedule
        </button>
        <button
            onclick="alert('Share feature coming soon!')"
            class="flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-share-alt"></i>
            Share Schedule
        </button>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('
        success ') }}');
</script>
@endif

@if(session('error'))
<script>
    alert('{{ session('
        error ') }}');
</script>
@endif
@endsection
