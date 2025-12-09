{{-- File: resources/views/Doctors_Dashboard/layouts/partials/sidebar.blade.php --}}
<aside id="sidebar" class="sidebar-expanded fixed left-0 top-0 h-screen bg-gray-900 text-white transition-all duration-300 overflow-y-auto z-50">
    <div class="flex flex-col h-full">

        <!-- Logo Section -->
        <div class="flex items-center justify-between p-4 border-b border-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-teal-500 dark:bg-teal-600 rounded flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white"></i>
                </div>
                <span id="logo-text" class="text-lg font-bold dark:text-white">MediCare</span>
            </div>
            <button id="toggle-btn" class="w-8 h-8 bg-gray-800 dark:bg-gray-700 rounded flex items-center justify-center hover:bg-gray-700 dark:hover:bg-gray-600">
                <i class="fas fa-bars text-sm dark:text-gray-300"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-4">

            <!-- MAIN Section -->
            <div class="mb-6">
                <a href="{{ route('doctor.dashboard') }}"
                   class="flex items-center px-4 py-3 {{ request()->routeIs('doctor.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="menu-text ml-3">Dashboard</span>
                </a>

                <a href="{{ route('appointments.index') }}"
                   class="flex items-center px-4 py-3 {{ request()->routeIs('appointments.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <i class="fas fa-clock w-5"></i>
                    <span class="menu-text ml-3">Appointments</span>
                </a>

                <a href="{{ route('schedules.index') }}"
                   class="flex items-center px-4 py-3 {{ request()->routeIs('schedules.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span class="menu-text ml-3">Schedules</span>
                </a>

                <a href="{{ route('medical_records.index') }}"
                   class="flex items-center px-4 py-3 {{ request()->routeIs('medical_records.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <i class="fas fa-procedures w-5"></i>
                    <span class="menu-text ml-3">Patients</span>
                </a>

                <a href="{{ route('medical_records.index') }}"
                   class="flex items-center px-4 py-3 hover:bg-gray-800 text-gray-300 hover:text-white">
                    <i class="fa-solid fa-star w-5"></i>
                    <span class="menu-text ml-3">Reviews</span>
                </a>

                <!-- Notifications Link with Badge -->
                <a href="{{ route('doctor.notifications.index') }}"
                   class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('doctor.notifications.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-bell w-5"></i>
                        <span class="menu-text ml-3">Notifications</span>
                    </div>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="menu-text bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('profile_setting.index') }}"
                   class="flex items-center px-4 py-3 {{ request()->routeIs('profile_setting.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300 hover:text-white' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span class="menu-text ml-3">Settings</span>
                </a>
            </div>



        </nav>
    </div>
</aside>


