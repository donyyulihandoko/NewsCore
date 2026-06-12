<x-app-layout title="Dashboard">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white">Hello, {{ auth()->user()->name }} 👋</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Here's a summary of your content
                    performance.</p>
            </div>
            <a href="{{ route('author.posts.create') }}"
                class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 text-center">
                + New Article
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <a href="{{ route('author.posts.index') }}">
                <div
                    class="bg-white hover:bg-blue-100 dark:bg-gray-800 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Total Posts</p>
                    <p class="text-5xl font-black text-gray-900 dark:text-white">{{ $totalPost ?? 0 }}</p>
                </div>
            </a>

            <a href="{{ route('author.published.post') }}">
                <div class="bg-white hover:bg-blue-100 dark:bg-gray-800 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Published Posts</p>
                    <p class="text-5xl font-black text-blue-600">{{ $publishedPost ?? 0 }}</p>
                </div>
            </a>

            <a href="{{ route('author.pending.post') }}">
                <div
                    class="bg-white hover:bg-blue-100 dark:bg-gray-800 p-8 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pending Review</p>
                    <p class="text-5xl font-black text-orange-500">{{ $pendingPosts ?? 0 }}</p>
                </div>
            </a>
    
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Recent Articles</h3>
                <a href="{{ route('author.posts.index') }}"
                    class="text-sm font-black text-blue-600 hover:underline">View All</a>
            </div>
            <table class="w-full text-left">
                <thead
                    class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 text-xs uppercase tracking-widest font-black">
                    <tr>
                        <th class="px-8 py-5">Title</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Created At</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentPosts as $post)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-8 py-6 font-bold text-gray-900 dark:text-white">{{ $post->title }}</td>
                        <td class="px-8 py-6">
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                {{ $post->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500">{{ $post->created_at->format('d M, Y') }}</td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('author.posts.edit', $post->id) }}"
                                class="text-blue-600 font-bold hover:underline">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-gray-400">No articles found. Start writing
                            one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
