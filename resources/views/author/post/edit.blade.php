<x-app-layout>
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
                {{-- Kotak Gambar Utama ( handling lama vs baru) --}}
                <div x-data="{ 
                                        {{-- Tentukan sumber gambar awal: gambar lama (jika ada) atau null --}}
                                        imagePreview: '{{ Str::startsWith($post->image, 'http') ? $post->image : Storage::url($post->image) }}' 
                                    }"
                    class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:border-blue-300 dark:hover:border-blue-700 transition-colors group">
            
                    <label class="block mb-4 text-sm font-semibold text-gray-900 dark:text-white">Featured
                        Image</label>
            
                    {{-- Area Klik untuk Upload --}}
                    <div class="relative cursor-pointer" @click="$refs.fileInput.click()">
                        {{-- Tampilan Placeholder (jika tidak ada gambar sama sekali) --}}
                        <div x-show="!imagePreview"
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl h-40 flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-xs text-gray-500 mt-2 group-hover:text-blue-600">Click to
                                upload</span>
                        </div>
            
                        {{-- Tampilan Preview (Gambar lama atau Gambar baru yang dipilih) --}}
                        <div x-show="imagePreview" class="relative group">
                            <img :src="imagePreview"
                                class="w-full h-40 object-cover rounded-xl shadow-md border border-gray-100 dark:border-gray-700" />
            
                            {{-- Overlay saat hover di atas preview --}}
                            <div
                                class="absolute inset-0 bg-black/50 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span
                                    class="text-xs font-semibold text-white bg-black/50 px-3 py-1.5 rounded-full backdrop-blur-sm">
                                    Change Image
                                </span>
                            </div>
                        </div>
                    </div>
            
                    {{-- Input File Tersembunyi --}}
                    <input type="file" name="image" id="image" class="hidden" x-ref="fileInput" accept="image/*" {{-- Saat file
                        dipilih, update imagePreview --}} @change="imagePreview = URL.createObjectURL($event.target.files[0])"
                        readonly>
            
                    @error('image') <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
            
                    @if($post->image)
                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-600 text-center italic">Current image: {{
                        basename($post->image) }}</p>
                    @endif
                </div>
            
                {{-- Kotak Kategori & Action --}}
                <div class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <div class="mb-5">
                        <label for="category_id"
                            class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-3 dark:bg-gray-700 dark:border-gray-600">
                            @foreach ($categories as $category)
                            {{-- Cek selected berdasarkan old value atau data asli database --}}
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) ==
                                $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
        
                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all shadow-lg shadow-blue-500/20">
                        Edit Article
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
</x-app-layout>