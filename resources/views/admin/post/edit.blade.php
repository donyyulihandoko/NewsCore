<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.posts.index') }}"
                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Article Review & Approval</h2>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- BAGIAN KONTEN (READ-ONLY) --}}
        <div class="lg:col-span-2 space-y-6">
            <article
                class="p-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">

                {{-- Gambar (Tampilan statis) --}}
                @if($post->image)
                <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}" class="w-full aspect-[16/9] object-cover rounded-xl mb-6">
                @endif

                {{-- Judul (Teks biasa) --}}
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

                {{-- Meta Info --}}
                <div class="flex items-center space-x-4 mb-6 text-sm text-gray-500">
                    <span>Category: {{ $post->category->name }}</span>
                    <span>•</span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </div>

                <hr class="mb-8">

                {{-- Body (Render HTML statis) --}}
                <div class="prose prose-blue max-w-none dark:prose-invert">
                    {!! $post->body !!}
                </div>
            </article>
        </div>

        {{-- BAGIAN APPROVAL (FORM STATUS) --}}
        <div class="space-y-6">
            <form action= '{{ route('admin.posts.update', $post) }}' method="POST"
                class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                @csrf @method('PUT')

                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Approval Action</h3>

                <div class="flex items-center justify-between mb-6">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        {{ $post->is_published ? 'Published' : 'Draft' }}
                    </span>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $post->is_published
                        ? 'checked' : '' }}
                        onchange="this.form.submit()">
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>

                <p class="text-xs text-gray-500">
                    *Mengubah status di atas akan langsung tersimpan di database.
                </p>
            </form>

            {{-- Info Tambahan --}}
            <div class="p-6 bg-gray-900 rounded-2xl text-white">
                <h3 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4">System Log</h3>
                <p class="text-sm text-gray-400">Author: {{ $post->author->name }}</p>
                <p class="text-sm text-gray-400">Last updated: {{ $post->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</x-app-layout>