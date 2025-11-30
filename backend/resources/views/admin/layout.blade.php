<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex">


    <div class="flex-1 flex flex-col">

        {{-- محتوى الصفحة --}}
        <main class="p-6">
            @yield('content')
        </main>

    </div>

</body>

</html>
