@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <!-- Header Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Appointment Details</h1>
        <p class="text-gray-500 mt-1">Review all information about the appointment.</p>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">

        {{-- Card Header --}}
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-semibold text-gray-800">
                Appointment #{{ $appointment->id }}
            </h3>

            <a href="{{ route('appointments.edit', $appointment->id) }}"
               class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
                Edit
            </a>
        </div>

        <!-- Grid Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="space-y-1">
                <p class="text-gray-500">Patient Name</p>
                <p class="font-semibold text-gray-900 text-lg">
                    {{ $appointment->patient->user->name }}
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Doctor</p>
                <p class="font-semibold text-gray-900 text-lg">
                    Dr. {{ $appointment->doctor->user->name }}
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Date</p>
                <p class="font-semibold text-gray-900 text-lg">
                    {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('Y-m-d') }}
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Time</p>
                <p class="font-semibold text-gray-900 text-lg">
                    {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('H:i') }}
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Status</p>
                <span class="
                    inline-block px-4 py-1.5 text-sm font-medium rounded-full
                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                    @elseif($appointment->status == 'confirmed') bg-green-100 text-green-700
                    @elseif($appointment->status == 'cancelled') bg-red-100 text-red-700
                    @endif
                ">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Price</p>
                <p class="font-semibold text-gray-900 text-lg">
                    {{ $appointment->price }} EGP
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-gray-500">Created At</p>
                <p class="font-semibold text-gray-900 text-lg">
                    {{ $appointment->created_at->format('Y-m-d H:i') }}
                </p>
            </div>

        </div>

        <!-- Divider -->
        <hr class="my-8 border-gray-200">

        <!-- Notes -->
        @if($appointment->notes)
            <div>
                <p class="text-gray-500 mb-1">Notes</p>
                <p class="text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    {{ $appointment->notes }}
                </p>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-10">
            <a href="{{ route('appointments.index') }}"
               class="px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-800 transition">
                Back to Appointments
            </a>
        </div>

    </div>
</div>
@endsection
