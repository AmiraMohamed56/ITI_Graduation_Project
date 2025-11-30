@extends('layouts.admin')
@section('title', 'Patient Details')

@section('content')
<div x-data="{ info: true, chronic: false, appointments: true }"
    class="flex gap-6 p-6 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

    <!-- Left Sidebar -->
    <div class="w-96 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="text-center mb-6">
            <img
                src="{{ $patient->user->profile_pic ?? 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=200&h=200&fit=crop' }}"
                alt="{{ $patient->user->name }}"
                class="w-24 h-24 rounded-lg mx-auto mb-3 object-cover" />
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $patient->user->name }}</h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm">#PT{{ $patient->id }}</p>
        </div>

        <!-- Basic Information -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Basic Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-300">Email</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $patient->user->email }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-300">Phone</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $patient->user->phone }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-300">Blood Type</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $patient->blood_type ?? '-' }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Content -->
    <div class="flex-1 space-y-4">

        <!-- Chronic Diseases Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <button @click="chronic = !chronic" class="w-full flex justify-between items-center p-6 text-left">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Chronic Diseases</h2>
                <span x-show="chronic">▲</span>
                <span x-show="!chronic">▼</span>
            </button>
            <div x-show="chronic" class="px-6 pb-6" x-cloak>
                <p class="text-gray-600 dark:text-gray-300">{{ $patient->chronic_diseases ?: 'None' }}</p>
            </div>
        </div>

        <!-- Appointments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <button @click="appointments = !appointments" class="w-full flex justify-between items-center p-6 text-left">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Appointments</h2>
                <span x-show="appointments">▲</span>
                <span x-show="!appointments">▼</span>
            </button>
            <div x-show="appointments" class="px-6 pb-6 overflow-x-auto" x-cloak>
                @if($appointments->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No appointments found for this patient.</p>
                @else
                <table class="w-full border-collapse border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="p-3 border dark:border-gray-600">#</th>
                            <th class="p-3 border dark:border-gray-600">Doctor</th>
                            <th class="p-3 border dark:border-gray-600">Date</th>
                            <th class="p-3 border dark:border-gray-600">Time</th>
                            <th class="p-3 border dark:border-gray-600">Type</th>
                            <th class="p-3 border dark:border-gray-600">Status</th>
                            <th class="p-3 border dark:border-gray-600">Price</th>
                            <th class="p-3 border dark:border-gray-600">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr class="border-b dark:border-gray-700">
                            <td class="p-3 border dark:border-gray-600">{{ $appointment->id }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ $appointment->schedule_date->format('Y-m-d') }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ $appointment->schedule_time->format('H:i') }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ ucfirst($appointment->type) }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ ucfirst($appointment->status) }}</td>
                            <td class="p-3 border dark:border-gray-600">${{ number_format($appointment->price, 2) }}</td>
                            <td class="p-3 border dark:border-gray-600">{{ $appointment->notes ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.patients.index') }}"
                class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded hover:bg-gray-700 dark:hover:bg-gray-600 text-sm font-medium">
                ← Back to Patients
            </a>
        </div>

    </div>
</div>

@endsection
