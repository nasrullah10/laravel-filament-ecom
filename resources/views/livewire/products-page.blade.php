<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">Shop</li>
        </ol>
    </nav>

    <h1 class="font-serif text-4xl md:text-5xl text-[#1a3c34] mb-8">
        Shop
    </h1>

    <section class="py-10 bg-white font-poppins">
        <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                <!-- Sidebar Filters -->
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    <!-- Categories -->
                    <div class="p-4 mb-5 bg-white border-0">
                        <h2 class="font-serif text-xl text-[#1a3c34] mb-4">Categories</h2>
                        <div class="w-12 pb-2 mb-6 border-b border-[#1a3c34]"></div>
                        <ul>
                            @foreach($categories as $category)
                            <li class="mb-3" wire:key="{{ $category->id }}">
                                <label for="{{ $category->slug }}" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           wire:model.live="selected_categories" 
                                           id="{{ $category->slug }}" 
                                           value="{{ $category->id }}" 
                                           class="w-4 h-4 text-[#1a3c34] border-gray-300 rounded focus:ring-[#1a3c34]">
                                    <span class="text-sm text-gray-600 group-hover:text-[#1a3c34] transition ml-3">{{ $category->name }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Brands -->
                    <div class="p-4 mb-5 bg-white border-0">
                        <h2 class="font-serif text-xl text-[#1a3c34] mb-4">Brand</h2>
                        <div class="w-12 pb-2 mb-6 border-b border-[#1a3c34]"></div>
                        <ul>
                            @foreach($brands as $brand)
                            <li class="mb-3" wire:key="{{ $brand->id }}">
                                <label for="{{ $brand->slug }}" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           wire:model.live="selected_brands" 
                                           id="{{ $brand->slug }}" 
                                           value="{{ $brand->id }}" 
                                           class="w-4 h-4 text-[#1a3c34] border-gray-300 rounded focus:ring-[#1a3c34]">
                                    <span class="text-sm text-gray-600 group-hover:text-[#1a3c34] transition ml-3">{{ $brand->name }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Product Status -->
                    <div class="p-4 mb-5 bg-white border-0">
                        <h2 class="font-serif text-xl text-[#1a3c34] mb-4">Product Status</h2>
                        <div class="w-12 pb-2 mb-6 border-b border-[#1a3c34]"></div>
                        <ul>
                            <li class="mb-3">
                                <label for="featured" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           wire:model.live="featured" 
                                           id="featured" 
                                           value="1" 
                                           class="w-4 h-4 text-[#1a3c34] border-gray-300 rounded focus:ring-[#1a3c34]">
                                    <span class="text-sm text-gray-600 group-hover:text-[#1a3c34] transition ml-3">Featured</span>
                                </label>
                            </li>
                            <li class="mb-3">
                                <label for="on_sale" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           wire:model.live="on_sale" 
                                           id="on_sale" 
                                           value="1" 
                                           class="w-4 h-4 text-[#1a3c34] border-gray-300 rounded focus:ring-[#1a3c34]">
                                    <span class="text-sm text-gray-600 group-hover:text-[#1a3c34] transition ml-3">On Sale</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Price Range -->
                    <div class="p-4 mb-5 bg-white border-0">
                        <h2 class="font-serif text-xl text-[#1a3c34] mb-4">Price</h2>
                        <div class="w-12 pb-2 mb-6 border-b border-[#1a3c34]"></div>
                        <div>
                            <div class="font-medium text-sm text-[#1a3c34] mb-3">
                                {{ Number::currency($price_range,'PKR',true) }}
                            </div>
                            <input type="range" 
                                   wire:model.live="price_range" 
                                   class="w-full h-1 mb-4 bg-[#f5f0e8] appearance-none cursor-pointer accent-[#1a3c34]" 
                                   max="50000" 
                                   value="500" 
                                   step="500">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">
                                    {{ Number::currency(500,'PKR',true) }}
                                </span>
                            
                                <span class="text-xs text-gray-500">
                                    {{ Number::currency($price_range,'PKR',true) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="w-full px-3 lg:w-3/4">
                    <!-- Sort Bar -->
                    <div class="px-3 mb-6">
                        <div class="items-center justify-between hidden px-4 py-3 bg-[#f5f0e8] md:flex">
                            <div class="flex items-center justify-between">
                                <select wire:model.live="sort"  
                                        class="block w-40 text-sm bg-transparent cursor-pointer text-gray-600 focus:outline-none border-0">
                                    <option value="latest">Sort by Latest</option>
                                    <option value="price">Sort by Price</option>
                                </select>
                            </div>
                            <p class="text-xs text-gray-500">{{ $products->total() }} Products</p>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="flex flex-wrap items-center">
                        @if($products->count() == 0)
                        <div class="w-full py-16 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="font-serif text-xl text-[#1a3c34] mb-2">No Products Found</h3>
                            <p class="text-gray-500 text-sm mb-6">No products available in this category.</p>
                            <a href="/products" class="inline-block text-sm tracking-widest uppercase text-[#1a3c34] border-b border-[#1a3c34] pb-1 hover:opacity-70 transition">
                                View All Products
                            </a>
                        </div>
                        @endif
                        @foreach($products as $product)
                        <div class="w-full px-3 mb-8 sm:w-1/2 md:w-1/3" wire:key="{{ $product->id }}">
                            <div class="group border-0 bg-white hover:shadow-lg transition duration-500">
                                <div class="relative bg-[#f5f0e8] overflow-hidden">
                                    <a href="/products/{{ $product->slug }}" class="block">
                                        @php
                                            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                                            if (!is_array($images)) {
                                                $images = [$product->images ?? $product->image ?? null];
                                            }
                                            $firstImage = $images[0] ?? $product->image ?? null;
                                        @endphp
                                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://via.placeholder.com/400x500?text=No+Image' }}"
                                         alt="{{ $product->name }}"
                                         class="object-cover w-full h-72 mx-auto group-hover:scale-105 transition duration-700"
                                         onerror="this.src='https://via.placeholder.com/400x500?text=No+Image'">
                                    </a>
                                    
                                    @if($product->on_sale || $product->compare_price > $product->price)
                                    <span class="absolute top-3 left-3 bg-[#c75b39] text-white text-xs tracking-wider uppercase px-3 py-1">
                                        Sale
                                    </span>
                                    @endif
                                    
                                    @if($product->is_featured)
                                    <span class="absolute top-3 right-3 bg-[#1a3c34] text-white text-xs tracking-wider uppercase px-3 py-1">
                                        Featured
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <div class="mb-2">
                                        <h3 class="font-serif text-lg text-[#1a3c34] group-hover:text-[#c75b39] transition duration-300">
                                            {{ $product->name }}
                                        </h3>
                                    </div>
                                    <p class="text-sm">
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <span class="text-gray-400 line-through mr-2">{{ Number::currency($product->compare_price,'PKR',true) }}</span>
                                        @endif
                                        <span class="text-[#1a3c34] font-medium">{{ Number::currency($product->price,'PKR',true) }}</span>
                                    </p>
                                </div>
                                
                                <div class="flex justify-center p-4 border-t border-gray-100">
                                    <a wire:click.prevent="addToCart({{ $product->id }})" 
                                       href="#" 
                                       class="text-xs tracking-widest uppercase text-gray-500 flex items-center space-x-2 hover:text-[#1a3c34] transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4" viewBox="0 0 16 16">
                                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Bag</span>
                                        <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-end mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>