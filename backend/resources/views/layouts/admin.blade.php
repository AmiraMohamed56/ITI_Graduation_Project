<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Simple tooltip */
        .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #374151;
            /* Tailwind gray-700 */
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            margin-left: 0.5rem;
        }

        .group:hover .tooltip {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="bg-white dark:bg-gray-800 shadow-md border-r transition-all duration-300 flex-shrink-0"
            style="width:16rem">
            <div class="p-6 font-bold text-xl border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <span id="sidebar-title">Admin Panel</span>
                <button id="sidebar-toggle" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                    <span class="material-icons" id="sidebar-toggle-icon">chevron_left</span>
                </button>
            </div>

            <nav class="mt-6">
                <ul class="space-y-1">
                    <x-admin.nav-item icon="dashboard" text="Dashboard" href="{{ route('admin.dashboard') }}" />
                    <x-admin.nav-item icon="group" text="Patients" href="{{ route('admin.patients.index') }}" />
                    <x-admin.nav-item icon="person" text="Doctors" href="{{ route('admin.doctors.index') }}" />
                    <x-admin.nav-item icon="category" text="Specialties" href="{{ route('admin.specialties.index') }}" />
                    <x-admin.nav-item icon="calendar_today" text="Appointments" href="{{ route('admin.appointments.index') }}" />
                    <x-admin.nav-item icon="how_to_reg" text="Visits" href="{{ route('admin.visits.index') }}" />
                    <x-admin.nav-item icon="rate_review" text="Reviews" href="{{ route('admin.reviews.index') }}" />
                    <x-admin.nav-item icon="how_to_reg"  text="Notifications" href="{{ route('admin.notifications.index') }}" />
                    <x-admin.nav-item icon="payment" text="Payments" href="{{ route('admin.payments.index') }}" />
                    <x-admin.nav-item icon="contacts" text="contacts" href="{{ route('admin.contacts.index') }}" />
                    <x-admin.nav-item icon="receipt_long" text="Logs" href="{{ route('admin.logs.index') }}" />
                    <x-admin.nav-item icon="settings" text="Profile" href="{{ route('admin.settings.edit') }}" />
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <nav class="text-gray-500 dark:text-gray-400 text-sm">
                    @yield('breadcrumb')
                </nav>
                <div class="pr-6 flex items-center justify-end space-x-6">
                    <button id="theme-toggle" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="material-icons">dark_mode</span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="relative p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                            <span class="material-icons">notifications</span>
                            @if(Auth::user()->unreadNotifications()->count() > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ Auth::user()->unreadNotifications()->count() > 9 ? '9+' : Auth::user()->unreadNotifications()->count() }}
                            </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                            style="display: none;">

                            <!-- Header -->
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        <span class="material-icons text-base align-middle mr-1">notifications</span>
                                        Notifications
                                    </h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ Auth::user()->unreadNotifications()->count() }} new
                                    </span>
                                </div>
                            </div>

                            <!-- Notifications List -->
                            <div class="max-h-96 overflow-y-auto">
                                @php
                                $recentNotifications = Auth::user()->notifications()->take(5)->get();
                                @endphp

                                @forelse($recentNotifications as $notification)
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} transition-colors">
                                    <div class="flex items-start gap-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ getAdminNotificationColorClass($notification->type) }}">
                                            <span class="material-icons text-lg">
                                                {{ getAdminNotificationIcon($notification->type) }}
                                            </span>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-300 truncate mt-1">
                                                {{ Str::limit($notification->data['message'] ?? '', 60) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                                <span class="material-icons text-xs mr-1">schedule</span>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        <!-- Unread Indicator -->
                                        @if(is_null($notification->read_at))
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        @endif
                                    </div>
                                </a>
                                @empty
                                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <span class="material-icons text-4xl mb-2 text-gray-300 dark:text-gray-600">notifications_off</span>
                                    <p class="text-sm">No notifications</p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold transition-colors">
                                    View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.settings.edit') }}">
                        <img src="{{ asset('storage/profile_pics/' . Auth::user()->profile_pic) }}" class="w-10 h-10 object-cover rounded-full" alt="Admin">
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-admin.button type="danger" size="sm">
                            Log Out
                        </x-admin.button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const toggleIcon = document.getElementById('sidebar-toggle-icon');
        const sidebarTitle = document.getElementById('sidebar-title');
        const navTexts = document.querySelectorAll('#sidebar nav ul li a span.ml-3');
        const navLinks = document.querySelectorAll('#sidebar nav ul li a');
        let open = true;

        toggleBtn.addEventListener('click', () => {
            open = !open;
            if (open) {
                sidebar.style.width = '16rem';
                toggleIcon.textContent = 'chevron_left';
                sidebarTitle.style.display = 'inline';
                navTexts.forEach(el => el.style.display = 'inline');
                navLinks.forEach(link => {
                    link.classList.remove('justify-center');
                    link.classList.add('justify-start');
                });
            } else {
                sidebar.style.width = '4rem';
                toggleIcon.textContent = 'menu';
                sidebarTitle.style.display = 'none';
                navTexts.forEach(el => el.style.display = 'none');
                navLinks.forEach(link => {
                    link.classList.remove('justify-start');
                    link.classList.add('justify-center');
                });
            }

        });

        // Theme toggle
        const themeBtn = document.getElementById('theme-toggle');
        const icon = themeBtn.querySelector('.material-icons');
        const htmlEl = document.documentElement;

        function updateIcon() {
            if (htmlEl.classList.contains('dark')) {
                icon.textContent = 'light_mode';
            } else {
                icon.textContent = 'dark_mode';
            }
        }

        if (localStorage.theme === 'dark') {
            htmlEl.classList.add('dark');
            updateIcon();
        } else {
            htmlEl.classList.remove('dark');
            updateIcon();
        }

        themeBtn.addEventListener('click', () => {
            htmlEl.classList.toggle('dark');
            localStorage.theme = htmlEl.classList.contains('dark') ? 'dark' : 'light';
            updateIcon();
        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>

@php
function getAdminNotificationColorClass($type) {
    if (strpos($type, 'User') !== false || strpos($type, 'Patient') !== false || strpos($type, 'Doctor') !== false) {
        return 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400';
    }
    if (strpos($type, 'Appointment') !== false || strpos($type, 'Visit') !== false) {
        return 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
    }
    if (strpos($type, 'Payment') !== false) {
        return 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400';
    }
    if (strpos($type, 'Alert') !== false || strpos($type, 'Warning') !== false) {
        return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400';
    }
    if (strpos($type, 'System') !== false || strpos($type, 'Log') !== false) {
        return 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400';
    }
    return 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400';
}

function getAdminNotificationIcon($type) {
    if (strpos($type, 'User') !== false || strpos($type, 'Patient') !== false || strpos($type, 'Doctor') !== false) {
        return 'person';
    }
    if (strpos($type, 'Appointment') !== false) {
        return 'calendar_today';
    }
    if (strpos($type, 'Visit') !== false) {
        return 'how_to_reg';
    }
    if (strpos($type, 'Payment') !== false) {
        return 'payment';
    }
    if (strpos($type, 'Alert') !== false || strpos($type, 'Warning') !== false) {
        return 'warning';
    }
    if (strpos($type, 'System') !== false) {
        return 'settings';
    }
    if (strpos($type, 'Log') !== false) {
        return 'receipt_long';
    }
    return 'info';
}
@endphp
