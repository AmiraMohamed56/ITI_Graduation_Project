{{-- <!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    <!-- Sidebar backdrop for mobile -->
    <div
        class="fixed inset-0 bg-black bg-opacity-25 z-20 lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed z-30 inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-md border-r transform transition-transform duration-300 lg:static lg:translate-x-0 flex-shrink-0"
    >
        <div class="p-6 font-bold text-xl border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <span>Admin Panel</span>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                <span class="material-icons">close</span>
            </button>
        </div>

        <nav class="mt-6">
            <ul class="space-y-1">
                <x-admin.nav-item icon="dashboard" text="Dashboard" href="#"/>
                <x-admin.nav-item icon="person" text="Doctors" href="#"/>
                <x-admin.nav-item icon="group" text="Patients" href="#"/>
                <x-admin.nav-item icon="calendar_today" text="Appointments" href="#"/>
                <x-admin.nav-item icon="settings" text="Profile" href="#"/>
            </ul>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="flex justify-between items-center px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">

            <div class="flex items-center space-x-4">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 lg:hidden">
                    <span class="material-icons">menu</span>
                </button>

                <!-- Breadcrumb -->
                <nav class="text-gray-500 dark:text-gray-400 text-sm">
                    @yield('breadcrumb')
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <button id="theme-toggle" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                    <span class="material-icons">dark_mode</span>
                </button>
                <div>
                    <img src="https://via.placeholder.com/32" class="rounded-full" alt="Admin">
                </div>
            </div>

        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

</div>

<!-- Theme toggle -->
<script>
    const toggleBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;

    if(localStorage.theme === 'dark') htmlEl.classList.add('dark');
    else htmlEl.classList.remove('dark');

    toggleBtn.addEventListener('click', () => {
        htmlEl.classList.toggle('dark');
        localStorage.theme = htmlEl.classList.contains('dark') ? 'dark' : 'light';
    });
</script>

<script src="//unpkg.com/alpinejs" defer></script>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Simple tooltip */
        .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #374151; /* Tailwind gray-700 */
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
                {{-- <x-admin.nav-item icon="dashboard" text="Dashboard" href="#"/>
                <x-admin.nav-item icon="person" text="Doctors" href="#"/>
                <x-admin.nav-item icon="group" text="Patients" href="#"/>
                <x-admin.nav-item icon="calendar_today" text="Appointments" href="#"/> --}}
                <x-admin.nav-item icon="settings" text="Profile" href="{{ route('admin.settings.edit') }}"/>
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
                {{-- <button id="notification-btn" class="relative p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                    <span class="material-icons">notifications</span>
                    <!-- Notification badge -->
                    <span id="notification-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">0</span>
                </button> --}}

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="material-icons">notifications</span>
                        <span id="notification-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">3</span>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden z-50">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700">New user registered</li>
                            <li class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700">New appointment booked</li>
                            <li class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700">Server updated</li>
                        </ul>
                    </div>
                </div>

                <img src="{{ asset('storage/profile_pics/' . Auth::user()->profile_pic) }}" class="w-10 h-10 object-cover rounded-full" alt="Admin">
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

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const toggleIcon = document.getElementById('sidebar-toggle-icon');
    const sidebarTitle = document.getElementById('sidebar-title');
    const navTexts = document.querySelectorAll('#sidebar nav ul li a span.ml-3');

    let open = true;

    toggleBtn.addEventListener('click', () => {
        open = !open;
        if(open) {
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

    function updateIcon () {
        if (htmlEl.classList.contains('dark')) {
            icon.textContent = 'light_mode';
        }else {
            icon.textContent = 'dark_mode';
        }
    }

    if(localStorage.theme === 'dark') {
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
</body>
</html>
