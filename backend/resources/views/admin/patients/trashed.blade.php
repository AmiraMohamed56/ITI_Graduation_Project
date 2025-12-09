@extends('layouts.admin')
@section('title', 'Trashed Patients')
@section('content')

<div x-data="{ restoreModal: false, actionId: null }" class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Trashed Patients</h1>
                <span class="bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $patients->total() }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.patients.index') }}"
                    class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded hover:bg-gray-700 dark:hover:bg-gray-600 text-sm font-medium">
                    ← Back to Patients
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-100 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-100">#</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-100">Patient Name</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-100">Email</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-100">Phone</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-100">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $patient->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $patient->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patient->user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patient->user->phone }}</td>

                        <td class="px-6 py-4 flex space-x-2">
                            <button @click="restoreModal=true; actionId={{ $patient->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-icons text-base">restore</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No trashed patients found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $patients->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Restore Modal -->
    <div x-show="restoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-96 shadow-xl">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Restore Patient?</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">This action will restore the patient.</p>

            <div class="flex justify-end space-x-3">
                <button @click="restoreModal=false"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>

                <form x-ref="restoreForm" :action="`{{url('admin/patients')}}/${actionId}/restore`" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded hover:bg-green-700 dark:hover:bg-green-600">
                        Yes, Restore
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>


@endsection
