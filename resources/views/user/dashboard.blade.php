<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-12">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                    Welcome back, {{ explode(' ', Auth::user()->name)[0] }}! 👋
                </h1>
                <p class="mt-2 text-lg text-gray-500 dark:text-gray-400 font-medium">
                    Here’s what’s happening in your reading world.
                </p>
            </div>

            <div class="relative overflow-hidden mb-12 p-8 md:p-12 bg-gray-900 rounded-[2.5rem] shadow-2xl">
                <div
                    class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-600 rounded-full blur-3xl opacity-20">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-indigo-600 rounded-full blur-3xl opacity-20">
                </div>

                <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-bold text-white mb-3">Become a NewsCore Author</h2>
                        <p class="text-gray-400 text-lg max-w-lg">
                            Have stories that need to be told? Share your insights with our community of thousands.
                        </p>
                    </div>
                    <a href="#"
                        class="px-8 py-4 bg-white text-gray-900 font-black rounded-2xl hover:bg-blue-50 transition-all hover:scale-105 shadow-xl">
                        Apply to Write
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold">Saved Stories</h3>
                        <a href="/" class="text-blue-600 font-bold text-sm hover:underline">Browse More</a>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center p-16 bg-white dark:bg-gray-800 rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div
                            class="w-20 h-20 mb-6 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium text-center">Your reading list is quiet for now. Start
                            saving articles you love!</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div
                        class="p-8 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-400 uppercase text-xs tracking-widest mb-6">Activity Summary</h4>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <span class="font-bold">Articles Read</span>
                                <span class="text-2xl font-black text-blue-600">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold">Bookmarked</span>
                                <span class="text-2xl font-black text-blue-600">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-blue-50 dark:bg-blue-900/20 rounded-[2rem]">
                        <h4 class="font-bold text-blue-900 dark:text-blue-400 uppercase text-xs tracking-widest mb-6">
                            Explore Topics</h4>
                        <div class="flex flex-wrap gap-3">
                            <span
                                class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl text-sm font-bold shadow-sm">#Laravel</span>
                            <span
                                class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl text-sm font-bold shadow-sm">#Tailwind</span>
                            <span
                                class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl text-sm font-bold shadow-sm">#Engineering</span>
                            <span
                                class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl text-sm font-bold shadow-sm">#UIUX</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>