<x-app-layout>
    <x-slot name="title">Topic: {{ $category->name }} | NewsCore</x-slot>
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="relative overflow-hidden p-8 md:p-16 bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-200 dark:border-gray-700 mb-12">
                <div
                    class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-600 rounded-full blur-[120px] opacity-10">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <nav class="flex mb-6 text-sm font-bold uppercase tracking-widest text-blue-600"
                            aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-2">
                                <li><a href="{{ route('topics.index') }}" class="hover:underline">Topics</a></li>
                                <li><span class="text-gray-400 mx-2">/</span></li>
                                <li class="text-gray-400 uppercase tracking-widest">{{ $category->name }}</li>
                            </ol>
                        </nav>

                        <h1 class="text-5xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tighter mb-4">
                            #{{ $category->name }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                            Menampilkan koleksi artikel dan panduan terbaik seputar <span
                                class="font-bold text-gray-900 dark:text-white">{{ $category->name }}</span>.
                            Tetap update dengan tren dan tutorial terbaru.
                        </p>
                    </div>

                    <div
                        class="flex items-center space-x-8 py-6 px-10 bg-gray-50 dark:bg-gray-700/50 rounded-[2rem] border border-gray-100 dark:border-gray-600">
                        <div class="text-center">
                            <p class="text-3xl font-black text-blue-600">{{ $posts->total() }}</p>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Articles</p>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-600"></div>
                        <div class="text-center text-gray-400">
                            <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <p class="text-[10px] font-black uppercase tracking-widest">Trending</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($posts as $post)
                <article
                    class="flex flex-col group bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-500">
                    <div class="relative h-56 overflow-hidden">
                        @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                            <span class="text-white text-sm font-bold uppercase tracking-widest">Read More →</span>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=EBF4FF&color=7F9CF5"
                                class="w-6 h-6 rounded-full" alt="">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $post->author->name
                                }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs font-bold text-gray-400 italic">{{ $post->created_at->diffForHumans()
                                }}</span>
                        </div>

                        <h3
                            class="text-xl font-black text-gray-900 dark:text-white leading-tight mb-4 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                {{ Str::limit($post->title, 60) }}
                            </a>
                        </h3>

                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 mb-6 font-medium italic">
                            {{ Str::limit(strip_tags($post->body), 100) }}
                        </p>

                        <div
                            class="mt-auto pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
                            <a href="{{ route('posts.show', $post->slug) }}"
                                class="text-xs font-black uppercase tracking-widest text-gray-400 group-hover:text-blue-600 transition-colors">
                                Full Story
                            </a>
                            <button class="text-gray-300 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
                @empty
                <div
                    class="col-span-full py-24 text-center bg-white dark:bg-gray-800 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <div
                        class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-400 italic">No articles found in this topic.</h3>
                    <a href="{{ route('topics.index') }}"
                        class="mt-6 inline-block text-blue-600 font-black uppercase tracking-widest text-sm hover:underline">←
                        Browse Other Topics</a>
                </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>