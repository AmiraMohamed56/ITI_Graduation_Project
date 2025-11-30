@extends('layouts.admin')
@section('title', 'Patient Details')

@section('content')
<div x-data="{ info: true, chronic: false, appointments: true }"
    class="p-4 md:p-6 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Left Sidebar -->
        <div class="w-full lg:w-96 bg-white dark:bg-gray-800 rounded-xl shadow p-6">

            <!-- Profile Image -->
            @php
            $name = $patient->user->name ?? 'No Name';
            $firstLetter = strtoupper(substr($name, 0, 1));

            // ألوان ثابتة حسب الحرف
            $colors = [
            'A' => 'bg-red-500', 'B' => 'bg-blue-500', 'C' => 'bg-green-500',
            'D' => 'bg-purple-500', 'E' => 'bg-pink-500', 'F' => 'bg-indigo-500',
            'G' => 'bg-yellow-500', 'H' => 'bg-teal-500', 'I' => 'bg-orange-500',
            'J' => 'bg-cyan-500', 'K' => 'bg-emerald-500', 'L' => 'bg-fuchsia-500',
            'M' => 'bg-rose-500', 'N' => 'bg-lime-500', 'O' => 'bg-sky-500',
            'P' => 'bg-violet-500','Q' => 'bg-amber-500', 'R' => 'bg-stone-500',
            'S' => 'bg-red-600', 'T' => 'bg-blue-600', 'U' => 'bg-green-600',
            'V' => 'bg-purple-600','W' => 'bg-pink-600', 'X' => 'bg-indigo-600',
            'Y' => 'bg-teal-600', 'Z' => 'bg-orange-600',
            ];

            $bgColor = $colors[$firstLetter] ?? 'bg-gray-500';
            @endphp

            <div class="text-center mb-6">

                @if ($patient->user->profile_pic)
                <!-- صورة من قاعدة البيانات -->
                <div class="mx-auto w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden shadow border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset($patient->user->profile_pic) }}"
                        alt="{{ $patient->user->name }}"
                        class="w-full h-full object-cover">
                </div>
                @else
                <!-- Avatar بديل -->
                <div class="mx-auto w-28 h-28 md:w-32 md:h-32 rounded-full shadow flex items-center justify-center text-white text-4xl font-bold {{ $bgColor }}">
                    {{ $firstLetter }}
                </div>
                @endif

                <h1 class="text-2xl font-bold mt-3">{{ $patient->user->name }}</h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm">#PT{{ $patient->id }}</p>
            </div>


            <!-- Basic Information -->
            <div class="mb-6">
                <h2 class="text-lg font-bold mb-4">Basic Information</h2>
                <div class="space-y-3">

                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-600 dark:text-gray-300">Email</span>
                        <span class="font-medium">{{ $patient->user->email }}</span>
                    </div>

                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-600 dark:text-gray-300">Phone</span>
                        <span class="font-medium">{{ $patient->user->phone }}</span>
                    </div>

                    <div class="flex justify-between py-2 text-sm">
                        <span class="text-gray-600 dark:text-gray-300">Blood Type</span>
                        <span class="font-medium">{{ $patient->blood_type ?? '-' }}</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right Content -->
        <div class="flex-1 space-y-4">

            <!-- Chronic Diseases -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                <button @click="chronic = !chronic"
                    class="w-full flex justify-between items-center p-5">
                    <h2 class="text-xl font-bold">Chronic Diseases</h2>
                    <span x-show="chronic">▲</span>
                    <span x-show="!chronic">▼</span>
                </button>

                <div x-show="chronic" x-cloak class="px-6 pb-6">
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $patient->chronic_diseases ?: 'None' }}
                    </p>
                </div>
            </div>

            <!-- Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                <button @click="appointments = !appointments"
                    class="w-full flex justify-between items-center p-5">
                    <h2 class="text-xl font-bold">Appointments</h2>
                    <span x-show="appointments">▲</span>
                    <span x-show="!appointments">▼</span>
                </button>

                <div x-show="appointments" x-cloak class="px-6 pb-6">

                    @if($appointments->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No appointments found.</p>

                    @else
                    <!-- Responsive: Table on Desktop, Cards on Mobile -->

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
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
                                    <td class="p-3 border dark:border-gray-600 capitalize">{{ $appointment->type }}</td>
                                    <td class="p-3 border dark:border-gray-600 capitalize">{{ $appointment->status }}</td>
                                    <td class="p-3 border dark:border-gray-600">${{ number_format($appointment->price, 2) }}</td>
                                    <td class="p-3 border dark:border-gray-600">{{ $appointment->notes ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @foreach($appointments as $appointment)
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 space-y-2 shadow">

                            <div class="flex justify-between">
                                <span class="font-bold">Appointment #{{ $appointment->id }}</span>
                                <span class="capitalize text-sm">{{ $appointment->status }}</span>
                            </div>

                            <p><span class="font-semibold">Doctor:</span> {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Date:</span> {{ $appointment->schedule_date->format('Y-m-d') }}</p>
                            <p><span class="font-semibold">Time:</span> {{ $appointment->schedule_time->format('H:i') }}</p>
                            <p><span class="font-semibold">Type:</span> {{ ucfirst($appointment->type) }}</p>
                            <p><span class="font-semibold">Price:</span> ${{ number_format($appointment->price, 2) }}</p>
                            <p><span class="font-semibold">Notes:</span> {{ $appointment->notes ?: '-' }}</p>

                        </div>
                        @endforeach
                    </div>

                    @endif

                </div>
            </div>

            <a href="{{ route('admin.patients.index') }}"
                class="inline-block px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 text-sm">
                ← Back to Patients
            </a>

        </div>
    </div>

</div>
@endsection
