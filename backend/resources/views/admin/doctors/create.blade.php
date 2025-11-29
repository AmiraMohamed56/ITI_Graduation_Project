{{-- @extends('admin.layouts.app') --}}

{{-- @section('content') --}}
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Add New Doctor</h1>
        <a href="{{ route('admin.doctors.index') }}" class="text-blue-600 hover:underline">← Back to list</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.doctors.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Doctor Name</label>
                <input type="text" name="name" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                <select name="specialty_id" class="w-full border-gray-300 rounded-lg" required>
                    @foreach ($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience</label>
                <input type="number" name="years_experience" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <select name="gender" class="w-full border-gray-300 rounded-lg" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee</label>
                <input type="number" name="fee" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div class="md:col-span-2 mt-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Save Doctor</button>
            </div>
        </form>
    </div>
</div>
{{-- @endsection --}}

