@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Appointments')

@section('content')

{{-- @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif --}}
@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif



<div class="min-h-screen bg-gray-50 p-8">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-900">Appointments</h1>

                <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                    <span>Dashboard</span>
                    <span>»</span>
                    <span>Appointments</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M20 20v-5h-.581m-15.357-2a8.003 8.003 0 0115.357 2">
                        </path>
                    </svg>
                </button>

                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" stroke="currentColor" fill="none" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M6 9l6-6 6 6M12 3v12"></path>
                    </svg>
                </button>

                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 4v16h16V4H4z"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white rounded-lg shadow">

        {{-- Table Header --}}
        <div class="flex items-center justify-between p-6 border-b">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-900">Total Appointments</h2>
                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-medium">
                    {{ $appointments->count() }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Sort By :</span>
                <select class="text-sm border-0 text-gray-900 font-medium focus:ring-0">
                    <option>Newest</option>
                    <option>Oldest</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Patient ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Patient Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Appointment Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @foreach ($appointments as $appointment)
                    <tr class="hover:bg-gray-50">

                        {{-- Appointment ID --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            #APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Patient Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-lg">
                                    {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $appointment->patient->user->name }}
                                </span>
                            </div>
                        </td>

                        {{-- Type --}}
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'consultation' => 'bg-purple-100 text-purple-800',
                                    'follow_up' => 'bg-green-100 text-green-800',
                                    'telemedicine' => 'bg-yellow-100 text-yellow-800',
                                ];
                            @endphp

                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                {{ $typeColors[$appointment->type] ?? 'bg-gray-200' }}">
                                {{ ucfirst($appointment->type) }}
                            </span>
                        </td>

                        {{-- Appointment Date --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d M Y') }}
                            ,
                            {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp

                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                {{ $statusColors[$appointment->status] ?? 'bg-gray-200' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 text-center flex items-center gap-3">

                            {{-- View --}}
                            <a href="{{ route('appointments.show', $appointment->id) }}"
                            class="text-blue-600 hover:text-blue-800 text-xl">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                            class="text-green-600 hover:text-green-800 text-xl">
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
