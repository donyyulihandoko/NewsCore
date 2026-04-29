<x-guest-layout>
    <nav class="bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600 dark:text-blue-500">
                        NewsCore<span class="text-gray-900 dark:text-white">.</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                    @auth
                    @if (Auth::user()->role === 'author')
                        <a href="{{ route('author.dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">Dashboard</a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard.index') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">Dashboard</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">Dashboard</a>
                    @endif
                    
                    @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Log
                        in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Register</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-7xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                    Stay Informed with <span class="text-blue-600">NewsCore</span></h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                    Dapatkan berita terpercaya seputar teknologi, pemrograman, dan gaya hidup langsung dari sumbernya.
                    Ringkas, tajam, dan edukatif.</p>
                <a href="#latest-news"
                    class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    Mulai Membaca
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/phone-mockup.png" alt="mockup">
            </div>
        </div>
    </section>

    <div class="bg-gray-50 dark:bg-gray-800 py-6 border-y border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 overflow-x-auto flex space-x-4 no-scrollbar">
            <span
                class="px-4 py-2 bg-blue-600 text-white rounded-full text-sm font-medium whitespace-nowrap cursor-pointer">All
                Topics</span>
            <span
                class="px-4 py-2 bg-white text-gray-600 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap cursor-pointer hover:bg-blue-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Laravel</span>
            <span
                class="px-4 py-2 bg-white text-gray-600 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap cursor-pointer hover:bg-blue-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Tailwind
                CSS</span>
            <span
                class="px-4 py-2 bg-white text-gray-600 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap cursor-pointer hover:bg-blue-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Finance</span>
        </div>
    </div>

    <section id="latest-news" class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 dark:text-white">Berita Terbaru</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(range(1, 6) as $item)
                <article
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden group hover:shadow-md transition-shadow">
                    <img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/content/office-content-1.png"
                        alt="Article Image">
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Tech</span>
                            <span class="text-gray-500 text-xs italic dark:text-gray-400">5 min read</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2 dark:text-white group-hover:text-blue-600 transition-colors">
                            <a href="#">Membangun Backend Powerhouse dengan Laravel 11</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">Pelajari bagaimana pola
                            Service dan Repository bisa membuat aplikasi NewsCore kamu lebih maintainable...</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                    A</div>
                                <span class="text-sm font-medium dark:text-white">Admin</span>
                            </div>
                            <span class="text-xs text-gray-500">12 Apr 2026</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <button
                    class="px-6 py-3 border border-gray-300 rounded-full font-medium text-gray-700 hover:bg-gray-50 dark:text-white dark:border-gray-600 dark:hover:bg-gray-800 transition-colors">
                    Lihat Berita Lainnya
                </button>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-200 p-4 py-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7xl mx-auto text-center">
            <span class="text-sm text-gray-500 dark:text-gray-400">© 2026 <a href="/"
                    class="hover:underline">NewsCore™</a>. All Rights Reserved.</span>
        </div>
    </footer>
</x-guest-layout>