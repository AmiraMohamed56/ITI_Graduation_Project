@extends('layouts.admin')

@section('title', 'All Patients')

@section('content')

<div x-data="{ deleteModal: false, deleteId: null }" class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Total Patients</h1>
                <span class="bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $patients->total() }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span>⇅ Sort By :</span>
                <select onchange="window.location.href='?sort='+this.value" class="border-none bg-transparent cursor-pointer text-gray-600 dark:text-gray-300">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.patients.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                    Add Patient
                </a>
                <a href="{{ route('admin.patients.trashed') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                    Trashed
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded">
            {{ session('success') }}
        </div>
        @endif
        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" action="{{ route('admin.patients.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                <!-- Name Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Search by name..."
                        value="{{ request('name') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" />
                </div>

                <!-- Email Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="Search by email..."
                        value="{{ request('email') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" />
                </div>

                <!-- Phone Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="Search by phone..."
                        value="{{ request('phone') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" />
                </div>

                <!-- Blood Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Blood Type</label>
                    <select
                        name="blood_type"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">All Types</option>
                        <option value="A+" {{ request('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ request('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ request('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ request('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ request('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ request('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ request('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ request('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-filter text-xs"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.patients.index') }}"
                        class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-times text-xs"></i>
                        Clear
                    </a>
                </div>

            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">ID</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Patient Name</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Phone</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Blood Type</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $patient->id }}</td>

                        <td class="px-6 py-4 flex items-center gap-3">
                            @php
                            $image = $patient->user->profile_pic ?? null;
                            $name = $patient->user->name;
                            $initial = strtoupper(substr($name,0,1));
                            @endphp

                            @if ($image)
                            <img src="{{ asset('uploads/profile/' . $image) }}"
                                class="w-10 h-10 rounded-full object-cover">
                            @else
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ $initial }}
                            </div>
                            @endif

                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $patient->user->name }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patient->user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patient->user->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patient->blood_type }}</td>

                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('admin.patients.show', $patient->id) }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">visibility</span>
                            </a>
                            <a href="{{ route('admin.patients.edit', $patient->id) }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">edit</span>
                            </a>
                            <button @click="deleteModal=true; deleteId={{ $patient->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No patients found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $patients->links() }}
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-96 shadow-xl">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Are you sure?</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">This action will delete the patient.</p>

            <div class="flex justify-end space-x-3">
                <button @click="deleteModal=false"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-600">Cancel</button>

                <form :action="`patients/${deleteId}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Dark Mode Script -->

</div>

@endsection
