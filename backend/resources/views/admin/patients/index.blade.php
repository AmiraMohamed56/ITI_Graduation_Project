@extends('admin.layout')

@section('title', 'All Patients')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-2xl font-bold">Patients</h2>
    <a href="{{ route('admin.patients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Patient</a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-3">#</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Phone</th>
            <th class="p-3">Blood Type</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($patients as $patient)
        <tr class="border-b">
            <td class="p-3">{{ $patient->id }}</td>
            <td class="p-3">{{ $patient->user->name }}</td>
            <td class="p-3">{{ $patient->user->email }}</td>
            <td class="p-3">{{ $patient->user->phone }}</td>
            <td class="p-3">{{ $patient->blood_type }}</td>
            <td class="p-3 flex gap-2">
                <a href="{{ route('admin.patients.show', $patient->id) }}"
                    class="bg-green-500 text-white px-3 py-1 rounded">
                    View
                </a>

                <a href="{{ route('admin.patients.edit', $patient->id) }}"
                    class="bg-yellow-500 text-white px-3 py-1 rounded">
                    Edit
                </a>

                <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $patients->links() }}
</div>

@endsection
