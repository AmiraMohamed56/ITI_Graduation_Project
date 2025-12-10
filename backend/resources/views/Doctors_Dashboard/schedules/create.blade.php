@extends('Doctors_Dashboard.layouts.app')

@section('title', $schedule->exists ? 'Edit Schedule' : 'Create Schedule')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $schedule->exists ? 'Edit Schedule' : 'Create Schedule' }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">
                    {{ $schedule->exists ? 'Update doctor schedule information' : 'Set up a new schedule for appointments' }}
                </p>
            </div>
            <a href="{{ route('schedules.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form
        action="{{ $schedule->exists ? route('schedules.update', $schedule) : route('schedules.store') }}"
        method="POST"
        class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700">
        @csrf
        @if($schedule->exists)
        @method('PUT')
        @endif

        <div class="p-6">
            <!-- Schedule Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Schedule Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Day of Week -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Day of Week <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="day_of_week"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">Select a day</option>
                            <option value="sunday" {{ old('day_of_week', $schedule->day_of_week) == 'sunday' ? 'selected' : '' }}>Sunday</option>
                            <option value="monday" {{ old('day_of_week', $schedule->day_of_week) == 'monday' ? 'selected' : '' }}>Monday</option>
                            <option value="tuesday" {{ old('day_of_week', $schedule->day_of_week) == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="wednesday" {{ old('day_of_week', $schedule->day_of_week) == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="thursday" {{ old('day_of_week', $schedule->day_of_week) == 'thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="friday" {{ old('day_of_week', $schedule->day_of_week) == 'friday' ? 'selected' : '' }}>Friday</option>
                            <option value="saturday" {{ old('day_of_week', $schedule->day_of_week) == 'saturday' ? 'selected' : '' }}>Saturday</option>
                        </select>
                        @error('day_of_week')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appointment Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Appointment Duration (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="appointment_duration"
                            value="{{ old('appointment_duration', $schedule->appointment_duration) }}"
                            min="5"
                            max="480"
                            placeholder="30"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                        @error('appointment_duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            name="start_time"
                            value="{{ old('start_time', $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                        @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            name="end_time"
                            value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                        @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }}
                                class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500 rounded">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Active Schedule
                            </span>
                        </label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-7">
                            Uncheck to temporarily disable this schedule without deleting it
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-lg border-t border-gray-200 dark:border-gray-600 flex items-center justify-end gap-3">
            <a
                href="{{ route('schedules.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button
                type="submit"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i>
                {{ $schedule->exists ? 'Update Schedule' : 'Create Schedule' }}
            </button>
        </div>
    </form>
</div>
@endsection
