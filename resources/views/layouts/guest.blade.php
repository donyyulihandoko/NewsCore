<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NewsCore') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-white dark:bg-gray-900">

    <div class="min-h-screen">
        <main>
            {{ $slot }}
        </main>
    </div>

    <footer class="bg-gray-50 border-t border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                Built with Laravel, Livewire & Tailwind CSS.
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('livewire:navigated', () => { 
                if (typeof initFlowbite === 'function') {
                    initFlowbite();
                }
            });
    </script>
</body>

</html>