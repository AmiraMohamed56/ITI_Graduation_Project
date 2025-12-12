<x-guest-layout>
    <div class="fixed inset-0 overflow-hidden bg-gray-100">
        <!-- Centered Form -->
        <div class="relative z-10 w-full h-full flex items-center justify-center px-4">
            <div class="w-full max-w-4xl flex flex-col md:flex-row overflow-hidden rounded-3xl shadow-xl bg-white">

                <!-- Left Side Image -->
                <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-300 p-6 flex-col justify-center text-white relative overflow-hidden">
                    <img src="/images/login.jpg" alt="clinic illustration" class="absolute inset-0 w-full h-full object-cover opacity-30">
                    <div class="relative z-10">
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4">Welcome Back!</h1>
                        <p class="text-lg opacity-90">Clinic Management System</p>
                    </div>
                </div>

                <!-- Right Side Form -->
                <div class="w-full md:w-1/2 p-6 md:p-10 max-h-[90vh] overflow-y-auto">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800">Login</h2>
                    <p class="text-gray-600 mb-6">Welcome back! Please login to your account.</p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    
                    

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-800 mb-1 font-semibold">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="username@gmail.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-gray-800 mb-1 font-semibold">Password</label>
                            <input id="password" type="password" name="password"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="Enter your password">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="remember"
                                       class="form-checkbox text-blue-600 rounded">
                                <span class="text-gray-700 text-sm font-medium">Remember Me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500 transition">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold text-lg rounded-lg shadow transition">
                            Login
                        </button>
                        
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
