<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, <span
                        class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>. Here's your daily summary.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="text-xs font-semibold px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-300">
                    {{ now()->format('d M, Y') }}
                </span>
            </div>
        </div>
    </x-slot>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
        $stats = [
        ['title' => 'Total Posts', 'value' => $totalPosts, 'icon' => 'document-text', 'color' => 'blue', 'route' =>
        'admin.posts.index'],
        ['title' => 'Pending Review', 'value' => $totalPendingPosts, 'icon' => 'clock', 'color' => 'amber', 'route' =>
        'admin.pending.post'],
        ['title' => 'Published', 'value' => $totalPublishedPosts, 'icon' => 'check-circle', 'color' => 'green', 'route'
        => 'admin.published.post'],
        ['title' => 'Categories', 'value' => $totalCategories, 'icon' => 'tag', 'color' => 'indigo', 'route' =>
        'admin.categories.index'],
        ['title' => 'Comments', 'value' => $totalComments, 'icon' => 'tag', 'color' => 'indigo', 'route' =>
        'admin.comments.index']
        ];
        @endphp
    
        @foreach($stats as $stat)
        <a href="{{ route($stat['route']) }}" class="group transition-all duration-300 hover:-translate-y-1">
            <div
                class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:shadow-{{ $stat['color'] }}-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-{{ $stat['color'] }}-50 dark:bg-gray-700 rounded-xl">
                        {{-- Icon Placeholder (Gunakan Blade components/heroicons di sini) --}}
                        <div class="w-6 h-6 text-{{ $stat['color'] }}-600">
                            {{-- Sederhanakan dengan icon svg biasa --}}
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ $stat['title'] }}</span>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stat['value'] }}</h3>
            </div>
        </a>
        @endforeach
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Posts --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity</h3>
                <a href="{{ route('admin.pending.post') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800">View All →</a>
            </div>
    
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Article Title</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Approved Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        {{-- Contoh row --}}
                        @foreach ($recentActivity as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->title }}
                            </td>
    
                            <td class="px-6 py-4">
                                @if($item->is_published)
                                <span
                                    class="px-3 py-1 text-[10px] font-bold uppercase rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Published</span>
                                @else
                                <span
                                    class="px-3 py-1 text-[10px] font-bold uppercase rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                @endif
                            </td>
    
                            <td class="px-6 py-4 text-right text-gray-500">{{ $item->updated_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        {{-- Quick Actions Sidebar --}}
        <div class="space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#"
                        class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors group">
                        <span class="text-blue-600 mb-2 font-bold">+</span>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-700">New
                            Post</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}"
                        class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-green-50 dark:hover:bg-gray-600 transition-colors group">
                        <span class="text-green-600 mb-2 font-bold">📂</span>
                        <span
                            class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-green-700">Category</span>
                    </a>
                </div>
            </div>
    
            <div
                class="p-6 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl shadow-blue-900/20 text-white">
                <h3 class="font-bold flex items-center mb-3">
                    <span class="mr-2">💡</span> NewsCore Tip
                </h3>
                <p class="text-sm opacity-90 leading-relaxed">
                    Tingkatkan SEO dengan mengisi meta-description pada setiap post. Engagement rate naik hingga 30%!
                </p>
            </div>
        </div>
    </div>
</x-app-layout>