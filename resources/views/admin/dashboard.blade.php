<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name }}! Here's what's
            happening with NewsCore today.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</span>
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">+12%</span>
            </div>
            <div class="flex items-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">1,240</h3>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12 7a1 1 0 110-2h5V4a1 1 0 011-1h-4a1 1 0 01-1 1v3zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>32 new posts this week</span>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</span>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
            </div>
            <div class="flex items-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">18</h3>
            </div>
            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 font-medium">Active Topics</p>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Comments</span>
                <span
                    class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Spam:
                    3</span>
            </div>
            <div class="flex items-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">856</h3>
            </div>
            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 italic">Across all platforms</p>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Storage Usage</span>
                <span class="text-xs font-bold text-blue-600">45% Full</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
            </div>
            <p class="mt-4 text-xs text-gray-500">Last backup: 2 hours ago</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div
            class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Posts</h3>
                <a href="#" class="text-sm font-medium text-blue-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">The Future of Laravel 11
                            </td>
                            <td class="px-4 py-3"><span
                                    class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Published</span>
                            </td>
                            <td class="px-4 py-3">Oct 12, 2026</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">Tailwind CSS v4 Roadmap</td>
                            <td class="px-4 py-3"><span
                                    class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Draft</span>
                            </td>
                            <td class="px-4 py-3">Oct 11, 2026</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">Mastering Service Pattern
                            </td>
                            <td class="px-4 py-3"><span
                                    class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Published</span>
                            </td>
                            <td class="px-4 py-3">Oct 10, 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#"
                        class="flex flex-col items-center justify-center p-3 text-center bg-gray-50 rounded-lg hover:bg-blue-50 group transition-all dark:bg-gray-700 dark:hover:bg-gray-600">
                        <svg class="w-6 h-6 mb-1 text-blue-600 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="text-xs font-medium text-gray-900 dark:text-white">New Post</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}"
                        class="flex flex-col items-center justify-center p-3 text-center bg-gray-50 rounded-lg hover:bg-green-50 group transition-all dark:bg-gray-700 dark:hover:bg-gray-600">
                        <svg class="w-6 h-6 mb-1 text-green-600 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <span class="text-xs font-medium text-gray-900 dark:text-white">New Category</span>
                    </a>
                </div>
            </div>

            <div class="p-4 bg-blue-600 rounded-lg shadow-lg text-white">
                <h3 class="font-bold mb-2">NewsCore Pro Tip</h3>
                <p class="text-xs opacity-90 leading-relaxed">Don't forget to check your "Spam" folder in comments
                    regularly to keep your engagement clean and healthy!</p>
            </div>
        </div>

    </div>
</x-admin-layout>