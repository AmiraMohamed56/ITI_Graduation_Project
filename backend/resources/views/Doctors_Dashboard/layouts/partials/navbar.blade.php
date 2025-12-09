{{-- File: resources/views/Doctors_Dashboard/layouts/partials/navbar.blade.php --}}
<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3 sticky top-0 z-40">
    <div class="flex justify-end items-center">
        <!-- Right Side Icons -->
        <div class="flex items-center space-x-4 ml-6">

            <!-- Notifications Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-9 h-9 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full relative transition-colors duration-200">
                    <i class="fas fa-bell"></i>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    <span class="absolute -top-1 -right-1 bg-red-600 dark:bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                        {{ Auth::user()->unreadNotifications()->count() > 9 ? '9+' : Auth::user()->unreadNotifications()->count() }}
                    </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open"
                    @click.away="open = false"
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
                                <i class="fas fa-bell mr-2"></i> Notifications
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
                        <a href="{{ route('doctor.notifications.index') }}"
                            class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} transition-colors">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ getNotificationColorClass($notification->type) }}">
                                    {!! getNotificationIconSVG($notification->type) !!}
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 truncate mt-1">
                                        {{ Str::limit($notification->data['message'] ?? '', 60) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="far fa-clock mr-1"></i>
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
                            <i class="fas fa-bell-slash text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-sm">No notifications</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Footer -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <a href="{{ route('doctor.notifications.index') }}"
                            class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold transition-colors">
                            View All Notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <button onclick="toggleTheme()"
                class="w-9 h-9 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors duration-200"
                title="Toggle Dark Mode">
                <i id="theme-toggle-icon" class="fas fa-moon"></i>
            </button>

            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ Auth::user()->profile_picture_url }}"
                        alt="{{ Auth::user()->name }}"
                        class="w-9 h-9 rounded-full border-2 border-gray-200 dark:border-gray-600 object-cover">
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-1 z-50 border border-gray-200 dark:border-gray-700">

                    <a href="{{ route('profile_setting.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>

                    <a href="{{ route('profile_setting.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>

                    <hr class="my-1 border-gray-200 dark:border-gray-700">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Theme toggle functionality
    function toggleTheme() {
        const html = document.documentElement;
        const icon = document.getElementById('theme-toggle-icon');

        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }

    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const icon = document.getElementById('theme-toggle-icon');

        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            document.documentElement.classList.remove('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    });
</script>
@endpush

@php
function getNotificationColorClass($type) {
if (strpos($type, 'Appointment') !== false) return 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
if (strpos($type, 'Reminder') !== false) return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400';
if (strpos($type, 'Payment') !== false) return 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400';
if (strpos($type, 'Patient') !== false) return 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400';
return 'bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400';
}

function getNotificationIconSVG($type) {
if (strpos($type, 'Appointment') !== false) {
return '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
</svg>';
}
if (strpos($type, 'Reminder') !== false) {
return '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
</svg>';
}
if (strpos($type, 'Payment') !== false) {
return '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>';
}
return '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>';
}
@endphp
