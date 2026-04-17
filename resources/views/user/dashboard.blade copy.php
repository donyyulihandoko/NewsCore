<x-app-layout>
    <x-slot name="title">Reader Hub | NewsCore</x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Hello, {{ Auth::user()->name }}!
                </h1>
                <p class="text-gray-600 dark:text-gray-400">What would you like to read today?</p>
            </div>

            <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                    <li class="mr-2">
                        <a href="#"
                            class="inline-flex items-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                            </svg>
                            Saved Stories
                        </a>
                    </li>
                    <li class="mr-2">
                        <a href="#"
                            class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Reading History
                        </a>
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse([] as $post) {{-- Nanti diisi data bookmark --}}
                @empty
                <div
                    class="col-span-full p-12 text-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Your library is empty</h3>
                    <p class="text-gray-500 mb-6">Start exploring interesting stories and save them here.</p>
                    <a href="/"
                        class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 transition-colors">Browse
                        Articles</a>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>