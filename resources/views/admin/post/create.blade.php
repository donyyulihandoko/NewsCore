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
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Write New Article</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Share your thoughts and news with the world.</p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        /* Sembunyikan tombol upload file di trix jika kamu belum siap handling storage */
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        .trix-content {
            min-height: 250px !important;
        }
    </style>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <div class="mb-5">
                            <label for="title"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Article
                                Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('title') border-red-500 @enderror"
                                placeholder="e.g. 10 Cara Belajar Laravel 11 dengan Cepat" required autofocus>
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="slug"
                                class="block mb-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Slug
                                URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" readonly
                                class="bg-gray-100 border border-gray-200 text-gray-500 text-xs rounded-lg block w-full p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-500 italic">
                        </div>

                        <div>
                            <label for="body"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Content</label>
                            <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                            <trix-editor input="body"
                                class="trix-content prose dark:prose-invert bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' :
                                    '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-6 border-gray-100 dark:border-gray-700">
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center transition-all shadow-lg shadow-blue-500/20">
                            Publish Article
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                            class="block mt-3 text-center text-sm font-medium text-gray-500 hover:text-gray-800 dark:hover:text-white transition-colors">
                            Save as Draft
                        </a>
                    </div>

                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                    <strong>Tips:</strong> Gunakan judul yang menarik (Clickbait yang jujur) untuk
                                    meningkatkan interaksi pembaca NewsCore Anda.
                                </p>
                            </div>
                        </div>
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

        // Mencegah upload file di Trix secara tidak sengaja
        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        });
    </script>
</x-admin-layout>