@extends('layouts.admin')

@section('title', 'View Specialty')

@section('breadcrumb')
    <a href="{{ route('admin.specialties.index') }}" class="hover:underline">Specialties</a> /
    <span class="text-gray-500">{{ $specialty->name }}</span>
@endsection

@section('content')

<x-admin.card>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">Specialty Details</h2>
        <a href="{{ route('admin.specialties.edit', $specialty) }}">
            <x-admin.button type="secondary">Edit Specialty</x-admin.button>
        </a>
    </div>

    <div class="overflow-x-auto">
        <x-admin.table>
            <tbody>
                <tr class="">
                    <td class="px-4 py-3 font-semibold">ID</td>
                    <td class="px-4 py-3">{{ $specialty->id }}</td>
                </tr>
                <tr class="">
                    <td class="px-4 py-3 font-semibold">Name</td>
                    <td class="px-4 py-3">{{ $specialty->name }}</td>
                </tr>
                <tr class="">
                    <td class="px-4 py-3 font-semibold">Created At</td>
                    <td class="px-4 py-3">{{ $specialty->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                <tr class="">
                    <td class="px-4 py-3 font-semibold">Updated At</td>
                    <td class="px-4 py-3">{{ $specialty->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
            </tbody>
        </x-admin.table>
    </div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('admin.specialties.index') }}">
            <x-admin.button type="secondary">Back to List</x-admin.button>
        </a>

        <x-admin.button type="danger" id="delete-button">Delete</x-admin.button>

        <!-- Confirmation Modal (Initially Hidden) -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6 w-96">
                <h2 class="text-xl mb-4">Are you sure you want to delete this payment?</h2>
                <div class="flex justify-end gap-4">
                    <!-- Cancel Button -->
                    <x-admin.button id="cancel-button" type="secondary" size="sm">Cancel</x-admin.button>

                    {{-- delete form --}}
                    <form id="delete-form" action="{{ route('admin.specialties.destroy', $specialty) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-admin.button type="danger" size="sm">Cofirm</x-admin.button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin.card>

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
