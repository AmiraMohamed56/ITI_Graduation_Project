@extends('layouts.admin')
@section('title', 'Edit Doctor')
@section('breadcrumb')
    <a href="{{ route('admin.doctors.index') }} " class="hover:underline">Doctors</a> / <a href="{{ route('admin.doctors.show', $doctor) }}" class="hover:underline"><span>{{ $doctor->user->name }}</span></a> / <span>edit</span>
@endsection
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 py-10 px-6">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Doctor</h1>
                <a href="{{ route('admin.doctors.index') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                    ← Back to Doctors
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden p-6">

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div
                        class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded border border-red-400 dark:border-red-600">
                        <h3 class="font-semibold mb-2">Validation Errors:</h3>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor Name</label>
                        <input type="text" name="name" value="{{ old('name', $doctor->user->name) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $doctor->user->email) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password
                            (Optional)</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500"
                            placeholder="Leave empty to keep current password">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $doctor->user->phone) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Specialty -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Specialty</label>
                        <select name="specialty_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                            @foreach ($specialties as $specialty)
                                <option value="{{ $specialty->id }}"
                                    {{ $doctor->specialty_id == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Years -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Years of
                            Experience</label>
                        <input type="number" name="years_experience"
                            value="{{ old('years_experience', $doctor->years_experience) }}" min="0"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                        <select name="gender"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                            <option value="male" {{ $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Consultation Fee -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Consultation
                            Fee</label>
                        <input type="number" step="0.01" name="consultation_fee"
                            value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                        <textarea name="bio" rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">{{ old('bio', $doctor->bio) }}</textarea>
                    </div>

                    <!-- Education -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Education</label>
                        <textarea name="education" rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">{{ old('education', $doctor->education) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border rounded-lg px-3 py-2">
                            <option value="active" {{ $doctor->user->status == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="suspended" {{ $doctor->user->status == 'suspended' ? 'selected' : '' }}>
                                Suspended</option>
                        </select>
                    </div>

                    <!-- Online Availability -->
                    <div class="md:col-span-2">
                        <input type="hidden" name="available_for_online" value="0">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="available_for_online" value="1"
                                class="rounded border-gray-300 dark:border-gray-600"
                                {{ $doctor->available_for_online ? 'checked' : '' }}>
                            <span>Available for Online Consultation</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="md:col-span-2 mt-4">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                            Update Doctor
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
