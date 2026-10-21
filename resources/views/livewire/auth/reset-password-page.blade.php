<div class="bg-naas-cream px-4 py-4">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-6">

                {{-- Heading --}}
                <div class="text-center mb-4">
                    <h1 class="text-2xl md:text-3xl font-serif text-naas-green">
                        Reset Password
                    </h1>
                    <p class="mt-1 text-gray-500 text-sm">
                        Create a new password for your account
                    </p>
                </div>

                {{-- Divider --}}
                <div class="flex items-center mb-4">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="px-3 text-[10px] uppercase tracking-widest text-gray-400">
                        New Password
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

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            New Password
                        </label>
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

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                wire:model="password_confirmation"
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:ring-2 focus:ring-naas-terracotta/30 focus:border-naas-terracotta
                                outline-none transition">
                            @error('password_confirmation')
                            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('password_confirmation')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Save Button --}}
                    <button
                        type="submit"
                        class="w-full bg-naas-green hover:bg-naas-terracotta text-white py-2.5 rounded-lg font-semibold tracking-wide text-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        Save Password
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>