<x-app-layout>
    <x-slot name="title">Topic: {{ $category->name }} | NewsCore</x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700 mb-12">

                <div class="absolute inset-0 z-0">
                    @if($category->image)
                    <img src="{{ Str::startsWith($category->image, 'http') ? $category->image : Storage::url($category->image) }}"
                        class="w-full h-full object-cover transition-transform duration-1000 transform group-hover:scale-110"
                        alt="{{ $category->name }}">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700"></div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    <div
                        class="absolute top-0 left-0 -mt-20 -ml-20 w-96 h-96 bg-blue-500 rounded-full blur-[120px] opacity-20">
                    </div>
                </div>

                <div
                    class="relative z-10 p-8 md:p-16 flex flex-col md:flex-row md:items-end justify-between gap-10 min-h-[400px]">
                    <div class="max-w-3xl text-white">
                        <nav class="flex mb-6 text-sm font-bold uppercase tracking-widest text-blue-300"
                            aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-2">
                                <li><a href="{{ route('topics.index') }}" class="hover:underline">Topics</a></li>
                                <li><span class="text-white/40 mx-2">/</span></li>
                                <li class="text-white/60">{{ $category->name }}</li>
                            </ol>
                        </nav>

                        <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter mb-6 leading-none">
                            <span class="text-blue-400">#</span>{{ $category->name }}
                        </h1>
                        <p class="text-xl text-white/80 font-medium leading-relaxed max-w-2xl">
                            {{ $category->description ?? 'Menampilkan koleksi artikel dan panduan terbaik seputar ' .
                            $category->name . '.' }}
                        </p>
                    </div>

                    <div
                        class="flex items-center space-x-8 py-8 px-10 bg-white/10 dark:bg-black/20 rounded-[2.5rem] border border-white/10 backdrop-blur-lg shadow-xl">
                        <div class="text-center">
                            <p class="text-5xl font-black text-white">{{ $posts->total() }}</p>
                            <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.2em]">Articles</p>
                        </div>
                        <div class="w-px h-12 bg-white/10"></div>
                        <div class="text-center text-white/50 hover:text-blue-300 transition-colors">
                            <div
                                class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-1 border border-white/10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest">Trending</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($posts as $post)
                <article
                    class="flex flex-col group bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-1">

                    <div class="relative h-64 overflow-hidden">
                        @if($post->image)
                        <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}" alt="{{ $post->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                        <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center space-x-3 mb-6">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=EBF4FF&color=3B82F6&bold=true"
                                class="w-8 h-8 rounded-xl shadow-sm" alt="{{ $post->author->name }}">
                            <div>
                                <p class="text-xs font-black text-gray-900 dark:text-white leading-none mb-1">{{
                                    $post->author->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{
                                    $post->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <h3
                            class="text-2xl font-black text-gray-900 dark:text-white leading-tight mb-4 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                {{ Str::limit($post->title, 60) }}
                            </a>
                        </h3>

                        <p
                            class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-8 font-medium">
                            {{ Str::limit(strip_tags($post->body), 120) }}
                        </p>

                        <div
                            class="mt-auto pt-6 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                            <a href="{{ route('posts.show', $post) }}"
                                class="text-xs font-black uppercase tracking-[0.2em] text-blue-600 hover:text-blue-700 transition-colors flex items-center">
                                Read Full Story
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                @endforelse
            </div>

            <div class="mt-16">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>