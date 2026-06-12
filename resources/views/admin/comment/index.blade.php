<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Manage Comments</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review, moderate, and manage user engagement
                    across your posts.</p>
            </div>

            {{-- Statistik Singkat --}}
            <div class="flex items-center space-x-4">
                <div
                    class="px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-center shadow-sm">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Total
                        Feedback</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $comments->total() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ searchQuery: '' }">

        {{-- Flash Message Alert --}}
        @if (session()->has('error'))
        <div
            class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-200 text-sm font-medium rounded-xl flex items-center space-x-2.5 shadow-sm">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Toolbar Filter & Search --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input x-model="searchQuery" type="text" placeholder="Search comments or users..."
                    class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 shadow-sm transition-all">
            </div>
        </div>

        {{-- Main Table Container --}}
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-all">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="bg-gray-50/70 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            <th
                                class="p-4 text-xs font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider w-1/4">
                                User</th>
                            <th
                                class="p-4 text-xs font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider w-5/12">
                                Comment</th>
                            <th
                                class="p-4 text-xs font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider w-1/4">
                                On Article</th>
                            <th
                                class="p-4 text-xs font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider text-right w-24">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($comments as $comment)
                        {{-- Baris di-filter secara real-time via Alpine.js menggunakan pencarian lokal --}}
                        <tr x-show="searchQuery === '' || 
                                    `{{ strtolower($comment->user->name) }}`.includes(searchQuery.toLowerCase()) || 
                                    `{{ strtolower($comment->body) }}`.includes(searchQuery.toLowerCase())"
                            class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">

                            {{-- User Metadata Column --}}
                            <td class="p-4 align-top">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 border border-blue-100/50 dark:border-gray-600 flex flex-shrink-0 items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm uppercase shadow-sm">
                                        {{ substr($comment->user->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4
                                            class="font-semibold text-sm text-gray-900 dark:text-white truncate hover:text-blue-600 transition-colors cursor-default">
                                            {{ $comment->user->name }}</h4>
                                        <span
                                            class="inline-flex mt-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md {{ $comment->user->role === 'admin' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                            {{ $comment->user->role }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Comment Body Column (Interaktif Expand via Alpine.js) --}}
                            <td class="p-4 align-top" x-data="{ expanded: false }">
                                <div class="space-y-2">
                                    <div>
                                        <p @click="expanded = !expanded" :class="expanded ? '' : 'line-clamp-3'"
                                            class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line break-words leading-relaxed cursor-pointer hover:text-gray-900 dark:hover:text-white transition-colors">
                                            {{ $comment->body }}
                                        </p>
                                        {{-- Indikator Teks Panjang --}}
                                        @if(strlen($comment->body) > 120)
                                        <button @click="expanded = !expanded"
                                            class="text-xs font-semibold text-blue-600 dark:text-blue-400 mt-1 hover:underline focus:outline-none">
                                            <span x-text="expanded ? 'Show less' : 'Read full comment'"></span>
                                        </button>
                                        @endif
                                    </div>
                                    <div class="flex items-center text-xs text-gray-400 dark:text-gray-500 space-x-1.5">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span title="{{ $comment->created_at->format('d M Y, H:i') }}">{{
                                            $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Linked Post Column --}}
                            <td class="p-4 align-top">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.posts.edit', $comment->post_id) }}"
                                        class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 group max-w-full">
                                        <span class="truncate transition-colors">{{ $comment->post->title }}</span>
                                        <svg class="w-3.5 h-3.5 ml-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 text-blue-500 transition-all flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="p-4 align-top text-right whitespace-nowrap">
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus komentar ini secara permanen?')"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/50 px-3 py-2 rounded-xl border border-red-100/70 dark:border-red-900/30 transition-all focus:ring-2 focus:ring-red-500/20">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3.5">
                                    <div
                                        class="p-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 rounded-2xl shadow-inner">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="max-w-xs">
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">No comments yet</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">When
                                            users express their thoughts on your published articles, they will appear
                                            right here.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tailored Pagination Area --}}
            @if ($comments->hasPages())
            <div class="p-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-200 dark:border-gray-700">
                {{ $comments->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>