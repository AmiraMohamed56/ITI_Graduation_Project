@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Schedule')

@section('content')
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Doctor Schedule</h1>
                        <p class="text-gray-600 mt-1">View and manage availability schedule</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            onclick="window.location.href='#'"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit"></i>
                            Edit Schedule
                        </button>
                        <button
                            onclick="deleteSchedule()"
                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-trash"></i>
                            Delete Schedule
                        </button>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-times text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Doctor Info Card -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <img
                                src="https://ui-avatars.com/api/?name=Andrew+Clark&background=4F46E5&color=fff&size=100"
                                alt="Doctor"
                                class="w-20 h-20 rounded-full border-4 border-blue-100"
                            />
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Dr. Andrew Clark</h2>
                                <p class="text-gray-600 mt-1">Specialist in Anaesthesiology</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        Active Schedule
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Room 205, Building A
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Schedule Period</p>
                            <p class="text-lg font-semibold text-gray-900">Jan 15 - Jun 30, 2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Working Days</p>
                            <p class="text-2xl font-bold text-gray-900">5 days/week</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Daily Appointments</p>
                            <p class="text-2xl font-bold text-gray-900">10 max</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Consultation Time</p>
                            <p class="text-2xl font-bold text-gray-900">30 min</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Break Time</p>
                            <p class="text-2xl font-bold text-gray-900">60 min</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-coffee text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Schedule -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Weekly Schedule</h3>
                    <p class="text-sm text-gray-600 mt-1">Doctor's availability throughout the week</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Monday -->
                        <div id="day-monday" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors" data-active="true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-blue-600 font-semibold">MON</p>
                                            <p class="text-lg font-bold text-blue-600">15</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Monday</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            09:00 AM - 05:00 PM
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Max Appointments</p>
                                        <p class="text-xl font-bold text-gray-900">10</p>
                                    </div>
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Available
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('monday')"
                                            class="toggle-btn px-3 py-2 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Deactivate
                                        </button>
                                        <button
                                            onclick="editDay('monday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tuesday -->
                        <div id="day-tuesday" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors" data-active="true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-blue-600 font-semibold">TUE</p>
                                            <p class="text-lg font-bold text-blue-600">16</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Tuesday</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            09:00 AM - 05:00 PM
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Max Appointments</p>
                                        <p class="text-xl font-bold text-gray-900">10</p>
                                    </div>
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Available
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('tuesday')"
                                            class="toggle-btn px-3 py-2 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Deactivate
                                        </button>
                                        <button
                                            onclick="editDay('tuesday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wednesday -->
                        <div id="day-wednesday" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors" data-active="true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-blue-600 font-semibold">WED</p>
                                            <p class="text-lg font-bold text-blue-600">17</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Wednesday</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            09:00 AM - 05:00 PM
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Max Appointments</p>
                                        <p class="text-xl font-bold text-gray-900">10</p>
                                    </div>
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Available
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('wednesday')"
                                            class="toggle-btn px-3 py-2 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Deactivate
                                        </button>
                                        <button
                                            onclick="editDay('wednesday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thursday -->
                        <div id="day-thursday" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors" data-active="true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-blue-600 font-semibold">THU</p>
                                            <p class="text-lg font-bold text-blue-600">18</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Thursday</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            09:00 AM - 05:00 PM
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Max Appointments</p>
                                        <p class="text-xl font-bold text-gray-900">10</p>
                                    </div>
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Available
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('thursday')"
                                            class="toggle-btn px-3 py-2 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Deactivate
                                        </button>
                                        <button
                                            onclick="editDay('thursday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Friday -->
                        <div id="day-friday" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition-colors" data-active="true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-blue-600 font-semibold">FRI</p>
                                            <p class="text-lg font-bold text-blue-600">19</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">Friday</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            09:00 AM - 05:00 PM
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Max Appointments</p>
                                        <p class="text-xl font-bold text-gray-900">10</p>
                                    </div>
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Available
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('friday')"
                                            class="toggle-btn px-3 py-2 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Deactivate
                                        </button>
                                        <button
                                            onclick="editDay('friday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Saturday (Off) -->
                        <div id="day-saturday" class="border border-gray-200 rounded-lg p-5 bg-gray-50 opacity-60" data-active="false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500 font-semibold">SAT</p>
                                            <p class="text-lg font-bold text-gray-500">20</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-500">Saturday</h4>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-ban mr-1"></i>
                                            Not Available
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Day Off
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('saturday')"
                                            class="toggle-btn px-3 py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-off mr-1"></i>
                                            Activate
                                        </button>
                                        <button
                                            onclick="editDay('saturday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sunday (Off) -->
                        <div id="day-sunday" class="border border-gray-200 rounded-lg p-5 bg-gray-50 opacity-60" data-active="false">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500 font-semibold">SUN</p>
                                            <p class="text-lg font-bold text-gray-500">21</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-500">Sunday</h4>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-ban mr-1"></i>
                                            Not Available
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="status-badge inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Day Off
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleDayStatus('sunday')"
                                            class="toggle-btn px-3 py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-toggle-off mr-1"></i>
                                            Activate
                                        </button>
                                        <button
                                            onclick="editDay('sunday')"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Settings -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Appointment Settings</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Appointment Duration</p>
                                <p class="text-lg font-semibold text-gray-900">30 minutes</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-video text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Consultation Type</p>
                                <p class="text-lg font-semibold text-gray-900">Both In-Person & Online</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Location</p>
                                <p class="text-lg font-semibold text-gray-900">Room 205, Building A</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Notes -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Special Instructions</h3>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-gray-700">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Emergency cases will be accommodated outside regular hours. Please contact the reception desk for urgent appointments. Lunch break is from 1:00 PM to 2:00 PM daily.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="mt-6 flex items-center justify-between bg-white p-4 rounded-lg shadow">
                <a href="#" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Schedules List
                </a>
                <div class="flex items-center gap-3">
                    <button
                        onclick="window.print()"
                        class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-print"></i>
                        Print Schedule
                    </button>
                    <button
                        class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-share-alt"></i>
                        Share Schedule
                    </button>
                </div>
            </div>

    <script>
        function deleteSchedule() {
            if (confirm('Are you sure you want to delete this schedule? This action cannot be undone.')) {
                alert('Schedule deleted successfully!');
                // Add your delete logic here
                // window.location.href = 'schedules-list.html';
            }
        }
    </script>
      @endsection
    

