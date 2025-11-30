@extends('layouts.admin')

@section('title', 'Doctors')

@section('content')

    <div x-data="{ deleteModal: false, deleteId: null }" class="min-h-screen bg-gray-50">

        <div class="max-w-7xl mx-auto py-10 px-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-semibold text-gray-900">Total Doctors</h1>
                    <span class="bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">
                        {{ $doctors->total() }}
                    </span>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>⇅ Sort By :</span>
                    <select onchange="window.location.href='?sort='+this.value"
                        class="border-none bg-transparent cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.doctors.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                        Add Doctor
                    </a>
                    <a href="{{ route('admin.doctors.trashed') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium">
                        Trashed
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto bg-white rounded-xl shadow">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">#</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Doctor Name</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Specialty</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Experience</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Gender</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Consultation Fee</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($doctors as $index => $doctor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-600">#{{ $doctor->id }}</td>

                                <td class="px-6 py-4 flex items-center gap-3">
                                    <img src="{{ asset('images/doctor.jpg') }}" alt="Doctor"
                                        class="w-10 h-10 rounded-full object-cover">

                                    <span class="text-sm font-medium text-gray-900">{{ $doctor->user->name }}</span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg">
                                        {{ $doctor->specialty->name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">{{ $doctor->years_experience }} Years</td>
                                <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $doctor->gender }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $doctor->consultation_fee }}
                                    EGP</td>

                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                        class="text-gray-400 hover:text-gray-600">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                        class="text-gray-400 hover:text-gray-600">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button @click="deleteModal=true; deleteId={{ $doctor->id }}"
                                        class="text-gray-400 hover:text-gray-600">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500">
                                    No doctors found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $doctors->links() }}
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 w-96 shadow-xl">
                <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
                <p class="text-gray-600 mb-6">This action will delete the doctor.</p>

                <div class="flex justify-end space-x-3">
                    <button @click="deleteModal=false"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>

                    <form x-ref="deleteForm" :action="`/doctors/${deleteId}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>


    </div>

@endsection
