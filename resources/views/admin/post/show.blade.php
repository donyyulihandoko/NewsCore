<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.posts.index') }}"
                    class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-500 hover:text-blue-600 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Article Preview</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Viewing: {{ $post->slug }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.posts.edit', $post) }}"
                    class="text-blue-600 bg-white border border-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">
                    Approve Article
                </a>

                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="text-red-600 bg-white border border-red-700 hover:bg-red-50 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <article
                class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm">

                {{-- BAGIAN GAMBAR UTAMA (FEATURED IMAGE) --}}
                @if($post->image)
                <div
                    class="relative mb-8 rounded-2xl overflow-hidden shadow-lg border border-gray-100 dark:border-gray-700 group">
                    {{-- Overlay gradien halus --}}
                    <div
                        class="absolute inherit bg-gradient-to-b from-transparent to-black/30 group-hover:to-black/50 transition-all duration-300">
                    </div>

                    {{-- Gambar dengan rasio 16:9 agar konsisten --}}
                    <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}" alt="Cover image for {{ $post->title }}"
                        class="w-full h-auto aspect-[16/9] object-cover object-center transform group-hover:scale-105 transition-transform duration-500 ease-out"
                        loading="lazy">

                    {{-- Badge Kategori yang dipindahkan ke atas gambar --}}
                    <div class="absolute top-4 left-4 z-10">
                        <span
                            class="bg-blue-500/90 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-sm shadow-md">
                            {{ $post->category->name }}
                        </span>
                    </div>
                </div>
                @endif
                {{-- SELESAI BAGIAN GAMBAR UTAMA --}}

                <div class="flex items-center space-x-2 mb-4">
                    {{-- Badge kategori dilepas dari sini jika ada gambar --}}
                    @if(!$post->image)
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                        {{ $post->category->name }}
                    </span>
                    <span class="text-gray-400 text-sm">•</span>
                    @endif
                    <span class="text-gray-500 dark:text-gray-400 text-sm italic">
                        Published on {{ $post->created_at->format('M d, Y') }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                <hr class="border-gray-100 dark:border-gray-700 mb-8">

                <div class="prose prose-blue max-w-none dark:prose-invert 
                    prose-headings:text-gray-900 dark:prose-headings:text-white
                    prose-p:text-gray-600 dark:prose-p:text-gray-300
                    prose-strong:text-gray-900 dark:prose-strong:text-white">
                    {!! $post->body !!}
                </div>
            </article>
        </div>

        <div class="space-y-6">
            <div
                class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Author Details</h3>
                <div class="flex items-center space-x-4">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/30">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-base font-bold text-gray-900 dark:text-white">{{ $post->author->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $post->author->email }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-900 dark:bg-blue-900/10 rounded-2xl text-white shadow-xl">
                <h3 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4">Post Information</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between">
                        <span class="text-gray-400">Total Characters</span>
                        <span class="font-mono">{{ strlen(strip_tags($post->body)) }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-400">Word Count</span>
                        <span class="font-mono">{{ str_word_count(strip_tags($post->body)) }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-400">Read Time</span>
                        <span class="font-mono text-blue-400">{{ ceil(str_word_count(strip_tags($post->body)) / 200) }}
                            min</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>