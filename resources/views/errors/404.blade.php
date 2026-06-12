<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center antialiased">

    <div class="text-center px-6">
        <div class="text-9xl font-extrabold text-gray-200 dark:text-gray-800">
            404
        </div>

        <h1 class="mt-4 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
            Ups, halaman tidak ditemukan
        </h1>

        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
            Maaf, sepertinya halaman yang Anda cari tidak ada atau sudah dipindahkan.
        </p>

        <div class="mt-8">
            <a href="{{ url('/') }}"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>