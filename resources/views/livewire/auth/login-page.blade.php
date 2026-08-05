<div class="bg-naas-cream px-4 py-4">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-6">

                {{-- Heading --}}
                <div class="text-center mb-4">
                    <h1 class="text-2xl md:text-3xl font-serif text-naas-green">
                        Sign In
                    </h1>
                    <p class="mt-1 text-gray-500 text-sm">
                        Welcome back to NAASSHOPPING Premium Fashion
                    </p>
                    <p class="mt-1 text-xs text-gray-600">
                        Don't have an account?
                        <a wire:navigate href="/register" class="text-naas-terracotta hover:text-naas-green transition font-medium">
                            Sign up here
                        </a>
                    </p>
                </div>

                {{-- Divider --}}
                <div class="flex items-center mb-4">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="px-3 text-[10px] uppercase tracking-widest text-gray-400">
                        Login
                    </span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                {{-- Error Alert --}}
                @if (session()->has('error'))
                <div class="mb-3 bg-red-500 text-xs text-white rounded-lg p-3" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form wire:submit.prevent="save" class="space-y-3">

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <div class="relative">
                            <input
                                type="email"
                                wire:model="email"
                                placeholder="Enter Your Email Address Here"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                                outline-none transition">
                            @error('email')
                            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('email')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-medium text-gray-700">
                                Password
                            </label>
                            <a wire:navigate href="{{ route('password.request') }}" class="text-[10px] text-naas-terracotta hover:text-naas-green transition font-medium">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input
                                type="password"
                                wire:model="password"
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                                outline-none transition">
                            @error('password')
                            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('password')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sign In Button --}}
                    <button
                        type="submit"
                        class="w-full bg-naas-green hover:bg-naas-terracotta text-white py-2.5 rounded-lg font-semibold tracking-wide text-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        Sign In
                    </button>

                </form>

                {{-- OR --}}
                <div class="flex items-center my-4">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="px-3 text-xs text-gray-400">OR</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                {{-- Google Login --}}
                <a
                    href="{{ route('google.login') }}"
                    class="w-full flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm font-medium text-gray-700 transition-all duration-300 hover:border-naas-green hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                    <span>Continue with Google</span>
                </a>

            </div>
        </div>
    </div>
</div>