<nav
    class="sticky top-0 z-50 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center space-x-10">
                <a href="{{ route('admin.dashboard.index') }}"
                    class="text-2xl font-black tracking-tighter text-blue-600 dark:text-blue-500">
                    NewsCore<span class="text-gray-900 dark:text-white">.</span>
                    <span
                        class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full ml-1 font-bold uppercase tracking-widest">Admin</span>
                </a>

                <div class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider">
                    <a href="{{ route('admin.dashboard.index') }}"
                        class="hover:text-blue-600 transition-colors {{ request()->routeIs('admin.dashboard.index') ? 'text-blue-600' : 'text-gray-500' }}">Dashboard</a>

                    <a href="{{ route('admin.posts.index') }}"
                        class="hover:text-blue-600 transition-colors {{ request()->routeIs('admin.posts.*') ? 'text-blue-600' : 'text-gray-500' }}">Posts</a>

                    <a href="{{ route('admin.pending.post') }}"
                        class="hover:text-blue-600 transition-colors {{ request()->routeIs('admin.pending.post') ? 'text-blue-600' : 'text-gray-500' }}">Pending Review</a>

                    <a href="{{ route('admin.categories.index') }}"
                        class="hover:text-blue-600 transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-500' }}">Categories</a>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <button id="userMenuClick" data-dropdown-toggle="userDropdown"
                    class="flex items-center space-x-3 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <img class="w-9 h-9 rounded-full border-2 border-white dark:border-gray-800 shadow-sm"
                        src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=E5E7EB&color=374151"
                        alt="Profile">
                    <span class="hidden sm:block font-bold text-sm">{{ Auth::user()->name }}</span>
                </button>

                <div id="userDropdown"
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-2xl shadow-2xl w-56 dark:bg-gray-800 dark:divide-gray-600 border border-gray-100 dark:border-gray-700">
                    <div class="px-4 py-4 text-sm">
                        <p class="font-bold text-gray-900 dark:text-white italic">Author Panel</p>
                    </div>
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                        <li><a href="{{ route('admin.dashboard.index') }}"
                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600">Dashboard</a>
                        </li>
                        <li><a href="{{ route('profile.edit') }}"
                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600">Profile
                                Settings</a>
                        </li>
                    </ul>
                    <div class="py-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 font-bold">Sign
                                out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>