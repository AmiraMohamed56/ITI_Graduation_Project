@extends('Doctors_Dashboard.layouts.app')

@section('title', 'Schedules')

@section('content')
  <!-- Header -->
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Schedules</h1>
    <div class="flex items-center text-sm text-gray-500 mt-2">
      <span>Home</span>
      <span class="mx-2">»</span>
      <span>Schedules</span>
    </div>
  </div>
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-semibold text-gray-900">Total Schedules</h2>
                        <span class="bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded">950</span>
                    </div>

                    <a href="#"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus"></i>
                        Create Schedule
                    </a>
                </div>

                <!-- Search and Sort Row -->
                <div class="flex items-center gap-3">
                    <!-- Search Bar -->
                    <div class="relative flex-1">
                        <input
                            type="text"
                            placeholder="Search scedules..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Sort By -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Sort By :</span>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Newest</option>
                            <option>Oldest</option>
                            <option>Patient Name</option>
                            <option>Date</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Patient ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Patient Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Doctor Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Appointment Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#PT0025</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=James+Carter&background=4F46E5&color=fff"
                                         alt="James Carter"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">James Carter</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Andrew+Clark&background=059669&color=fff"
                                         alt="Dr. Andrew Clark"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-900">Dr. Andrew Clark</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">Anaesthesiology</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">17 Jun 2025, 09:00 AM to 10:00 AM</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    Upcoming
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteAppointment('PT0025')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#PT0024</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=DC2626&color=fff"
                                         alt="Emily Davis"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">Emily Davis</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Katherine+Brooks&background=9333EA&color=fff"
                                         alt="Dr. Katherine Brooks"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-900">Dr. Katherine Brooks</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">Dental Surgery</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">10 Jun 2025, 10:30 AM to 11:30 AM</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    Upcoming
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteAppointment('PT0024')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#PT0023</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Michael+Johnson&background=0891B2&color=fff"
                                         alt="Michael Johnson"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">Michael Johnson</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Benjamin+Harris&background=EA580C&color=fff"
                                         alt="Dr. Benjamin Harris"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-900">Dr. Benjamin Harris</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">Dermatology</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">22 May 2025, 01:15 PM to 02:15 PM</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    Upcoming
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteAppointment('PT0023')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#PT0022</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Olivia+Miller&background=D946EF&color=fff"
                                         alt="Olivia Miller"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">Olivia Miller</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Laura+Mitchell&background=7C3AED&color=fff"
                                         alt="Dr. Laura Mitchell"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-900">Dr. Laura Mitchell</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">ENT Surgery</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">15 May 2025, 11:30 AM to 12:30 PM</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    In Progress
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteAppointment('PT0022')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#PT0021</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=David+Smith&background=0284C7&color=fff"
                                         alt="David Smith"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">David Smith</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Christopher+Lewis&background=0891B2&color=fff"
                                         alt="Dr. Christopher Lewis"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-sm text-gray-900">Dr. Christopher Lewis</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">General Medicine</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">30 Apr 2025, 12:20 PM to 01:20 PM</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="#"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteAppointment('PT0021')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Showing 1 to 5 of 950 entries
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Previous
                    </button>
                    <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">3</button>
                    <span class="text-gray-600">...</span>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">190</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">
                        Next
                    </button>
                </div>
            </div>
        </div>

    <script>
        function deleteAppointment(id) {
            if (confirm('Are you sure you want to delete appointment #' + id + '?')) {
                // Add your delete logic here
                alert('Appointment #' + id + ' deleted!');
                // In a real application, you would make an AJAX call to delete the appointment
            }
        }
    </script>
      @endsection
