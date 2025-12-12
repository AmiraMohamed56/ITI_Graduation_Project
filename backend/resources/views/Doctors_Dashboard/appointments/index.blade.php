@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Appointments')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-700 dark:text-green-300" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-8">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointments</h1>

                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mt-1">
                    <span>Dashboard</span>
                    <span>»</span>
                    <span>Appointments</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700">

        {{-- Table Header --}}
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Total Appointments</h2>
                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-medium">
                    {{ $appointments->count() }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Sort By :</span>
                <select class="text-sm border-0 text-gray-900 dark:text-white font-medium focus:ring-0 bg-transparent dark:bg-gray-800">
                    <option class="dark:bg-gray-800">Newest</option>
                    <option class="dark:bg-gray-800">Oldest</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Patient ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Patient Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Appointment Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach ($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                        {{-- Appointment ID --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            #APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Patient Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-lg dark:text-white">
                                    {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->patient->user->name }}
                                </span>
                            </div>
                        </td>

                        {{-- Type --}}
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'consultation' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                    'follow_up' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'telemedicine' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                ];
                            @endphp

                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                {{ $typeColors[$appointment->type] ?? 'bg-gray-200 dark:bg-gray-600 dark:text-gray-300' }}">
                                {{ ucfirst($appointment->type) }}
                            </span>
                        </td>

                        {{-- Appointment Date --}}
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d M Y') }}
                            ,
                            {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'confirmed' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                ];
                            @endphp

                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                {{ $statusColors[$appointment->status] ?? 'bg-gray-200 dark:bg-gray-600 dark:text-gray-300' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 text-center flex items-center gap-3">

                            {{-- View --}}
                            <a href="{{ route('appointments.show', $appointment->id) }}"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xl">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-xl">
                                <i class="fas fa-edit"></i>
                            </a>

                        </td>


                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection
