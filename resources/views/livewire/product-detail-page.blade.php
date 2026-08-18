@push('meta')
    @php
        $schemaImages = collect($product->images ?? [])
            ->filter()
            ->map(fn ($image) => asset('storage/'.$image))
            ->values()
            ->all();
        $productUrl = route('product-detail', $product->slug);
        $productTitle = $product->name.' - NAAS Shopping';
        $productDescription = \Illuminate\Support\Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags(\Illuminate\Support\Str::markdown($product->description ?? '')))),
            160,
            ''
        );
        $productImage = $schemaImages[0] ?? asset('images/naas-logo.jpeg');

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $schemaImages ?: [$productImage],
            'description' => $productDescription,
            'sku' => 'NAAS-'.$product->id,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? 'NAAS Shopping',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'priceCurrency' => 'PKR',
                'availability' => $product->in_stock
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'url' => $productUrl,
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'NAAS Shopping',
                ],
            ],
        ];
        $productBreadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('products')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => $productUrl],
            ],
        ];
    @endphp
    <x-seo-meta
        :title="$productTitle"
        :description="$productDescription"
        :canonical="$productUrl"
        :image="$productImage"
        type="product"
    />
    <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($productBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
       @php
            $allImages = $product->images ?? [];
            $mainImage = $allImages[0] ?? null;
        @endphp

    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="/products" class="hover:text-[#1a3c34] transition">Shop</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">{{ $product->name }}</li>
        </ol>
    </nav>

    <section class="overflow-hidden bg-white py-11 font-poppins">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
            <div class="flex flex-wrap -mx-4">
                <!-- Product Images -->
                <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ $mainImage ? asset('storage/' . $mainImage) : '' }}' }">
                    <div class="md:sticky md:top-32 z-0 overflow-hidden">
                        <div class="relative mb-6 lg:mb-10 bg-[#FAF9F6] aspect-[4/5] max-h-[75vh]">
                            <img x-bind:src="mainImage" 
                                 alt="{{ $product->name }}" 
                                 class="object-contain w-full h-full"
                                 onerror="this.style.display='none'">
                        </div>
                        <div class="flex-wrap hidden md:flex">
                            @if(count($allImages) > 0)
                            @foreach($allImages as $image)
                            @php $imageUrl = asset('storage/' . $image); @endphp
                            <div class="w-1/2 p-2 sm:w-1/4" 
                                 x-on:click="mainImage='{{ $imageUrl }}'"
                                 :class="mainImage === '{{ $imageUrl }}' ? 'ring-2 ring-[#1a3c34]' : ''">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $product->name }}" 
                                     class="object-cover w-full lg:h-20 cursor-pointer hover:opacity-80 transition duration-300">
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <!-- Free Shipping -->
                        <div class="px-6 pb-6 mt-6 border-t border-gray-200">
                            <div class="flex flex-wrap items-center mt-6">
                                <span class="mr-3 text-[#1a3c34]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 bi bi-truck" viewBox="0 0 16 16">
                                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                        </path>
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-sm font-medium text-[#1a3c34] tracking-wider uppercase">Free Shipping</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">On orders over Rs. 10,000</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Product Info -->
                <div class="w-full px-4 md:w-1/2">
                    <div class="lg:pl-20">
                        <div class="mb-8 [&ul]:list-disc [&ul]:ml-4">
                            <h2 class="font-serif text-3xl md:text-4xl text-[#1a3c34] mb-4">
                                {{ $product->name }}
                            </h2>

                            <p class="mb-6">
                                <span class="font-serif text-3xl text-[#1a3c34]">{{ Number::currency($product->price,'PKR',true) }}</span>
                                @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-base text-gray-400 line-through ml-3">{{ Number::currency($product->compare_price,'PKR',true) }}</span>
                                @endif
                            </p>

                            <div class="max-w-md text-gray-600 text-sm leading-relaxed">
                                {!! Str::markdown($product->description) !!}
                            </div>
                        </div>
                        <!-- Quantity Section -->
                        <div class="w-32 mb-8">
                            <label class="w-full pb-2 text-xs tracking-widest uppercase text-gray-500 border-b border-gray-200 block mb-4">
                                Quantity
                            </label>
                            <div class="relative flex flex-row w-full h-12 mt-2 bg-transparent">
                                <button wire:click="decreaseQuantity" 
                                        class="w-12 h-full text-[#1a3c34] bg-[#f5f0e8] border border-gray-200 outline-none cursor-pointer hover:bg-[#1a3c34] hover:text-white transition duration-300 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                    value="{{ $quantity }}" 
                                    readonly 
                                    class="flex items-center w-full font-medium text-center text-[#1a3c34] bg-[#f5f0e8] outline-none border-y border-gray-200 focus:outline-none text-md" 
                                    placeholder="1">
                                <button wire:click="increaseQuantity" 
                                        class="w-12 h-full text-[#1a3c34] bg-[#f5f0e8] border border-gray-200 outline-none cursor-pointer hover:bg-[#1a3c34] hover:text-white transition duration-300 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Purchase Actions -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" wire:click="addToCart({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }}),buyNow({{ $product->id }})"
                                    @disabled(! $product->in_stock)
                                    class="w-full py-4 bg-[#1a3c34] text-white text-sm tracking-widest uppercase hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition duration-300">
                                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Bag</span>
                                <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                            </button>

                            <button type="button" wire:click="buyNow({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }}),buyNow({{ $product->id }})"
                                    @disabled(! $product->in_stock)
                                    class="w-full py-4 bg-[#C65D3B] text-white text-sm tracking-widest uppercase hover:bg-[#a94d30] disabled:opacity-50 disabled:cursor-not-allowed transition duration-300">
                                <span wire:loading.remove wire:target="buyNow({{ $product->id }})">Buy Now</span>
                                <span wire:loading wire:target="buyNow({{ $product->id }})">Redirecting...</span>
                            </button>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('products') }}"
                               class="text-xs tracking-widest uppercase text-gray-500 hover:text-[#1a3c34] transition border-b border-transparent hover:border-[#1a3c34] pb-1">
                                Continue Shopping
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="flex items-center space-x-6 mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-[#1a3c34]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>7-day exchange</span>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-[#1a3c34]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>Crafted in PK</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Related Products -->
    @if(count($relatedProducts) > 0)
    <section class="py-16 bg-[#FAF9F6]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-xs tracking-[0.2em] text-[#C65D3B] mb-2 uppercase">You May Also Like</p>
                <h2 class="font-serif text-3xl md:text-4xl text-[#1B4332]">Related Products</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <a href="/products/{{ $related->slug }}" class="group" wire:key="related-{{ $related->id }}">
                    <div class="relative overflow-hidden bg-[#FAF9F6] mb-4">
                       @php
    $images = $related->images;
    if (is_string($images)) {
        $images = json_decode($images, true);
    }
    if (!is_array($images)) {
        $images = [$images];
    }
    $firstImage = $images[0] ?? null;
@endphp
                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://via.placeholder.com/400x500?text=No+Image' }}" 
                             alt="{{ $related->name }}" 
                             class="w-full h-[350px] object-cover group-hover:scale-105 transition duration-700"
                             onerror="this.style.display='none'">
                        
                        @if($related->on_sale || $related->compare_price > $related->price)
                        <span class="absolute top-4 left-4 bg-[#C65D3B] text-white text-xs tracking-wider px-3 py-1">SALE</span>
                        @endif
                    </div>
                    <h3 class="font-serif text-lg mb-1 group-hover:text-[#C65D3B] transition">{{ $related->name }}</h3>
                    <p class="text-gray-500 text-sm">
                        @if($related->compare_price && $related->compare_price > $related->price)
                            <span class="text-gray-400 line-through mr-2">{{ Number::currency($related->compare_price,'PKR',true) }}</span>
                        @endif
                        {{ Number::currency($related->price,'PKR',true) }}
                    </p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    </section>
</div>
