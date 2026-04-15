<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-900">

    <x-admin-navbar/>

    <x-admin-sidebar/>

    <x-alert/>
    
    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            @if (isset($header))
            <header class="mb-6">
                {{ $header }}
            </header>
            @endif

            {{ $slot }}
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>