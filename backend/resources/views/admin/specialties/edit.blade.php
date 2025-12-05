@extends('layouts.admin')

@section('title', 'Edit Specialty')

@section('breadcrumb')
    <a href="{{ route('admin.specialties.index') }}" class="hover:underline">Specialties</a> /
    <span class="text-gray-500">Edit</span>
@endsection

@section('content')

<x-admin.card>
    <div class="mb-6">
        <h2 class="text-lg font-semibold">Edit Specialty</h2>
    </div>

    @if ($errors->any())
        <x-admin.alert type="error">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <form action="{{ route('admin.specialties.update', $specialty) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            {{-- <label for="name" class="block text-sm font-medium text-gray-700">Specialty Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $specialty->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 sm:text-sm"
                placeholder="Enter specialty name" required> --}}
            <x-admin.input label="name" type="text" name="name" id="name" value="{{ old('name', $specialty->name) }}" placeholder="Enter specialty name" required />
        </div>

        <div class="flex gap-2">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm inline-flex items-center justify-center font-medium
            rounded-xl transition-all duration-200 select-none
            focus:outline-none focus:ring-2 focus:ring-offset-2
            disabled:opacity-60 disabled:cursor-not-allowed bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400
            focus:ring-gray-400 shadow-sm hover:shadow drop-shadow">
                Cancel
            </button>

            <x-admin.button type="primary">Update Specialty</x-admin.button>
        </div>
    </form>
</x-admin.card>

@endsection
