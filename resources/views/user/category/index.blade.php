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
                    Temukan artikel, panduan, dan wawasan mendalam yang dikurasi berdasarkan minatmu.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($categories as $category)
                <a href="{{ route('topics.show', $category) }}"
                    class="group relative flex flex-col bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 hover:border-blue-500 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/20 overflow-hidden">

                    <div class="relative h-52 overflow-hidden">
                        @if($category->image)
                        <img src="{{ Str::startsWith($category->image, 'http') ? $category->image : Storage::url($category->image) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="{{ $category->name }}">
                        @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif

                        <div
                            class="absolute top-4 right-4 px-4 py-2 bg-black/30 backdrop-blur-md border border-white/20 rounded-2xl text-white">
                            <span class="text-xl font-black">{{ $category->posts_count ?? 0 }}</span>
                            <span class="text-[10px] uppercase font-bold tracking-tighter ml-1">Stories</span>
                        </div>

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60">
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <h3
                                class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                        </div>

                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 mb-6">
                            {{ $category->description ?? 'Jelajahi panduan terbaru seputar ' . $category->name . '.' }}
                        </p>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">
                                Explore Topic
                            </span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-2 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                @endforelse
            </div>

            <div class="mt-16">
                {{ $categories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>