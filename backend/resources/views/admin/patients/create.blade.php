@extends('layouts.admin')
@section('title', 'Add New Patient')
@section('breadcrumb')
    <a href="{{ route('admin.patients.index') }} " class="hover:underline">Patients</a> / <span>Create</span>
@endsection
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 py-10 px-6">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Add New Patient</h1>
            <a href="{{ route('admin.patients.index') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                ← Back to Patients
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden p-6">

            {{-- Validation Errors --}}
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

            <form action="{{ route('admin.patients.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                {{-- Blood Type Dropdown --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Blood Type</label>
                    <select name="blood_type"
                        class="w-full border {{ $errors->has('blood_type') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Select Blood Type</option>
                        @foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                    @error('blood_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Chronic Diseases --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Chronic Diseases</label>
                    <textarea name="chronic_diseases" rows="3"
                        class="w-full border {{ $errors->has('chronic_diseases') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('chronic_diseases') }}</textarea>
                    @error('chronic_diseases')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="md:col-span-2 mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Save Patient
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
