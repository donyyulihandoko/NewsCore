<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-900">
        <div class="mb-8">
            <a href="/" class="text-3xl font-extrabold text-blue-600">
                NewsCore<span class="text-gray-900 dark:text-white">.</span>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md px-8 py-10 bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden sm:rounded-2xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat Datang Kembali!</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Silakan login untuk mengelola berita kamu.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat
                        Email</label>
                    <input type="email" name="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="nama@email.com" value="{{ old('email') }}" required autofocus
                        autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="••••••••" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="remember_me" class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-400">Ingat
                            saya</label>
                    </div>
                    @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline dark:text-blue-500"
                        href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center transition-all">
                    Sign In
                </button>

                <p class="mt-6 text-sm text-center text-gray-500 dark:text-gray-400">
                    Belum punya akun? <a href="{{ route('register') }}"
                        class="font-medium text-blue-600 hover:underline dark:text-blue-500">Daftar sekarang</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>