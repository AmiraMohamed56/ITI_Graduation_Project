@extends('layouts.admin')

@section('title', 'Specialties')

@section('breadcrumb')
    <a href="{{ route('admin.specialties.index') }}" class="hover:underline">Specialties</a>
@endsection

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold">All Specialties</h1>
                <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $specialties->total() }}
                </span>
            </div>

            <a href="{{ route('admin.specialties.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                Create Specialty
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <x-admin.alert type="success" :message="session('success')" />
        @endif

        @if(session('error'))
            <x-admin.alert type="error" :message="session('error')" />
        @endif

        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" action="{{ route('admin.specialties.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Name Search -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search by Name</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search..."
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                    />
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-search text-xs"></i>
                        Search
                    </button>

                    @if(request('search'))
                    <a
                        href="{{ route('admin.specialties.index') }}"
                        class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-times text-xs"></i>
                        Clear
                    </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 text-center">ID</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 text-center">Name</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($specialties as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 text-center">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $s->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <a href="{{ route('admin.specialties.show', $s) }}"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $s->name ?? '-' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center gap-3">
                                <!-- View -->
                                <a href="{{ route('admin.specialties.show', $s) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <span class="material-icons text-base">visibility</span>
                                </a>
                                <!-- Edit -->
                                <a href="{{ route('admin.specialties.edit', $s) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <span class="material-icons text-base">edit</span>
                                </a>
                                <!-- Delete -->
                                <span id="delete-button" class="material-icons text-base hover:cursor-pointer">delete</span>
                                <!-- Confirmation Modal (Initially Hidden) -->
                                <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
                                        <h2 class="text-xl mb-4">Are you sure you want to delete this payment?</h2>
                                        <div class="flex justify-end gap-4">
                                            <!-- Cancel Button -->
                                            <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                                            {{-- delete form --}}
                                            <form id="delete-form" action="{{ route('admin.specialties.destroy', $s) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- <form action="{{ route('admin.specialties.destroy', $s) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this specialty?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </form> -->

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                No specialties found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $specialties->links() }}
        </div>

    </div>
</div>

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
