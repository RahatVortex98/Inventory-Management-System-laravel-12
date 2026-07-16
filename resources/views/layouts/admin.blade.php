<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    @include('admin.navigation')

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4">
            @yield('header')
        </div>
    </header>

    <main class="py-6">
        @yield('content')
    </main>

</body>
</html>