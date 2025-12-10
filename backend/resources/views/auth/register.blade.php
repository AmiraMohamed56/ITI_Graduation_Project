<x-guest-layout>
    <div class="fixed inset-0 overflow-hidden bg-gray-100">
        <!-- Centered Form -->
        <div class="relative z-10 w-full h-full flex items-center justify-center px-4">
            <div class="w-full max-w-4xl flex flex-col md:flex-row overflow-hidden rounded-3xl shadow-xl bg-white">

                <!-- Left Side Image -->
                <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-300 p-6 flex-col justify-center text-white relative overflow-hidden">
                    <img src="/images/login.jpg" alt="clinic illustration" class="absolute inset-0 w-full h-full object-cover opacity-30">
                    <div class="relative z-10">
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4">Welcome!</h1>
                        <p class="text-lg opacity-90">Clinic Management System</p>
                    </div>
                </div>

                <!-- Right Side Form -->
                <div class="w-full md:w-1/2 p-6 md:p-10 max-h-[90vh] overflow-y-auto">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800">Register</h2>
                    <p class="text-gray-600 mb-6">Create your account to get started.</p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-gray-800 mb-1 font-semibold">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="Your full name">
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-800 mb-1 font-semibold">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="you@example.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-gray-800 mb-1 font-semibold">Password</label>
                            <input id="password" type="password" name="password"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="Enter password">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-gray-800 mb-1 font-semibold">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="w-full border border-gray-300 rounded-lg p-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="Confirm password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm"/>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold text-lg rounded-lg shadow transition">
                            Register
                        </button>

                        <!-- Login Link -->
                        <p class="text-center mt-6 text-sm text-gray-700">
                            Already registered? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login</a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
