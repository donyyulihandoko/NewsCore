<x-app-layout>
    <x-slot name="title">My Dashboard | NewsCore</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Personal Dashboard
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your stories and monitor your performance.
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.posts.create') }}"
                        class="inline-flex items-center justify-center px-5 py-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900 transition-all shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Write New Article
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
                <div
                    class="p-6 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Articles</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">12</p>
                </div>
                <div
                    class="p-6 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">1.2k</p>
                </div>
                <div
                    class="p-6 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Feedback</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">84</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">My Recent Articles</h3>
                    <button class="text-sm text-blue-600 hover:underline font-medium">View all</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-bold">Title</th>
                                <th scope="col" class="px-6 py-3">Category</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- Contoh Baris Data --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">Menguasai Laravel 11
                                    untuk Pemula</td>
                                <td class="px-6 py-4">Tech</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Published</span>
                                </td>
                                <td class="px-6 py-4">12 April 2026</td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        class="text-blue-600 dark:text-blue-500 hover:text-blue-900 font-medium">Edit</button>
                                </td>
                            </tr>
                            {{-- ... --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>