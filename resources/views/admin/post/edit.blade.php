<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Article</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Updating content for: <span class="italic">{{
                        $post->slug }}</span></p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        .trix-content {
            min-height: 300px !important;
        }
    </style>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Untuk update di Laravel --}}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <div class="mb-5">
                            <label for="title"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Article
                                Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('title') border-red-500 @enderror"
                                required>
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="slug"
                                class="block mb-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Slug
                                URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" readonly
                                class="bg-gray-100 border border-gray-200 text-gray-500 text-xs rounded-lg block w-full p-2 dark:bg-gray-900 dark:border-gray-700 italic">
                        </div>

                        <div>
                            <label for="body"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Content</label>
                            <input id="body" type="hidden" name="body" value="{{ old('body', $post->body) }}">
                            <trix-editor input="body"
                                class="trix-content prose dark:prose-invert bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg">
                            </trix-editor>
                            @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <div class="mb-5">
                            <label for="category_id"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Category</label>
                            <select name="category_id" id="category_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) ==
                                    $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-6 border-gray-100 dark:border-gray-700">

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg text-sm px-5 py-3 transition-all shadow-lg shadow-blue-500/20">
                            Update Article
                        </button>

                        <a href="{{ route('admin.posts.index') }}"
                            class="block mt-3 text-center text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                            Cancel Changes
                        </a>
                    </div>

                    <div
                        class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            <strong>Last Updated:</strong><br>
                            {{ $post->updated_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <strong>Author:</strong><br>
                            {{ $post->author->name }}
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <script>
        const title = document.querySelector('#title');
        const slug = document.querySelector('#slug');

        title.addEventListener('keyup', function() {
            let preslug = title.value;
            preslug = preslug.replace(/[^a-zA-Z0-9\s]/g,"");
            preslug = preslug.replace(/\s+/g, '-');
            slug.value = preslug.toLowerCase();
        });

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        });
    </script>
</x-admin-layout>