@extends('Doctors_Dashboard.layouts.app')

@section('title', 'profile')

@section('content')
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                        <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
                    </div>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>
            </div>



            <!-- General Tab -->
            <div id="content-general" class="tab-content">
                <div class="bg-white rounded-lg shadow">

                    <div class="p-6">
                        <form>
                            <!-- Profile Picture -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-4">Profile Picture</label>
                                <div class="flex items-center gap-6">
                                    <img
                                        src="https://ui-avatars.com/api/?name=Andrew+Clark&background=4F46E5&color=fff&size=100"
                                        alt="Profile"
                                        class="w-20 h-20 rounded-full border-4 border-gray-200"
                                    />
                                    <div>
                                        <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            <i class="fas fa-upload mr-2"></i>
                                            Upload New Picture
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" value="Dr. Andrew Clark" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                    <input type="text" value="dr.andrew.clark" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" value="dr.andrew.clark@dreamsemr.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="tel" value="+1 (555) 123-4567" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Experienced Anaesthesiologist with a passion for patient care.</textarea>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-lock text-gray-600"></i>
                                    Change Password
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <div class="relative">
                                            <input type="password" id="newPassword" placeholder="Enter new password" class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <button type="button" onclick="togglePassword('newPassword')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                        <div class="relative">
                                            <input type="password" id="confirmPassword" placeholder="Confirm new password" class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <button type="button" onclick="togglePassword('confirmPassword')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
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
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>






        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));

            // Remove active state from all tabs
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active state to clicked tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');
            activeTab.classList.add('border-blue-600', 'text-blue-600');
        }

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

        // Form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Check if password fields are filled
                const newPassword = document.getElementById('newPassword');
                const confirmPassword = document.getElementById('confirmPassword');

                if (newPassword && newPassword.value) {
                    if (newPassword.value !== confirmPassword.value) {
                        alert('New passwords do not match!');
                        return;
                    }
                    if (newPassword.value.length < 8) {
                        alert('Password must be at least 8 characters long!');
                        return;
                    }
                    alert('Profile and password updated successfully!');
                } else {
                    alert('Settings saved successfully!');
                }
            });
        });
    </script>
@endsection


