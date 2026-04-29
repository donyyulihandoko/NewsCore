<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create Category</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add a new category to organize your news.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="mb-5">
                            <label for="name"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Category
                                Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror"
                                placeholder="e.g. Technology" required autofocus>
                            @error('name') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-5">
                            <label for="slug"
                                class="block mb-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Slug
                                URL</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" readonly
                                class="bg-gray-100 border border-gray-200 text-gray-500 text-xs rounded-lg block w-full p-2 dark:bg-gray-900 dark:border-gray-700 italic cursor-not-allowed">
                        </div>

                        <div>
                            <label for="description"
                                class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Description</label>
                            <textarea name="description" id="description" rows="5"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div x-data="{ imagePreview: null }"
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <label class="block mb-4 text-sm font-semibold text-gray-900 dark:text-white">Category
                            Image</label>

                        <div @click="$refs.fileInput.click()" class="cursor-pointer">
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
                            <img x-show="imagePreview" :src="imagePreview"
                                class="w-full h-40 object-cover rounded-xl shadow-md" />
                        </div>
                        <input type="file" name="image" id="image" class="hidden" x-ref="fileInput" accept="image/*"
                            @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        @error('image') <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div
                        class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all shadow-lg shadow-blue-500/20">
                            Save Category
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');

        name.addEventListener('keyup', function() {
            let preslug = name.value;
            preslug = preslug.replace(/[^a-zA-Z0-9\s]/g,"");
            preslug = preslug.replace(/\s+/g, '-');
            slug.value = preslug.toLowerCase();
        });
    </script>
</x-app-layout>