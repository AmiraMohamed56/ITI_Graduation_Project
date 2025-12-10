@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-xl"></i>
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    {{-- @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif --}}
    @if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif


    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <form action="{{ route('profile_setting.update',$user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Profile Picture</label>
                    <div class="flex items-center gap-6">
                        <img
                            id="profilePreview"
                            src="{{ $user->profile_picture_url }}"
                            alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-full border-4 border-gray-200 object-cover"
                        />
                        <div>
                            <input
                                type="file"
                                name="profile_pic"
                                id="profile_pic"
                                accept="image/jpeg,image/png,image/jpg"
                                class="hidden"
                                onchange="previewImage(this)"
                            >
                            <button
                                type="button"
                                onclick="document.getElementById('profile_pic').click()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors"
                            >
                                <i class="fas fa-upload mr-2"></i>
                                Upload New Picture
                            </button>
                            <p class="text-xs text-gray-500 mt-2">JPG, JPEG or PNG. Max size 2MB</p>
                        </div>
                    </div>
                    @error('profile_pic')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Basic Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="gender"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4">Professional Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Specialty <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="specialty_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select Specialty</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}"
                                        {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialty_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Education <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="education"
                                value="{{ old('education', $doctor->education) }}"
                                placeholder="e.g., MD from Harvard Medical School"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                            @error('education')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Years of Experience <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                name="years_experience"
                                value="{{ old('years_experience', $doctor->years_experience) }}"
                                min="0"
                                max="70"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                            @error('years_experience')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                            <input
                                type="number"
                                name="consultation_fee"
                                value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            @error('consultation_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea
                                name="bio"
                                rows="4"
                                placeholder="Tell patients about yourself..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >{{ old('bio', $doctor->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="available_for_online"
                                    value="1"
                                    {{ old('available_for_online', $doctor->available_for_online) ? 'checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded"
                                >
                                <span class="text-sm font-medium text-gray-700">Available for Online Consultations</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-gray-600"></i>
                        Change Password
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter new password"
                                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm new password"
                                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Leave password fields empty if you don't want to change your password
                        </p>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex items-center justify-end gap-3">
                    <a
                        href="{{ route('dashboard') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
