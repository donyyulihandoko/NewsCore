<x-app-layout>
    <x-slot name="title">{{ $post->title }} | NewsCore</x-slot>

    <article class="min-h-screen bg-white dark:bg-gray-900">

        <header class="relative h-[60vh] min-h-[400px] w-full overflow-hidden">
            @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                class="absolute inset-0 h-full w-full object-cover">
            @else
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-900"></div>
            @endif

            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px]"></div>

            <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center">
                <div class="max-w-4xl">
                    <span
                        class="inline-block px-4 py-2 mb-6 bg-blue-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-full shadow-xl">
                        {{ $post->category->name }}
                    </span>
                    <h1
                        class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight drop-shadow-2xl">
                        {{ $post->title }}
                    </h1>

                    <div class="mt-8 flex items-center justify-center space-x-4 text-white/90 font-bold">
                        <img class="w-10 h-10 rounded-full border-2 border-white/50"
                            src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=random"
                            alt="">
                        <div class="text-left">
                            <p class="text-sm">{{ $post->author->name }}</p>
                            <p class="text-xs opacity-70">{{ $post->created_at->format('M d, Y') }} • {{
                                $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="relative -mt-20 px-4 pb-20">
            <div
                class="mx-auto max-w-4xl bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl p-8 md:p-16 border border-gray-100 dark:border-gray-700">

                <div class="flex items-center justify-between mb-10 pb-8 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex space-x-4">
                        <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            <span class="text-sm font-bold">Save Story</span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Share:</p>
                        <a href="#"
                            class="p-2 bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-xl hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div
                    class="prose prose-lg md:prose-xl dark:prose-invert max-w-none prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-headings:font-black prose-headings:tracking-tighter prose-a:text-blue-600">
                    {!! $post->body !!}
                </div>

                <div class="mt-16 pt-8 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl">#Technology</span>
                        <span
                            class="px-4 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-bold rounded-xl">#Laravel</span>
                    </div>
                </div>
            </div>

            <div
                class="mx-auto max-w-4xl mt-10 p-8 bg-blue-50 dark:bg-blue-900/20 rounded-[2.5rem] flex items-center space-x-6">
                <img class="w-20 h-20 rounded-3xl object-cover shadow-lg"
                    src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=128" alt="">
                <div>
                    <h4 class="text-xl font-black text-gray-900 dark:text-white">Written by {{ $post->author->name }}
                    </h4>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Software Engineer and Tech Enthusiast.
                        Sharing thoughts on modern web development.</p>
                </div>
            </div>
        </div>
    </article>
</x-app-layout>