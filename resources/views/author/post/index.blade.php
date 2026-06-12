<x-app-layout title="My Posts">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">My Articles</h1>
            <a href="{{ route('author.posts.create') }}"
                class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black hover:bg-blue-700 transition shadow-lg">
                + Create New
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead
                    class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 text-xs uppercase tracking-widest font-black">
                    <tr>
                        <th class="px-8 py-5">Article</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Date</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-8 py-6">
                            <a href="{{ route('author.posts.show', $post) }}">
                                <p class="font-black hover:underline  hover:text-blue-500 text-gray-900 dark:text-white">{{ Str::limit($post->title, 50) }}</p>
                                <p class="text-xs hover:underline  hover:text-blue-500 text-gray-500 mt-1">{{ Str::limit(strip_tags($post->body), 60) }}</p>
                            </a>
                        </td>
                        <td class="px-8 py-6">
                            @if($post->is_published)
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Published</span>
                            @else
                            <span
                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 dark:text-gray-400">
                            {{ $post->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('author.posts.edit', $post) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Post">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                        
                                <form action="{{ route('author.posts.destroy', $post) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center text-gray-400">
                            <p class="font-bold">No articles yet.</p>
                            <a href="{{ route('author.posts.create') }}" class="text-blue-600 hover:underline">Start
                                writing your first story!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>