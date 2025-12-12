@extends('layouts.admin')
@section('title', 'Doctor Details')
@section('breadcrumb')
    <a href="{{ route('admin.doctors.index') }} " class="hover:underline">Doctors</a> / <span>{{ $doctor->user->name }}</span>
@endsection
@section('content')

    <div x-data="{ bio: false, education: false, experience: false, rating: false, created_at: false }"
        class="flex gap-6 p-6 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100">

        <!-- Left Sidebar -->
        <div class="w-96 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <!-- Doctor Header -->
            <div class="text-center mb-6">
                @if ($doctor->user->profile_pic)
                    <img src="{{ $doctor->user->profile_pic }}" alt="{{ $doctor->user->name }}"
                        class="w-24 h-24 rounded-full mx-auto mb-3 object-cover" />
                @else
                    <div
                        class="w-24 h-24 rounded-full mx-auto mb-3 bg-blue-500 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                    </div>
                @endif
                <p class="text-blue-600 dark:text-blue-400 text-sm font-medium mb-1">#DR{{ $doctor->id }}</p>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $doctor->user->name }}</h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $doctor->specialty->name }}</p>
            </div>

            <!-- Basic Information -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Basic Information</h2>
                <div class="space-y-3">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Specialty</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $doctor->specialty->name }}</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Gender</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst($doctor->gender) }}</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Experience</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $doctor->years_experience }}
                            years</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Email</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $doctor->user->email }}</span>
                    </div>

                    @if ($doctor->user->phone)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">Phone</span>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $doctor->user->phone }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Consultation Fee</span>
                        <span
                            class="text-gray-900 dark:text-gray-100 font-medium">{{ number_format($doctor->consultation_fee, 2) }}
                            EGP</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Online Consultation</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $doctor->available_for_online ? 'Available' : 'Not Available' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Content -->
        <div class="flex-1 space-y-4">

            <!-- About Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <button @click="bio = !bio" class="w-full flex justify-between items-center p-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Bio</h2>
                    <span x-show="bio">▲</span>
                    <span x-show="!bio">▼</span>
                </button>
                <div x-show="bio" class="px-6 pb-6 text-gray-600 dark:text-gray-300" x-cloak>
                    <p>{{ $doctor->bio ?? 'No bio provided.' }}</p>
                </div>
            </div>

            <!-- Education Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <button @click="education = !education" class="w-full flex justify-between items-center p-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Education</h2>
                    <span x-show="education">▲</span>
                    <span x-show="!education">▼</span>
                </button>
                <div x-show="education" class="px-6 pb-6 text-gray-600 dark:text-gray-300" x-cloak>
                    <p>{{ $doctor->education ?? 'No education info.' }}</p>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <button @click="experience = !experience" class="w-full flex justify-between items-center p-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Experience</h2>
                    <span x-show="experience">▲</span>
                    <span x-show="!experience">▼</span>
                </button>
                <div x-show="experience" class="px-6 pb-6 text-gray-600 dark:text-gray-300" x-cloak>
                    <p>{{ $doctor->years_experience }} years of experience</p>
                </div>
            </div>

            <!-- Rating Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <button @click="rating = !rating" class="w-full flex justify-between items-center p-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Rating</h2>
                    <span x-show="rating">▲</span>
                    <span x-show="!rating">▼</span>
                </button>
                <div x-show="rating" class="px-6 pb-6 text-gray-600 dark:text-gray-300" x-cloak>
                    <p>{{ $doctor->rating ?? 'No rating info.' }}</p>
                </div>
            </div>

            <!-- Created At Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <button @click="created_at = !created_at" class="w-full flex justify-between items-center p-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Created At</h2>
                    <span x-show="created_at">▲</span>
                    <span x-show="!created_at">▼</span>
                </button>
                <div x-show="created_at" class="px-6 pb-6 text-gray-600 dark:text-gray-300" x-cloak>
                    <p>{{ $doctor->created_at ?? 'No created at info.' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.doctors.index') }}"
                    class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded hover:bg-gray-700 dark:hover:bg-gray-600 text-sm font-medium">
                    ← Back to Doctors
                </a>
            </div>
        </div>
    </div>

@endsection
