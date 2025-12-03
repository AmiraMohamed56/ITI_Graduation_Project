@extends('layouts.admin')
@section('title', 'Trashed Doctors')
@section('content')

<div x-data="{ restoreModal: false, actionId: null }" class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Trashed Doctors</h1>
                <span class="bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $doctors->total() }}
                </span>
            </div>

            <!-- Sort -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span>⇅ Sort By :</span>
                <select onchange="window.location.href='?sort='+this.value"
                        class="border-none bg-transparent cursor-pointer text-gray-900 dark:text-gray-100">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.doctors.index') }}"
                   class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded hover:bg-gray-700 dark:hover:bg-gray-600 text-sm font-medium">
                   ← Back to Doctors
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Doctor Name</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Specialty</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Experience</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Gender</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Consultation Fee</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($doctors as $doctor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $doctor->id }}</td>

                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ asset('images/doctor.jpg') }}" alt="Doctor"
                                     class="w-10 h-10 rounded-full object-cover">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $doctor->user->name }}</span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-xs rounded-lg">
                                    {{ $doctor->specialty->name }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $doctor->years_experience }} Years</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 capitalize">{{ $doctor->gender }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 font-semibold">{{ $doctor->consultation_fee }} EGP</td>

                            <td class="px-6 py-4 flex space-x-2">
                                <button @click="restoreModal=true; actionId={{ $doctor->id }}"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <span class="material-icons text-base">restore</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                No trashed doctors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $doctors->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Restore Modal -->
    <div x-show="restoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-96 shadow-xl border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Restore Doctor?</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">This action will restore the doctor.</p>

            <div class="flex justify-end space-x-3">
                <button @click="restoreModal=false"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                </button>

                <form x-ref="restoreForm" :action="`/doctors/${actionId}/restore`" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                        Yes, Restore
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
