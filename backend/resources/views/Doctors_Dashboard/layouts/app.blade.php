<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-collapsed {
            width: 80px;
        }

        .sidebar-expanded {
            width: 240px;
        }

        .main-content-collapsed {
            margin-left: 80px;
        }

        .main-content-expanded {
            margin-left: 240px;
        }

        .hidden {
            display: none;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    @include('Doctors_Dashboard.layouts.partials.sidebar')

    <div id="main-content" class="main-content-expanded transition-all duration-300">
        @include('Doctors_Dashboard.layouts.partials.navbar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <!-- Dark Mode Script مبسط -->
    <script>
        // التحقق من التفضيل المخزن أو تفضيل النظام
        function getThemePreference() {
            if (localStorage.getItem('darkMode') === 'true') {
                return 'dark';
            }
            if (localStorage.getItem('darkMode') === 'false') {
                return 'light';
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        // تطبيق الـ Theme
        function applyTheme(theme) {
            const html = document.documentElement;
            if (theme === 'dark') {
                html.classList.add('dark');
                html.classList.remove('light');
                localStorage.setItem('darkMode', 'true');
            } else {
                html.classList.add('light');
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            }
        }

        // تبديل الـ Theme
        window.toggleTheme = function() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                applyTheme('light');
            } else {
                applyTheme('dark');
            }
        }

        // التهيئة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            applyTheme(getThemePreference());

            // تحديث أيقونة الـ toggle
            updateThemeIcon();

            // استماع لتغير تفضيلات النظام
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('darkMode')) {
                    applyTheme(e.matches ? 'dark' : 'light');
                    updateThemeIcon();
                }
            });
        });

        function updateThemeIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            if (!icon) return;

            if (document.documentElement.classList.contains('dark')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                icon.title = 'Switch to Light Mode';
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                icon.title = 'Switch to Dark Mode';
            }
        }

        window.addEventListener('storage', function(e) {
            if (e.key === 'darkMode') {
                applyTheme(e.newValue === 'true' ? 'dark' : 'light');
                updateThemeIcon();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
