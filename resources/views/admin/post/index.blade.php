<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">NewsCore Articles</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage all your news content, authors, and
                    summaries. </p>
            </div>

            <a href="{{ route('admin.posts.create') }}"
                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 transition-all">
                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Create Article
            </a>
        </div>
    </x-slot>

    <div
        class="bg-white dark:bg-gray-800 relative shadow-sm border border-gray-200 dark:border-gray-700 sm:rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-10">No</th>
                        <th scope="col" class="px-6 py-4">Title & Slug</th>
                        <th scope="col" class="px-6 py-4">Author</th>
                        <th scope="col" class="px-6 py-4">Body Summary</th>
                        <th scope="col" class="px-6 py-4">Category</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($posts as $index => $post)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ ($posts->currentPage() - 1) * $posts->perPage() + $index + 1 }}
                        </td>

                        
                        <td class="px-6 py-4 hover:bg-blue-200">   
                            <a href="{{ route('admin.posts.show', $post) }}">           
                                <div class="text-base font-bold text-gray-900 dark:text-white line-clamp-1">{{ $post->title}}</div>
                                <div class="text-[10px] text-blue-500 font-mono italic">{{ $post->slug }}</div>  
                            </a>            
                        </td>
                    
                    

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-blue-100 rounded-full dark:bg-gray-600 border border-blue-200">
                                    <span class="font-bold text-blue-600 dark:text-gray-300 text-xs uppercase">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $post->author->name }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 max-w-xs">
                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ Str::limit($post->body, 70, '...') }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <span
                                class="bg-gray-100 text-gray-800 text-[10px] font-bold uppercase px-2 py-1 rounded dark:bg-gray-700 dark:text-gray-300 border border-gray-200">
                                {{ $post->category->name }}
                            </span>
                        </td>

                        <td class="px-6 py-4 flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.posts.edit', $post) }}"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
        <div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>