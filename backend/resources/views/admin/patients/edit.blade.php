@extends('layouts.admin')
@section('title', 'Edit Patient')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 py-10 px-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Patient</h1>
            <a href="{{ route('admin.patients.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                ← Back to Patients
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden p-6">

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded border border-red-400 dark:border-red-600">
                <h3 class="font-semibold mb-2">Validation Errors:</h3>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $patient->user->name) }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('name')
                    <p class="text-sm text-red-600 dark:text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $patient->user->email) }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('email')
                    <p class="text-sm text-red-600 dark:text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $patient->user->phone) }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('phone')
                    <p class="text-sm text-red-600 dark:text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Blood Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Blood Type
                    </label>

                    <select name="blood_type"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700
               text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">

                        <option value="">Select Blood Type</option>

                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                        <option value="{{ $type }}"
                            {{ old('blood_type', $patient->blood_type) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach

                    </select>
                </div>


                <!-- Chronic Diseases -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chronic Diseases</label>
                    <textarea name="chronic_diseases" rows="3"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-blue-500">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                </div>

                <!-- Submit -->
                <div class="md:col-span-2 mt-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Update Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
