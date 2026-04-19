<x-author-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('author.posts.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Article</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update your article content.</p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        .trix-content {
            min-height: 250px !important;
        }
    </style>

    <div class="max-w-6xl mx-auto py-8">
        <form action="{{ route('author.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="mb-5">
                            <label for="title"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Article
                                Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('title') border-red-500 @enderror"
                                required>
                            @error('title') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="slug"
                                class="block mb-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Slug
                                URL</label>

                        <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" readonly
                                class="bg-gray-100 border border-gray-200 text-gray-500 text-xs rounded-lg block w-full p-2 dark:bg-gray-900 dark:border-gray-700 italic cursor-not-allowed">
                            </div>

                            @error('slug') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

                        <div>
                            <label for="body"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Content</label>
                            <input id="body" type="hidden" name="body" value="{{ old('body', $post->body) }}">
                            <trix-editor input="body"
                                class="trix-content prose dark:prose-invert bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500">
                            </trix-editor>
                            @error('body') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div x-data="{ imagePreview: '{{ $post->image ? asset('storage/' . $post->image) : null }}' }"
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <label class="block mb-4 text-sm font-semibold text-gray-900 dark:text-white">Featured
                            Image</label>

                        <div @click="$refs.fileInput.click()" class="cursor-pointer">
                            <div x-show="!imagePreview"
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl h-40 flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <span class="text-xs text-gray-500">Click to change image</span>
                            </div>
                            <img x-show="imagePreview" :src="imagePreview"
                                class="w-full h-40 object-cover rounded-xl shadow-md" />
                        </div>
                        <input type="file" name="image" id="image" class="hidden" x-ref="fileInput" accept="image/*"
                            @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        <p class="text-[10px] text-gray-400 mt-2">Leave empty to keep the current image.</p>
                        @error('image') <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="mb-5">
                            <label for="category_id"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Category</label>
                            <select name="category_id" id="category_id"
                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-3 dark:bg-gray-700 dark:border-gray-600">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) ==
                                    $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all shadow-lg shadow-blue-500/20">
                            Save Changes
                        </button>
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

        document.addEventListener('trix-file-accept', function(e) { e.preventDefault(); });
    </script>
</x-author-layout>