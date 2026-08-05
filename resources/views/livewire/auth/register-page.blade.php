<div class="bg-naas-cream px-4 py-4">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-6">

                {{-- Heading --}}
                <div class="text-center mb-4">
                    <h1 class="text-2xl md:text-3xl font-serif text-naas-green">
                        Create Account
                    </h1>
                    <p class="mt-1 text-gray-500 text-sm">
                        Join NAASSHOPPING Premium Fashion
                    </p>
                    <p class="mt-1 text-xs text-gray-600">
                        Already have an account?
                        <a wire:navigate href="/login" class="text-naas-terracotta hover:text-naas-green transition font-medium">
                            Sign In
                        </a>
                    </p>
                </div>

                {{-- Divider --}}
                <div class="flex items-center mb-4">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="px-3 text-[10px] uppercase tracking-widest text-gray-400">
                        Register
                    </span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <form wire:submit.prevent="save" class="space-y-3">

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Please Enter Your Name"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                            focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                            outline-none transition">
                        @error('name')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            wire:model="email"
                            placeholder="Please Enter Your Email Address Here"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                            focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                            outline-none transition">
                        @error('email')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input
                            type="password"
                            wire:model="password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                            focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                            outline-none transition">
                        @error('password')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Register Button --}}
                    <button
                        type="submit"
                        class="w-full bg-naas-green hover:bg-naas-terracotta text-white py-2.5 rounded-lg font-semibold tracking-wide text-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        Create Account
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