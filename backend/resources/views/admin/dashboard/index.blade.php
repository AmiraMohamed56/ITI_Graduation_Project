@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }} " class="hover:underline">Dashboard</a>
@endsection

@section('content')

<div x-data="{ activeChart: 'overview' }" class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Dashboard Overview</h1>
                <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $from->format('M d, Y') }} - {{ $to->format('M d, Y') }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span class="inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Period:
                </span>

                <form method="GET" class="flex items-center gap-2">
                    <input
                        type="date"
                        name="from"
                        value="{{ $from->format('Y-m-d') }}"
                        class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">to</span>
                    <input
                        type="date"
                        name="to"
                        value="{{ $to->format('Y-m-d') }}"
                        class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button
                        type="submit"
                        class="px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
            $statCards = [
            ['title' => 'Total Patients', 'value' => $stats['patients'], 'color' => 'blue', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>'],
            ['title' => 'Total Doctors', 'value' => $stats['doctors'], 'color' => 'green', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422M12 14l-6.16-3.422M12 14v7" />
            </svg>'],
            ['title' => 'Specialties', 'value' => $stats['specialties'], 'color' => 'purple', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c2.21 0 4-1.79 4-4S14.21 0 12 0 8 1.79 8 4s1.79 4 4 4zM12 8v8M12 16h.01" />
            </svg>'],
            ['title' => 'Total Appointments', 'value' => $stats['appointments'], 'color' => 'indigo', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>'],
            ['title' => 'Total Revenue', 'value' => '$' . number_format($stats['revenue']), 'color' => 'yellow', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z" />
            </svg>'],
            ['title' => 'Pending', 'value' => $stats['pending'], 'color' => 'orange', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
            </svg>'],
            ['title' => 'Confirmed', 'value' => $stats['confirmed'], 'color' => 'teal', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>'],
            ['title' => 'Completed', 'value' => $stats['completed'], 'color' => 'green', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>'],
            ['title' => 'Cancelled', 'value' => $stats['cancelled'], 'color' => 'red', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>'],
            ];
            @endphp


            @foreach($statCards as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition-all duration-300 p-6 border-l-4 border-{{ $stat['color'] }}-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stat['value'] }}</p>
                    </div>
                    <span class="text-2xl">{!! $stat['icon'] !!}</span>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Trend</span>
                        <span class="text-green-500 font-medium">+12.5%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Chart Selection Tabs -->
        <div class="mb-6">
            <div class="flex space-x-1 bg-gray-200 dark:bg-gray-700 p-1 rounded-xl">
                <button
                    @click="activeChart = 'overview'"
                    :class="activeChart === 'overview' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'"
                    class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    System Overview
                </button>
                <button
                    @click="activeChart = 'appointments'"
                    :class="activeChart === 'appointments' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'"
                    class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    Appointments (7 Days)
                </button>
                <button
                    @click="activeChart = 'revenue'"
                    :class="activeChart === 'revenue' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300'"
                    class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    Revenue Per Day
                </button>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <!-- System Overview Chart -->
            <div x-show="activeChart === 'overview'" x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">System Overview</h2>
                    <select class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-1 text-sm">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <canvas id="overviewChart" class="h-64"></canvas>
            </div>

            <!-- Appointments 7 Days Chart -->
            <div x-show="activeChart === 'appointments'" x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Appointments - Last 7 Days</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->subDays(6)->format('M d') }} - {{ now()->format('M d') }}</span>
                </div>
                <canvas id="appointments7Days" class="h-64"></canvas>
            </div>

            <!-- Revenue Chart -->
            <div x-show="activeChart === 'revenue'" x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Revenue Per Day</h2>

                </div>
                <canvas id="revenueChart" class="h-64"></canvas>
            </div>

            <!-- Appointments Status Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-900 dark:text-gray-100 mb-6">Appointments Status</h2>
                <canvas id="statusChart" class="h-64"></canvas>
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                    $statuses = [
                    'pending' => ['count' => $stats['pending'], 'color' => 'bg-yellow-500', 'text' => 'Pending'],
                    'confirmed' => ['count' => $stats['confirmed'], 'color' => 'bg-blue-500', 'text' => 'Confirmed'],
                    'completed' => ['count' => $stats['completed'], 'color' => 'bg-green-500', 'text' => 'Completed'],
                    'cancelled' => ['count' => $stats['cancelled'], 'color' => 'bg-red-500', 'text' => 'Cancelled'],
                    ];
                    @endphp
                    @foreach($statuses as $status)
                    <div class="flex items-center gap-3">
                        <div class="{{ $status['color'] }} w-3 h-3 rounded-full"></div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $status['text'] }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $status['count'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Specialties Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Top Specialties</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">By Appointment Count</span>
                </div>
                <canvas id="specialtiesChart" class="h-64"></canvas>
            </div>
        </div>

        <!-- Top Doctors Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-10">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Top Rated Doctors</h2>
                    <a href="{{ route('admin.doctors.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        View All →
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Doctor</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Specialty</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Rating</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Experience</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Appointments</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($topDoctors as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                    $image = $doc->user->profile_pic ?? null;
                                    $name = $doc->user->name;
                                    $initial = strtoupper(substr($name, 0, 1));
                                    @endphp

                                    @if($image)
                                    <img src="{{ asset('storage/' . $image) }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                    @else
                                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                        {{ $initial }}
                                    </div>
                                    @endif

                                    <div>
                                        <a href="{{ route('admin.doctors.show', $doc->id) }}"
                                            class="font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $doc->user->name }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $doc->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $doc->specialty->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $doc->rating ? ' text-yellow-400' : ' text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($doc->rating, 1) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $doc->years_experience }} years
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                                    {{ $doc->appointments_count ?? 0 }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Appointments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Latest Appointments</h2>
                    <a href="{{ route('admin.appointments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        View All →
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Patient</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Doctor</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Date & Time</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($latestAppointments as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                    $patientImage = $a->patient->user->profile_pic ?? null;
                                    $patientName = $a->patient->user->name;
                                    $patientInitial = strtoupper(substr($patientName, 0, 1));
                                    @endphp

                                    @if($patientImage)
                                    <img src="{{ asset('storage/' . $patientImage) }}"
                                        class="w-8 h-8 rounded-full object-cover">
                                    @else
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ $patientInitial }}
                                    </div>
                                    @endif

                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $patientName }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $a->patient->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $a->doctor->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $a->doctor->specialty->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $a->schedule_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $a->schedule_time }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $a->type == 'consultation' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                       ($a->type == 'follow_up' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                       'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                    {{ ucfirst($a->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$a->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($a->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ===== Pass Data to JS ===== --}}
<script>
    window.dashboardStats = @json($stats);
    window.last7Days = @json($last7Days);
    window.topSpecialties = @json($topSpecialties);
    window.revenuePerDay = @json($revenuePerDay);
</script>

@vite(['resources/js/dashboard.js'])
@endsection
