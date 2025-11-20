<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SATGAS P4GN UNILA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800">
    <main>
        @yield('content')
    </main>

    @stack('scripts')
    
</body>
</html>
