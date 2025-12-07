<nav class="bg-white border-b border-gray-200 px-6 py-3 sticky top-0 z-40">
    <div class="flex justify-end items-center">
        <!-- Right Side Icons -->
        <div class="flex items-center space-x-4 ml-6">
            <!-- Notifications -->
            <button class="w-9 h-9 flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded relative">
                <i class="fas fa-bell"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Dark Mode Toggle -->
            <button class="w-9 h-9 flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded">
                <i class="fas fa-moon"></i>
            </button>

            <!-- User Profile -->
            <a href="{{ route('profile_setting.index') }}" class="flex items-center space-x-2">
                <img src="{{ Auth::user()->profile_picture_url }}"
                alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full border-2 border-gray-200">
            </a>
        </div>
    </div>
</nav>
