<!-- resources/views/livewire/partials/navbar.blade.php -->
<header class="sticky top-0 z-50 w-full bg-naas-cream border-b border-naas-cream-dark">
    <!-- Top Bar -->
    <div class="bg-naas-green text-white text-xs py-2 text-center tracking-wider font-light">
        FREE SHIPPING IN PAKISTAN ON ORDERS OVER RS. 10,000
    </div>

    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8" aria-label="Global">
        <div class="relative flex items-center justify-between h-20">
            <!-- Logo -->
           <a href="/" class="flex items-center flex-none">
                <img
                    src="https://naasshopping.com/images/naas-logo.jpeg"
                    alt="NAAS"
                    class="h-12 md:h-14 w-auto"
                >
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                @foreach($menuCategories ?? [] as $category)
                <div class="relative group">
                    <a href="/products?category={{ $category->slug }}" 
                       class="text-sm tracking-wider text-naas-dark hover:text-naas-terracotta transition py-8 inline-flex items-center">
                        {{ strtoupper($category->name) }}
                        @if($category->children->count() > 0)
                        <svg class="w-3 h-3 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        @endif
                    </a>
                    
                    @if($category->children->count() > 0)
                    <div class="absolute top-full left-0 w-56 bg-white shadow-lg border border-naas-cream-dark opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <div class="py-2">
                            @foreach($category->children as $child)
                            <a href="/products?category={{ $child->slug }}" 
                               class="block px-4 py-3 text-sm tracking-wider text-naas-dark hover:bg-naas-cream hover:text-naas-terracotta transition">
                                {{ strtoupper($child->name) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                <a href="{{ route('blog.index') }}"
                   class="text-sm tracking-wider text-naas-dark hover:text-naas-terracotta transition py-8 inline-flex items-center">
                    BLOG
                </a>
            </div>

            <!-- Right Icons -->
            <div class="flex items-center space-x-5">
                <!-- Search -->
                <button type="button" wire:click="openSearch" class="text-naas-dark hover:text-naas-terracotta transition" aria-label="Search products">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- User -->
                @guest
                <a href="/login" class="text-naas-dark hover:text-naas-terracotta transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                @endguest

                @auth
                <div class="hs-dropdown relative inline-flex" data-hs-dropdown>
                    <button type="button" class="flex items-center text-sm tracking-wider text-naas-dark hover:text-naas-terracotta transition">
                        {{ strtoupper(auth()->user()->name) }}
                        <svg class="ms-2 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="hs-dropdown-menu hidden absolute z-10 mt-2 w-48 bg-white border border-naas-cream-dark shadow-lg">
                        <a href="{{ route('my-orders') }}" class="block px-4 py-3 text-sm tracking-wider text-naas-dark hover:bg-naas-cream hover:text-naas-terracotta transition">MY ORDERS</a>
                        <a href="#" class="block px-4 py-3 text-sm tracking-wider text-naas-dark hover:bg-naas-cream hover:text-naas-terracotta transition">MY ACCOUNT</a>
                        <a href="{{ route('logout') }}" class="block px-4 py-3 text-sm tracking-wider text-naas-dark hover:bg-naas-cream hover:text-naas-terracotta transition">LOGOUT</a>
                    </div>
                </div>
                @endauth

                <!-- Cart -->
                <a href="/cart" class="relative text-naas-dark hover:text-naas-terracotta transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-naas-terracotta text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-medium">
                        {{ $total_count }}
                    </span>
                </a>
            </div>

            <!-- Mobile Toggle -->
            <div class="md:hidden">
                <button type="button" class="hs-collapse-toggle text-naas-dark hover:text-naas-terracotta transition" data-hs-collapse="#navbar-collapse-with-animation">
                    <svg class="hs-collapse-open:hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="hs-collapse-open:block hidden w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="navbar-collapse-with-animation" class="hs-collapse hidden md:hidden border-t border-naas-cream-dark">
            <div class="py-4 space-y-2">
                @foreach($menuCategories ?? [] as $category)
                <div class="border-b border-naas-cream-dark">
                    <a href="/products?category={{ $category->slug }}" class="block py-3 text-sm tracking-wider text-naas-dark hover:text-naas-terracotta">
                        {{ strtoupper($category->name) }}
                    </a>
                    @if($category->children->count() > 0)
                    <div class="pl-4 pb-2 space-y-1">
                        @foreach($category->children as $child)
                        <a href="/products?category={{ $child->slug }}" class="block py-2 text-xs tracking-wider text-naas-gray-warm hover:text-naas-terracotta">
                            {{ strtoupper($child->name) }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                <div class="border-b border-naas-cream-dark">
                    <a href="{{ route('blog.index') }}" class="block py-3 text-sm tracking-wider text-naas-dark hover:text-naas-terracotta">BLOG</a>
                </div>
            </div>
        </div>
    </nav>

    @if($showSearch)
        <div class="fixed inset-0 z-[70] bg-black/45" wire:click.self="closeSearch" wire:keydown.escape.window="closeSearch">
            <div class="bg-naas-cream shadow-2xl border-t border-naas-cream-dark">
                <div class="max-w-4xl mx-auto px-4 md:px-6 py-7">
                    <form wire:submit="submitSearch" class="flex items-center gap-3 border-b-2 border-naas-green pb-3">
                        <svg class="w-6 h-6 text-naas-green flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="search" autofocus autocomplete="off" placeholder="Search products, categories or brands..." class="w-full border-0 bg-transparent p-0 text-lg md:text-2xl font-serif text-naas-dark placeholder:text-naas-gray-warm focus:ring-0">
                        <button type="button" wire:click="closeSearch" class="p-2 text-naas-dark hover:text-naas-terracotta" aria-label="Close search">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </form>

                    <div class="pt-5" wire:loading.class="opacity-50" wire:target="search">
                        @if(mb_strlen(trim($search)) < 2)
                            <p class="text-sm text-naas-gray-warm">Type at least 2 characters to search.</p>
                        @elseif($searchResults->isEmpty())
                            <p class="text-sm text-naas-gray-warm py-5">No products found for “{{ $search }}”.</p>
                        @else
                            <div class="grid sm:grid-cols-2 gap-3 max-h-[55vh] overflow-y-auto">
                                @foreach($searchResults as $product)
                                    @php($searchImage = is_array($product->images) ? ($product->images[0] ?? null) : null)
                                    <a href="{{ route('product-detail', $product->slug) }}" wire:key="search-product-{{ $product->id }}" class="flex gap-4 p-3 bg-white hover:bg-naas-cream-dark transition group">
                                        <div class="w-16 h-20 bg-naas-cream-dark flex-none overflow-hidden">
                                            @if($searchImage)<img src="{{ asset('storage/'.$searchImage) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">@endif
                                        </div>
                                        <div class="min-w-0 self-center">
                                            <p class="font-serif text-lg truncate group-hover:text-naas-terracotta transition">{{ $product->name }}</p>
                                            <p class="text-xs text-naas-gray-warm mt-1">PKR {{ number_format($product->price) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <button type="button" wire:click="submitSearch" class="mt-5 text-xs tracking-[0.15em] border-b border-naas-dark pb-1 hover:text-naas-terracotta hover:border-naas-terracotta">VIEW ALL RESULTS</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</header>
