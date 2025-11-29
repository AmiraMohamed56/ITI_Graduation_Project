@extends('Doctors_Dashboard.layouts.app')

@section('title', 'profile')

@section('content')

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Doctor Profile</h1>
                        <p class="text-gray-600 mt-1">View and manage your professional information</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            id="editBtn"
                            onclick="toggleEditMode()"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit"></i>
                            Edit Profile
                        </button>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-times text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img
                                    id="profileImage"
                                    src="https://ui-avatars.com/api/?name=Andrew+Clark&background=4F46E5&color=fff&size=150"
                                    alt="Doctor"
                                    class="w-32 h-32 rounded-full border-4 border-blue-100 mx-auto"
                                />
                                <button
                                    id="changePhotoBtn"
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white w-10 h-10 rounded-full hover:bg-blue-700 transition-colors hidden">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <h2 id="displayName" class="text-2xl font-bold text-gray-900 mt-4">Dr. Andrew Clark</h2>
                            <p id="displaySpecialty" class="text-gray-600 mt-1">Anaesthesiology Specialist</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Years of Experience</span>
                                    <span id="displayExperience" class="text-sm font-semibold text-gray-900">15 years</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Patients</span>
                                    <span class="text-sm font-semibold text-gray-900">2,450</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Success Rate</span>
                                    <span class="text-sm font-semibold text-green-600">98.5%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Rating</span>
                                    <span class="text-sm font-semibold text-yellow-600">
                                        <i class="fas fa-star"></i> 4.9/5.0
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Working Schedule Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Working Schedule</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Monday - Friday</span>
                                <span class="font-medium text-gray-900">09:00 - 17:00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Saturday</span>
                                <span class="font-medium text-gray-900">09:00 - 13:00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Sunday</span>
                                <span class="font-medium text-red-600">Closed</span>
                            </div>
                        </div>
                        <button class="w-full mt-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            View Full Schedule
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <form id="profileForm">
                        <!-- Personal Information -->
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="fullName"
                                            value="Dr. Andrew Clark"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            value="dr.andrew.clark@dreamsemr.com"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            value="+1 (555) 123-4567"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Date of Birth <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            id="dob"
                                            value="1978-05-15"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Gender <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="gender"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            disabled
                                        >
                                            <option value="male" selected>Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Blood Type
                                        </label>
                                        <select
                                            id="bloodType"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            disabled
                                        >
                                            <option value="">Select blood type</option>
                                            <option value="A+" selected>A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Address
                                        </label>
                                        <textarea
                                            id="address"
                                            rows="2"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        >123 Medical Center Drive, Suite 400, Los Angeles, CA 90001</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Professional Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Medical License Number <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="licenseNumber"
                                            value="MD-2008-45612"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Specialty <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="specialty"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            disabled
                                        >
                                            <option value="">Select specialty</option>
                                            <option value="anaesthesiology" selected>Anaesthesiology</option>
                                            <option value="cardiology">Cardiology</option>
                                            <option value="dermatology">Dermatology</option>
                                            <option value="ent">ENT Surgery</option>
                                            <option value="general">General Medicine</option>
                                            <option value="orthopedics">Orthopaedics</option>
                                            <option value="pediatrics">Paediatrics</option>
                                            <option value="radiology">Radiology</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Years of Experience <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="experience"
                                            value="15"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Department <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="department"
                                            value="Anaesthesiology Department"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Consultation Fee ($)
                                        </label>
                                        <input
                                            type="number"
                                            id="consultationFee"
                                            value="150"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Room Number
                                        </label>
                                        <input
                                            type="text"
                                            id="roomNumber"
                                            value="Room 205, Building A"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Qualifications
                                        </label>
                                        <textarea
                                            id="qualifications"
                                            rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        >MD - Harvard Medical School (2008)
Fellowship in Anaesthesiology - Johns Hopkins Hospital (2012)
Board Certified Anaesthesiologist</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education & Certifications -->
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Education & Certifications</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Doctor of Medicine (MD)</h4>
                                                <p class="text-sm text-gray-600 mt-1">Harvard Medical School</p>
                                                <p class="text-sm text-gray-500 mt-1">2004 - 2008</p>
                                            </div>
                                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Fellowship in Anaesthesiology</h4>
                                                <p class="text-sm text-gray-600 mt-1">Johns Hopkins Hospital</p>
                                                <p class="text-sm text-gray-500 mt-1">2008 - 2012</p>
                                            </div>
                                            <i class="fas fa-certificate text-green-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Board Certification</h4>
                                                <p class="text-sm text-gray-600 mt-1">American Board of Anesthesiology</p>
                                                <p class="text-sm text-gray-500 mt-1">Valid until: 2026</p>
                                            </div>
                                            <i class="fas fa-award text-yellow-600 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-white rounded-lg shadow mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Languages Spoken
                                        </label>
                                        <input
                                            type="text"
                                            id="languages"
                                            value="English, Spanish, French"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Biography
                                        </label>
                                        <textarea
                                            id="biography"
                                            rows="4"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"
                                            readonly
                                        >Dr. Andrew Clark is a highly experienced Anaesthesiologist with over 15 years of practice. He specializes in pain management and critical care anaesthesia. Dr. Clark is committed to providing the highest quality of patient care and has received numerous awards for his contributions to the field of anaesthesiology.</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div id="actionButtons" class="bg-white rounded-lg shadow p-6 hidden">
                            <div class="flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    onclick="cancelEdit()"
                                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            const editBtn = document.getElementById('editBtn');
            const actionButtons = document.getElementById('actionButtons');
            const changePhotoBtn = document.getElementById('changePhotoBtn');

            if (isEditMode) {
                // Enable edit mode
                inputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                    input.classList.remove('bg-gray-50');
                    input.classList.add('bg-white');
                });

                editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel Edit';
                editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                editBtn.classList.add('bg-gray-600', 'hover:bg-gray-700');

                actionButtons.classList.remove('hidden');
                changePhotoBtn.classList.remove('hidden');
            } else {
                // Disable edit mode
                inputs.forEach(input => {
                    input.setAttribute('readonly', 'readonly');
                    if (input.tagName === 'SELECT') {
                        input.setAttribute('disabled', 'disabled');
                    }
                    input.classList.remove('bg-white');
                    input.classList.add('bg-gray-50');
                });

                editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
                editBtn.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');

                actionButtons.classList.add('hidden');
                changePhotoBtn.classList.add('hidden');
            }
        }

        function cancelEdit() {
            toggleEditMode();
            // Optionally reload the form to reset values
            alert('Changes cancelled');
        }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Update display name and specialty
            const fullName = document.getElementById('fullName').value;
            const specialty = document.getElementById('specialty').options[document.getElementById('specialty').selectedIndex].text;
            const experience = document.getElementById('experience').value;

            document.getElementById('displayName').textContent = fullName;
            document.getElementById('displaySpecialty').textContent = specialty + ' Specialist';
            document.getElementById('displayExperience').textContent = experience + ' years';

            // Exit edit mode
            toggleEditMode();

            alert('Profile updated successfully!');
            // Add your save logic here
        });

        document.getElementById('changePhotoBtn').addEventListener('click', function() {
            alert('Change photo functionality - implement file upload here');
            // Implement file upload logic
        });
    </script>

