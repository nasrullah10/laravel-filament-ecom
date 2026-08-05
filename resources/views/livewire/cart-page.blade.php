<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('products') }}" class="hover:text-[#1a3c34] transition">Shop</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">Shopping Cart</li>
        </ol>
    </nav>

    <h1 class="font-serif text-4xl md:text-5xl text-[#1a3c34] mb-8">
        Shopping Cart
    </h1>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Cart Items -->
        <div class="md:w-3/4">
            <div class="bg-white overflow-x-auto p-6 mb-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Product</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Price</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Quantity</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Total</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cart_items as $item)
                        <tr wire:key="{{ $item['product_id'] }}" class="border-b border-gray-100 hover:bg-[#f5f0e8] hover:bg-opacity-50 transition">
                            <td class="py-4">
                                <div class="flex items-center">
                                    @php
                                        $product = \App\Models\Product::find($item['product_id']);
                                        $images = [];
                                        if ($product) {
                                            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                                            if (!is_array($images)) {
                                                $images = [$product->images ?? $product->image ?? null];
                                            }
                                        }
                                        $firstImage = $images[0] ?? $product->image ?? null;
                                        // Fix encoded slashes from database
                                        $firstImage = str_replace(['\\/', '%2F'], '/', $firstImage);
                                    @endphp

                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="h-16 w-16 mr-4 object-cover"
                                             onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">
                                    @else
                                        <div class="h-16 w-16 mr-4 bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-sm text-gray-600">{{ Number::currency($item['unit_amount'],'PKR',true) }}</td>
                            <td class="py-4">
                                <div class="flex items-center">
                                    <button wire:click="decreaseQty({{ $item['product_id'] }})" 
                                            class="border border-gray-300 py-2 px-4 mr-2 hover:border-[#1a3c34] hover:text-[#1a3c34] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="text-center w-8 text-sm font-medium">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQty({{ $item['product_id'] }})" 
                                            class="border border-gray-300 py-2 px-4 ml-2 hover:border-[#1a3c34] hover:text-[#1a3c34] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="py-4 text-sm font-medium text-[#1a3c34]">{{ Number::currency($item['total_amount'],'PKR',true) }}</td>
                            <td class="py-4">
                                <button wire:click="removeItem({{ $item['product_id'] }})" 
                                        class="text-gray-400 hover:text-red-500 transition p-2">
                                    <span wire:loading.remove wire:target="removeItem({{ $item['product_id'] }})">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="removeItem({{ $item['product_id'] }})">Removing...</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Your cart is empty</p>
                                <a href="{{ route('products') }}" class="inline-block mt-4 text-[#1a3c34] text-sm tracking-widest uppercase border-b border-[#1a3c34] pb-1 hover:opacity-70 transition">Continue Shopping</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="md:w-1/4">
            <div class="bg-[#f5f0e8] p-6 sticky top-6">
                <h2 class="font-serif text-2xl text-[#1a3c34] mb-6">Summary</h2>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900">{{ Number::currency($sub_total,'PKR',true) }}</span>
                </div>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Taxes</span>
                    <span class="font-medium text-gray-900">{{ Number::currency(0,'PKR',true) }}</span>
                </div>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Shipping</span>
                    <span class="font-medium text-[#1a3c34]">
                        @if($shipping_amount == 0)
                            Free
                        @else
                            {{ Number::currency($shipping_amount,'PKR',true) }}
                        @endif
                    </span>
                </div>
                <div class="border-t border-gray-300 my-4"></div>
                <div class="flex justify-between">
                    <span class="font-serif text-lg text-[#1a3c34]">Grand Total</span>
                    <span class="font-serif text-xl text-[#1a3c34]">{{ Number::currency($grand_total,'PKR',true) }}</span>
                </div>

                @if($cart_items)
                <a href="/checkout" 
                   class="block text-center bg-[#1a3c34] text-white py-4 mt-6 text-sm tracking-widest uppercase hover:bg-opacity-90 transition">
                    Proceed to Checkout
                </a>
                <a href="{{ route('products') }}" 
                   class="block text-center text-[#1a3c34] py-3 mt-3 text-xs tracking-widest uppercase hover:opacity-70 transition">
                    Continue Shopping
                </a>
                @endif
            </div>
        </div>
    </div>
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="py-16 mt-12 bg-[#FAF9F6]">
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
</div>