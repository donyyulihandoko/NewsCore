<x-guest-layout>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-900 px-4">
        <div class="mb-8 text-center">
            <a href="/" class="text-3xl font-extrabold text-blue-600">
                NewsCore<span class="text-gray-900 dark:text-white">.</span>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md px-8 py-10 bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden sm:rounded-2xl">
            <div class="flex justify-center mb-6">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Email Anda</h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik
                    tautan yang baru saja kami kirimkan. Jika tidak menerimanya, kami akan mengirimkan ulang.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded">
                Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
            </div>
            @endif

            <div class="mt-8 flex flex-col space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center transition-all">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit"
                        class="text-sm text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white underline transition-colors">
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-xs text-center text-gray-400 dark:text-gray-500">
            Butuh bantuan? Hubungi <a href="mailto:support@newscore.com" class="text-blue-500 hover:underline">Tim
                Support</a> kami.
        </p>
    </div>
</x-guest-layout>