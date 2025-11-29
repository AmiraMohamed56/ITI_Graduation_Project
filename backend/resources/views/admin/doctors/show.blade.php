{{-- @extends('admin.layouts.app') --}}
{{-- @section('content') --}}
<div class="p-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Doctor Details</h1>
    <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl text-gray-700 text-sm font-medium">← Back</a>
  </div>

  <div class="bg-white shadow-lg rounded-2xl p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- Left Section -->
      <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-800">{{ $doctor->user->name }}</h2>
        <p class="text-gray-600"><strong>Email:</strong> {{ $doctor->user->email }}</p>
        <p class="text-gray-600"><strong>Specialty:</strong> {{ $doctor->specialty->name }}</p>
        <p class="text-gray-600"><strong>Gender:</strong> {{ ucfirst($doctor->gender) }}</p>
        <p class="text-gray-600"><strong>Experience:</strong> {{ $doctor->years_experience }} years</p>
        <p class="text-gray-600"><strong>Consultation Fee:</strong> ${{ number_format($doctor->consultation_fee, 2) }}</p>
        <p class="text-gray-600"><strong>Online Consultations:</strong> {{ $doctor->available_for_online ? 'Available' : 'Not Available' }}</p>
      </div>

      <!-- Right Section -->
      <div class="space-y-4">
        <p class="text-gray-700"><strong>Bio:</strong></p>
        <p class="text-gray-600">{{ $doctor->bio ?? 'No bio provided.' }}</p>

        <p class="text-gray-700"><strong>Education:</strong></p>
        <p class="text-gray-600">{{ $doctor->education ?? 'No education info.' }}</p>
      </div>

    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex space-x-4">
      <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md">Edit</a>

      <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-md">Delete</button>
      </form>
    </div>
  </div>
</div>
{{-- @endsection --}}