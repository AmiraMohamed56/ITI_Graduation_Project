@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Statistics Cards --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, Dr/ {{Auth::user()->name}}</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Today's Appointments --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
                </div>
                <span class="text-sm text-gray-500">Today</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $todayAppointments }}</h3>
            <p class="text-sm text-gray-600">Today's Appointments</p>
        </div>

        {{-- Total Patients --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-users text-2xl text-green-600"></i>
                </div>
                <span class="text-sm text-gray-500">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPatients }}</h3>
            <p class="text-sm text-gray-600">Total Patients</p>
        </div>

        {{-- Pending Appointments --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <span class="text-sm text-gray-500">Pending</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $pendingAppointments }}</h3>
            <p class="text-sm text-gray-600">Pending Appointments</p>
        </div>

        {{-- Completed Appointments --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-check-circle text-2xl text-purple-600"></i>
                </div>
                <span class="text-sm text-gray-500">Completed</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $completedAppointments }}</h3>
            <p class="text-sm text-gray-600">Completed Appointments</p>
        </div>
    </div>

     {{-- Patient Statistics Chart --}}
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Patients Statistics</h3>
                    <p class="text-sm text-gray-600 mt-1">Total No of Patients : <span class="font-semibold">{{ $totalPatientCount }}</span></p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                        <span class="text-sm text-gray-600">New Patients</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-200 rounded-full"></span>
                        <span class="text-sm text-gray-600">Old Patients</span>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex items-end justify-between h-80 gap-4">
                @php
                    $maxValue = max(array_column($patientStats, 'total'));
                    $maxHeight = 280; // pixels
                @endphp
                
                @foreach($patientStats as $stat)
                    @php
                        $totalHeight = $maxValue > 0 ? ($stat['total'] / $maxValue) * $maxHeight : 0;
                        $newHeight = $stat['total'] > 0 ? ($stat['new'] / $stat['total']) * $totalHeight : 0;
                        $oldHeight = $totalHeight - $newHeight;
                    @endphp
                    
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full flex flex-col items-center justify-end mb-3" style="height: {{ $maxHeight }}px;">
                            <div class="w-full max-w-16 rounded-t-lg overflow-hidden flex flex-col-reverse">
                                @if($stat['old'] > 0)
                                    <div class="bg-blue-200 w-full transition-all hover:bg-blue-300" 
                                         style="height: {{ $oldHeight }}px;"
                                         title="Old Patients: {{ $stat['old'] }}">
                                    </div>
                                @endif
                                @if($stat['new'] > 0)
                                    <div class="bg-blue-600 w-full transition-all hover:bg-blue-700" 
                                         style="height: {{ $newHeight }}px;"
                                         title="New Patients: {{ $stat['new'] }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">{{ $stat['date'] }}</span>
                    </div>
                @endforeach
            </div>
            
            {{-- Y-axis labels --}}
            <div class="flex justify-between items-center mt-2 pt-2 border-t">
                <span class="text-xs text-gray-500">0</span>
                <span class="text-xs text-gray-500">{{ ceil($maxValue / 4) }}</span>
                <span class="text-xs text-gray-500">{{ ceil($maxValue / 2) }}</span>
                <span class="text-xs text-gray-500">{{ ceil(3 * $maxValue / 4) }}</span>
                <span class="text-xs text-gray-500">{{ $maxValue }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Appointments Table --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Appointments</h3>
                    <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentAppointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $appointment->patient->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($appointment->patient->user->name) }}" 
                                             alt="{{ $appointment->patient->user->name }}" 
                                             class="w-8 h-8 rounded-full mr-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $appointment->patient->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->schedule_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ date('h:i A', strtotime($appointment->schedule_time)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700 capitalize">{{ $appointment->type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" 
                                        class="text-blue-600 hover:text-blue-800 text-xl">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No appointments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            {{-- Upcoming Appointments --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ $appointment->patient->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($appointment->patient->user->name) }}" 
                                     alt="{{ $appointment->patient->user->name }}" 
                                     class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="font-medium text-sm text-gray-900">{{ $appointment->patient->user->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $appointment->type }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $appointment->schedule_date->format('d M Y') }}</span>
                                <i class="fas fa-clock ml-2"></i>
                                <span>{{ date('h:i A', strtotime($appointment->schedule_time)) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No upcoming appointments</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Reviews --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Reviews</h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($recentReviews as $review)
                        <div class="p-4 border-b last:border-b-0">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ $review->patient->user->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) }}" 
                                     alt="{{ $review->patient->user->name }}" 
                                     class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="font-medium text-sm text-gray-900">{{ $review->patient->user->name }}</p>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-xs text-gray-600">{{ Str::limit($review->comment, 80) }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No reviews yet</p>
                    @endforelse
                </div>
            </div>

            {{-- Doctor Info Card --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ Auth::user()->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         alt="Profile" 
                         class="w-16 h-16 rounded-full border-4 border-white">
                    <div>
                        <h4 class="font-bold text-lg">Dr. {{ Auth::user()->name }}</h4>
                        <p class="font-medium text-sm text-gray-500">{{ $doctor->specialty->name ?? 'Specialist' }}</p>
                    </div>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-sm text-gray-500">Rating</span>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="font-semibold">{{ number_format($doctor->rating, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-sm text-gray-500">Experience</span>
                        <span class="font-semibold">{{ $doctor->years_experience }} years</span>
                    </div>
                </div>
                <a href="#" class="block w-full bg-white text-blue-600 text-center py-2 rounded-lg font-medium hover:bg-blue-50 transition">
                    View Full Profile
                </a>
            </div>
        </div>
    </div>
@endsection
