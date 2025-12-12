@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Medical Records')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medical Records</h1>
    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-2">
        <span>Home</span>
        <span class="mx-2">»</span>
        <span>Medical Records</span>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Total Medical Records</h2>
                <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">
                    {{ $medicalRecords->total() }}
                </span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('medical_records.create') }}"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus"></i>
                    Create Medical Record
                </a>
                {{-- <a href="{{ route('medical_records.create') }}"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Deleted Records
                </a> --}}
            </div>
        </div>

        <!-- Search Row -->
        <div class="flex items-center gap-3">
            <!-- Search Bar -->
            <form method="GET" action="{{  route('medical_records.index') }}" class="relative flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by patient name, symptoms, diagnosis..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                <button type="submit" class="hidden">Search</button>
            </form>

            @if(request('search'))
            <a href="{{  route('medical_records.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-colors">
                Clear
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Patient Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Symptoms</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Diagnosis</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($medicalRecords as $record)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">


                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->patient->user->name }}</span>
                        </div>
                    </td>


                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            {{ Str::limit($record->symptoms ?? 'N/A', 30) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            {{ Str::limit($record->diagnosis ?? 'N/A', 30) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $record->created_at->format('d M Y, h:i A') }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('medical_records.show', $record->id)  }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                                title="View Details">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('medical_records.edit', $record->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition-colors"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>

{{-- ======================================================================================== --}}
                            <button
                                id="delete-button"
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition-colors"
                                title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <!-- Confirmation Modal (Initially Hidden) -->
                            <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
                                <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
                                    <h2 class="text-xl mb-4">Are you sure you want to delete this payment?</h2>
                                    <div class="flex justify-end gap-4">
                                        <!-- Cancel Button -->
                                        <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                                        {{-- delete form --}}
                                        <form id="delete-form" action="{{ route('medical_records.destroy', $record->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                                        </form>
                                    </div>
                                </div>
                            </div>
{{-- ============================================================================================ --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-lg font-medium dark:text-white">No medical records found</p>
                            @if(request('search'))
                            <p class="text-sm mt-1">Try adjusting your search terms</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $medicalRecords->firstItem() ?? 0 }} to {{ $medicalRecords->lastItem() ?? 0 }} of {{ $medicalRecords->total() }} entries
        </div>

        <div class="flex items-center gap-2">
            {{ $medicalRecords->links() }}
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('
        success ') }}');
</script>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButton = document.getElementById("delete-button");
        const cancelButton = document.getElementById("cancel-button");
        const modal = document.getElementById("modal");
        const deleteForm = document.getElementById("delete-form");

        // Show Modal
        deleteButton.addEventListener("click", function() {
            modal.classList.remove("hidden");
        });

        // Hide Modal (Cancel)
        cancelButton.addEventListener("click", function() {
            modal.classList.add("hidden");
        });

        // submit the form after confirmation
        // deleteForm.addEventListener("submit", function(event) {
        //     // You could add a confirmation prompt if you want a final check
        //     if (!confirm("Are you sure you want to delete this payment?")) {
        //         event.preventDefault();  // Prevent the form from being submitted if the user cancels
        //     }
        // });
    });
</script>
@endsection
