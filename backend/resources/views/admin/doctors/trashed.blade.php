{{-- @extends('admin.layouts.app') --}}
{{-- @section('content') --}}
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Trashed Doctors</h1>
            <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700">
                ← Back to Doctors
            </a>
        </div>

        <!-- Filters -->
        <div class="flex items-center justify-between bg-white p-4 rounded-xl shadow mb-6">
            <div>
                <span class="text-gray-600 font-medium">Showing {{ $doctors->count() }} trashed doctors</span>
            </div>
        </div>

        <!-- Doctors Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Specialty</th>
                        <th class="px-6 py-3">Experience</th>
                        <th class="px-6 py-3">Gender</th>
                        <th class="px-6 py-3">Consultation Fee</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse($doctors as $doctor)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $doctor->id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $doctor->user->name }}</td>
                            <td class="px-6 py-4">{{ $doctor->specialty->name }}</td>
                            <td class="px-6 py-4">{{ $doctor->years_experience }}</td>
                            <td class="px-6 py-4 capitalize">{{ $doctor->gender }}</td>
                            <td class="px-6 py-4">{{ $doctor->consultation_fee }} EGP</td>
                            <td class="px-6 py-4 flex space-x-3">
                                <!-- Restore Button -->
                                <form action="{{ route('admin.doctors.restore', $doctor->id) }}" method="POST" onsubmit="return confirm('Restore this doctor?');">
                                    @csrf
                                    <button class="text-green-600 hover:underline">Restore</button>
                                </form>

                                <!-- Permanent Delete (Optional) -->
                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Delete this doctor permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete Permanently</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No trashed doctors found.</td>
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
</div>
{{-- @endsection --}}
