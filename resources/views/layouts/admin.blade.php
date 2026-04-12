<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

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

<script>
    // Menggunakan Event Delegation agar tetap jalan meski data di-refresh Livewire
    document.addEventListener('click', function (e) {
        // Cek apakah yang diklik adalah tombol delete atau elemen di dalam tombol delete (seperti ikon SVG)
        const button = e.target.closest('.delete-btn');
        
        if (button) {
            e.preventDefault();
            const id = button.getAttribute('data-id');
            const form = document.getElementById('delete-form-' + id);

            if (!form) {
                console.error('Form tidak ditemukan untuk ID:', id);
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#14b8a6', // Teal 500 (sesuai tema kamu)
                cancelButtonColor: '#f43f5e',  // Rose 500
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '1.5rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>