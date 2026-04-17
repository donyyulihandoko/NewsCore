<x-app-layout>
    <x-slot name="title">Explore Topics | NewsCore</x-slot>

    <div class="py-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-4xl mb-20 text-center md:text-left">
                <span
                    class="inline-block px-4 py-2 mb-4 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-black uppercase tracking-[0.2em] rounded-full">
                    Knowledge Hub
                </span>
                <h1
                    class="text-5xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tighter leading-tight mb-6">
                    Dive into <span class="text-blue-600">Specialized Topics</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium leading-relaxed max-w-3xl">
                    Temukan artikel, panduan, dan wawasan mendalam yang dikurasi berdasarkan minatmu. Mulai jelajahi
                    sekarang.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($categories as $category)
                <a href="{{ route('topics.show', $category) }}"
                    class="group relative p-8 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-[2rem] border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2 overflow-hidden flex flex-col">

                    <div
                        class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <div
                            class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-gray-600 transition-colors group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>

                        <div class="text-right">
                            <span
                                class="text-4xl font-black text-gray-300 dark:text-gray-700 group-hover:text-blue-100 transition-colors duration-500">
                                {{ $category->posts_count ?? 0 }}
                            </span>
                            <p
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest -mt-1 group-hover:text-blue-100 transition-colors duration-500">
                                Stories
                            </p>
                        </div>
                    </div>

                    <div class="flex-grow mb-6">
                        <h3
                            class="text-2xl font-bold text-gray-900 dark:text-white leading-tight mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                            {{ $category->name }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed font-medium line-clamp-2">
                            {{ $category->description ?? 'Jelajahi panduan terbaru, tren, dan wawasan mendalam seputar '
                            . $category->name . '.' }}
                        </p>
                    </div>

                    <div
                        class="mt-auto pt-6 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                        <span class="text-sm font-black text-blue-600 dark:text-blue-400 flex items-center">
                            View Articles
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                        <span class="text-gray-300 dark:text-gray-600">/</span>
                    </div>
                </a>
                @empty
                <div
                    class="col-span-full p-20 text-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-[3rem] border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-500 italic">No topics available yet.</h3>
                </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $categories->links() }}
            </div>

            <div
                class="mt-28 p-12 md:p-16 bg-gray-900 rounded-[3rem] shadow-2xl relative overflow-hidden text-center md:text-left">
                <div
                    class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-600 rounded-full blur-[100px] opacity-20">
                </div>

                <div class="relative md:flex items-center justify-between gap-10">
                    <div class="max-w-xl mb-8 md:mb-0">
                        <h4 class="text-3xl font-black text-white leading-tight mb-4">Mencari sesuatu yang spesifik?
                        </h4>
                        <p class="text-gray-400 text-lg font-medium">Gunakan fitur pencarian untuk menemukan artikel
                            atau kembali ke beranda untuk feed terbaru.</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-10 py-4 bg-white text-gray-900 font-black rounded-2xl hover:bg-gray-100 transition-all hover:scale-105 shadow-xl whitespace-nowrap">
                        Go to Main Feed
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>