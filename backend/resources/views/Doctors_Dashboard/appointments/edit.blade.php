@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Appointment</h1>
        <p class="text-gray-500 mt-1">Modify appointment details and update status.</p>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">

        <h3 class="text-xl font-semibold text-gray-800 mb-6">
            Appointment #{{ $appointment->id }}
        </h3>

        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Patient Name -->
                <div>
                    <label class="text-gray-500 mb-1 block">Patient Name</label>
                    <input type="text" 
                           class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 text-gray-700"
                           value="{{ $appointment->patient->user->name }}"
                           disabled>
                </div>

                <!-- Doctor Name -->
                <div>
                    <label class="text-gray-500 mb-1 block">Doctor</label>
                    <input type="text" 
                           class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 text-gray-700"
                           value="Dr. {{ $appointment->doctor->user->name }}"
                           disabled>
                </div>

                <!-- Appointment Date -->
                <div>
                    <label class="text-gray-500 mb-1 block">Date</label>
                    <input type="date" name="schedule_date"
                           class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           value="{{ \Carbon\Carbon::parse($appointment->schedule_date)->format('Y-m-d') }}"
                           disabled>
                </div>

                <!-- Appointment Time -->
                <div>
                    <label class="text-gray-500 mb-1 block">Time</label>
                    <input type="time" name="schedule_time"
                           class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           value="{{ \Carbon\Carbon::parse($appointment->schedule_time)->format('H:i') }}"
                           disabled>
                </div>

                <!-- Status -->
                <div>
                    <label class="text-gray-500 mb-1 block">Status</label>
                    <select name="status"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending" 
                            @selected($appointment->status == 'pending')>
                            Pending
                        </option>

                        <option value="confirmed"
                            @selected($appointment->status == 'confirmed')>
                            Confirmed
                        </option>

                        <option value="cancelled"
                            @selected($appointment->status == 'cancelled')>
                            Cancelled
                        </option>

                        <option value="completed"
                            @selected($appointment->status == 'completed')>
                            Completed
                        </option>
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label class="text-gray-500 mb-1 block">Price (EGP)</label>
                    <input type="number" name="price" step="0.01"
                           class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           value="{{ $appointment->price }}"
                           disabled>
                </div>

            </div>

            <!-- Notes -->
            <div class="mt-8">
                <label class="text-gray-500 mb-1 block">Notes</label>
                <textarea name="notes" rows="4"
                          class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $appointment->notes }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between mt-10">

                <!-- Back Button -->
                <a href="{{ route('appointments.index') }}"
                   class="px-5 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition">
                    Back
                </a>

                <!-- Submit Button -->
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
