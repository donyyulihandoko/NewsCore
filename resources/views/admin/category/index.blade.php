<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Categories Management</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your blog categories and hierarchy here.</p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-all">
                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Create New Category
            </a>
        </div>
    </x-slot>

    <div
        class="bg-white dark:bg-gray-800 relative shadow-sm border border-gray-200 dark:border-gray-700 sm:rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4">No</th>
                        <th scope="col" class="px-6 py-4">Category Name</th>
                        <th scope="col" class="px-6 py-4">Slug</th>
                        <th scope="col" class="px-6 py-4 text-center">Posts Count</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $index => $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white italic">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 border border-blue-400">
                                {{ $category->slug }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-gray-500 rounded-full">
                                {{ $category->posts_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            No categories available. Click "Create New Category" to add one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</x-app-layout>