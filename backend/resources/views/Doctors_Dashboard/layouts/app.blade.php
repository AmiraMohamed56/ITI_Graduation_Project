<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-collapsed { width: 80px; }
        .sidebar-expanded { width: 240px; }
        .main-content-collapsed { margin-left: 80px; }
        .main-content-expanded { margin-left: 240px; }
        .hidden { display: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">

    @include('Doctors_Dashboard.layouts.partials.sidebar')

    <div id="main-content" class="main-content-expanded transition-all duration-300">
        @include('Doctors_Dashboard.layouts.partials.navbar')

        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
