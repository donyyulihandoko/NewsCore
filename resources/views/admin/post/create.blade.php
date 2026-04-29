<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Article</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage and publish content for your platform.</p>
            </div>
        </div>
    </x-slot>

    {{-- Trix Styles --}}
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
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Column --}}
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="mb-5">
                            <label for="title"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Article
                                Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('title') border-red-500 @enderror"
                                placeholder="e.g. Breaking News: Laravel 12 Released" required autofocus>
                            @error('title') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="slug"
                                class="block mb-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Slug
                                URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" readonly
                                class="bg-gray-100 border border-gray-200 text-gray-500 text-xs rounded-lg block w-full p-2 dark:bg-gray-900 dark:border-gray-700 italic cursor-not-allowed">
                        </div>

                        <div>
                            <label for="body"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Content</label>
                            <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                            <trix-editor input="body"
                                class="trix-content prose dark:prose-invert bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500">
                            </trix-editor>
                            @error('body') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Sidebar Column --}}
                <div class="space-y-6">
                    {{-- Featured Image Box --}}
                    <div x-data="{ imagePreview: null }"
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <label class="block mb-4 text-sm font-semibold text-gray-900 dark:text-white">Featured
                            Image</label>

                        <div class="relative group cursor-pointer" @click="$refs.fileInput.click()">
                            <div x-show="!imagePreview"
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl h-40 flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-xs text-gray-500 mt-2">Click to upload</span>
                            </div>

                            {{-- Image Preview --}}
                            <div x-show="imagePreview" class="relative">
                                <img :src="imagePreview" class="w-full h-40 object-cover rounded-xl shadow-md" />
                                <button type="button" @click.stop="imagePreview = null; $refs.fileInput.value = ''"
                                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <input type="file" name="image" id="image" class="hidden" x-ref="fileInput" accept="image/*"
                            @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        @error('image') <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Category & Action --}}
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="mb-5">
                            <label for="category_id"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Category</label>
                            <select name="category_id" id="category_id"
                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-3 dark:bg-gray-700 dark:border-gray-600">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' :
                                    '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all shadow-lg shadow-blue-500/20">
                            Publish Article
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Scripts --}}
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
</x-app-layout>