<x-app-layout>
    <x-slot name="title">{{ $post->title }} | NewsCore</x-slot>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div class="flex items-center space-x-3">
                <a href="{{ route('author.posts.index') }}"
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
                <a href="{{ route('author.posts.edit', $post) }}"
                    class="text-blue-600 bg-white border border-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">
                    Edit Article
                </a>

                <form action="{{ route('author.posts.destroy', $post) }}" method="POST"
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
                    <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}"
                        alt="Cover image for {{ $post->title }}"
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

            {{-- ================= SEKSI DISKUSI & KOMENTAR ================= --}}
            <div
                class="p-8 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm">
                <div class="mb-8">
                    <h3
                        class="text-xl font-black text-gray-900 dark:text-white tracking-tight mb-2 flex items-center gap-3">
                        Discussion
                        <span
                            class="text-xs font-bold bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
                            {{ $post->comments->count() }}
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Join the conversation or leave feedback on this
                        draft.</p>
                </div>

                {{-- Form Kirim Komentar Baru --}}
                @auth
                <form action="{{ route('author.posts.comments.store', $post) }}" method="POST" class="space-y-4 mb-10">
                    @csrf
                    <div>
                        <textarea name="body" rows="3" required placeholder="Write a professional review or comment..."
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm text-gray-900 dark:text-white rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 placeholder-gray-400 dark:placeholder-gray-500 transition-all resize-none @error('body') border-red-500 @enderror">{{ old('body') }}</textarea>

                        @error('body')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 transition-all transform hover:-translate-y-0.5">
                            Submit Comment
                        </button>
                    </div>
                </form>
                @else
                <div
                    class="p-4 mb-8 bg-gray-50 dark:bg-gray-900/30 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center">
                    <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                        You must be <a href="{{ route('login') }}"
                            class="text-blue-600 dark:text-blue-400 font-bold hover:underline">logged in</a> to post a
                        comment.
                    </p>
                </div>
                @endauth

                {{-- List Alur Komentar --}}
                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                    @forelse ($post->comments->sortByDesc('created_at') as $comment)
                    <div
                        class="group flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-700/10 transition-colors">

                        {{-- Avatar Inisial Bulat Kotak --}}
                        <div
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 border border-blue-100/50 dark:border-gray-600 flex flex-shrink-0 items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs uppercase shadow-sm">
                            {{ substr($comment->user->name, 0, 2) }}
                        </div>

                        <div class="flex-1 min-w-0 space-y-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white truncate">
                                        {{ $comment->user->name }}
                                    </h4>
                                    @if($comment->user_id === $post->user_id)
                                    <span
                                        class="px-1.5 py-0.5 text-[8px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 rounded-md">
                                        Author
                                    </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 whitespace-nowrap"
                                    title="{{ $comment->created_at->format('d M Y, H:i') }}">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p
                                class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line break-words leading-relaxed">
                                {{ $comment->body }}
                            </p>

                            {{-- Tombol Delete Terproteksi (Hanya muncul jika komentar milik user yang sedang aktif
                            login) --}}
                            @auth
                            @if($comment->user_id === auth()->id() || $post->user_id === auth()->id())
                            <div class="pt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('author.comments.destroy', $comment) }}" method="POST"
                                    onsubmit="return confirm('Delete this comment permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-[11px] font-bold text-red-500 dark:text-red-400 hover:underline focus:outline-none">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                            @endauth
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500">No comments on this preview
                            yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar Informasi Artikel --}}
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