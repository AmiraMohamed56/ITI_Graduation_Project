{{-- @extends('admin.layouts.app') --}}
{{-- @section('content') --}}
<div class="p-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Doctor</h1>
    <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl text-gray-700 text-sm font-medium">← Back</a>
  </div>

  <div class="bg-white shadow-lg rounded-2xl p-8">
    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <!-- Doctor Name & Email -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block font-semibold mb-1">Doctor Name</label>
          <input type="text" name="name" value="{{ old('name', $doctor->user->name) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
          <label class="block font-semibold mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email', $doctor->user->email) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200" required>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block font-semibold mb-1">Password (Optional)</label>
        <input type="password" name="password" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200" placeholder="Leave empty to keep current password">
        <input type="password" name="password_confirmation" class="w-full mt-2 px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200" placeholder="Confirm password">
      </div>

      <!-- Specialty + Gender -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block font-semibold mb-1">Specialty</label>
          <select name="specialty_id" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200" required>
            @foreach ($specialties as $specialty)
              <option value="{{ $specialty->id }}" {{ $doctor->specialty_id == $specialty->id ? 'selected' : '' }}>
                {{ $specialty->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block font-semibold mb-1">Gender</label>
          <select name="gender" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200">
            <option value="male" {{ $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
          </select>
        </div>
      </div>

      <!-- Bio -->
      <div>
        <label class="block font-semibold mb-1">Bio</label>
        <textarea name="bio" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200">{{ old('bio', $doctor->bio) }}</textarea>
      </div>

      <!-- Education + Experience -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block font-semibold mb-1">Education</label>
          <input type="text" name="education" value="{{ old('education', $doctor->education) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200">
        </div>

        <div>
          <label class="block font-semibold mb-1">Years of Experience</label>
          <input type="number" name="years_experience" value="{{ old('years_experience', $doctor->years_experience) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200">
        </div>
      </div>

      <!-- Fee + Online Availability -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block font-semibold mb-1">Consultation Fee</label>
          <input type="number" step="0.01" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex items-center space-x-3 mt-6">
          <input type="checkbox" name="available_for_online" value="1" {{ $doctor->available_for_online ? 'checked' : '' }} class="w-5 h-5">
          <label class="font-semibold">Available for Online Consultations</label>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md">
          Update Doctor
        </button>
      </div>
    </form>
  </div>
</div>
{{-- @endsection --}}