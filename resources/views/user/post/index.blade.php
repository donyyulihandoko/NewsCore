<x-app-layout>
    <x-slot name="title">Explore Stories | NewsCore</x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-12 text-center md:text-left">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight sm:text-5xl">
                    Latest Stories
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-2xl font-medium">
                    Jelajahi wawasan terbaru, panduan teknis, dan berita terhangat dari komunitas kami.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                    <article
                        class="group relative bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden">

                        <div class="absolute top-5 left-5 z-10">
                            <span
                                class="px-4 py-2 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-widest rounded-xl shadow-sm">
                                {{ $post->category->name }}
                            </span>
                        </div>

                        <div class="aspect-video w-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if($post->image)
                            <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                            <div class="flex items-center justify-center h-full text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="p-8">
                            <div
                                class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-4 font-bold uppercase tracking-tighter space-x-2">
                                <span>{{ $post->author->name }}</span>
                                <span>•</span>
                                <time datetime="{{ $post->created_at }}">
                                    {{ $post->created_at->diffForHumans() }}
                                </time>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 dark:text-white leading-tight mb-4 group-hover:text-blue-600 transition-colors">
                                <a href="#">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                            </h3>

                            <p
                                class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6 font-medium">
                                {{ Str::limit(strip_tags($post->body), 120) }}
                            </p>

                            <div
                                class="flex items-center justify-between mt-auto pt-6 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('posts.show', $post) }}"
                                    class="text-sm font-black text-blue-600 dark:text-blue-400 flex items-center group/link">
                                    Read Full Story
                                    <svg class="w-4 h-4 ml-2 group-hover/link:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>

                                <button class="text-gray-400 hover:text-blue-600 transition-colors">
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
                    class="col-span-full py-20 text-center bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-lg italic">Belum ada cerita yang
                        dipublikasikan.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
