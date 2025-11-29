@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Schedule')

@section('content')

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Schedule</h1>
                    <p class="text-gray-600 mt-1">Set your availability and working hours</p>
                </div>
                <a href="#" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <!-- Form -->
        <form id="scheduleForm" class="bg-white rounded-lg shadow">
            <div class="p-6">
                <!-- Doctor Information Section -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Doctor Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Doctor Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" placeholder="Dr. John Smith"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Department/Specialty <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="">Select department</option>
                                <option value="general">General Medicine</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="dermatology">Dermatology</option>
                                <option value="ent">ENT Surgery</option>
                                <option value="orthopedics">Orthopaedics</option>
                                <option value="pediatrics">Paediatrics</option>
                                <option value="radiology">Radiology</option>
                                <option value="dental">Dental Surgery</option>
                                <option value="anaesthesiology">Anaesthesiology</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Schedule Period -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Schedule Period
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required />
                        </div>
                    </div>
                </div>

                <!-- Weekly Schedule -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Weekly Schedule
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">Select the days and times you'll be available</p>

                    <div class="space-y-4">
                        <!-- Monday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'monday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Monday</span>
                                </label>
                            </div>
                            <div id="monday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="17:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="10" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Tuesday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'tuesday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Tuesday</span>
                                </label>
                            </div>
                            <div id="tuesday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="17:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="10" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Wednesday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'wednesday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Wednesday</span>
                                </label>
                            </div>
                            <div id="wednesday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="17:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="10" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Thursday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'thursday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Thursday</span>
                                </label>
                            </div>
                            <div id="thursday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="17:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="10" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Friday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'friday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Friday</span>
                                </label>
                            </div>
                            <div id="friday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="17:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="10" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Saturday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'saturday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Saturday</span>
                                </label>
                            </div>
                            <div id="saturday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="13:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="5" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Sunday -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleDay(this, 'sunday')">
                                    <span class="ml-3 text-sm font-semibold text-gray-900">Sunday</span>
                                </label>
                            </div>
                            <div id="sunday" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Start Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="09:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">End Time</label>
                                    <input type="time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="13:00">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Max Appointments</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="5" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Settings -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Appointment Settings
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment Duration (minutes) <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="15">15 minutes</option>
                                <option value="30" selected>30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">60 minutes</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Consultation Type <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="both" selected>Both In-Person & Online</option>
                                <option value="in-person">In-Person Only</option>
                                <option value="online">Online Only</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Location/Room Number
                            </label>
                            <input type="text" placeholder="e.g., Room 205, Building A"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Break Time (minutes)
                            </label>
                            <input type="number" placeholder="e.g., 60 for lunch break"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                min="0" />
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Additional Information
                    </h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes/Special Instructions
                        </label>
                        <textarea rows="4" placeholder="Any special instructions or notes about your schedule..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="copySchedule"
                        class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <label for="copySchedule" class="text-sm text-gray-700">Copy this schedule to next week</label>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="fas fa-calendar-check"></i>
                        Create Schedule
                    </button>
                </div>
            </div>
        </form>

    <script>
        function toggleDay(checkbox, dayId) {
            const daySection = document.getElementById(dayId);
            if (checkbox.checked) {
                daySection.classList.remove('hidden');
            } else {
                daySection.classList.add('hidden');
            }
        }

        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get all checked days
            const checkedDays = [];
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            checkboxes.forEach(cb => {
                if (cb.id !== 'copySchedule') {
                    checkedDays.push(cb.nextElementSibling.textContent);
                }
            });

            if (checkedDays.length === 0) {
                alert('Please select at least one day for your schedule!');
                return;
            }

            alert('Schedule created successfully for: ' + checkedDays.join(', '));
            // Add your form submission logic here
        });
    </script>
@endsection
