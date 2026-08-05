<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">Checkout</li>
        </ol>
    </nav>

    <h1 class="font-serif text-4xl md:text-5xl text-[#1a3c34] mb-8">
        Checkout
    </h1>

    <form wire:submit.prevent="placeOrder" class="space-y-6">
        <div class="grid grid-cols-12 gap-8">
            <!-- Left Column - Forms -->
            <div class="lg:col-span-7 col-span-12">
                <!-- Card -->
                <div class="bg-white p-4 sm:p-7">
                    <!-- Shipping Address -->
                    <div class="mb-8">
                        <h2 class="font-serif text-2xl text-[#1a3c34] mb-6">
                            Shipping Address
                        </h2>
                        <!-- Contact Section mein yeh add karein -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="first_name">
            First Name *
        </label>
        <input wire:model="first_name" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('first_name') border-red-500 @enderror" id="first_name" type="text">
        @error('first_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
    <div>
        <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="last_name">
            Last Name *
        </label>
        <input wire:model="last_name" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('last_name') border-red-500 @enderror" id="last_name" type="text">
        @error('last_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
    </div>
</div>

<!-- Email: Sirf Guest ke liye -->
@guest
<div class="mt-6">
    <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="email">
        Email *
    </label>
    <input wire:model="email" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('email') border-red-500 @enderror" id="email" type="email" placeholder="your@email.com">
    @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
    <p class="text-xs text-gray-400 mt-1">Order confirmation will be sent here</p>
</div>
@endguest

<div class="mt-6">
    <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="phone">
        Phone *
    </label>
    <input wire:model="phone" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('phone') border-red-500 @enderror" id="phone" type="text">
    @error('phone') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
</div>
                        <div class="mt-6">
                            <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="address">
                                Address
                            </label>
                            <input wire:model="street_address" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('street_address') border-red-500 @enderror" id="address" type="text">
                            <div class="text-red-500 text-xs mt-1">
                                @error('street_address')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-xs tracking-widest text-gray-600 uppercase mb-2" for="city">
                                City
                            </label>
                            <input wire:model="city" class="w-full border-b border-gray-300 py-3 px-0 bg-transparent focus:outline-none focus:border-[#1a3c34] transition @error('city') border-red-500 @enderror" id="city" type="text">
                            <div class="text-red-500 text-xs mt-1">
                                @error('city')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                       
                    </div>
                    
                    <div class="font-serif text-2xl text-[#1a3c34] mb-6">
                        Select Payment Method
                    </div>
                    
                    <ul class="grid w-full gap-4 md:grid-cols-2">
                        <li>
                            <input wire:model="payment_method" class="hidden peer" id="hosting-small" type="radio" checked value="cod" />
                            <label class="inline-flex items-center justify-between w-full p-5 text-gray-600 bg-[#f5f0e8] border border-gray-200 cursor-pointer peer-checked:border-[#1a3c34] peer-checked:text-[#1a3c34] hover:border-[#1a3c34] transition" for="hosting-small">
                                <div class="block">
                                    <div class="w-full text-sm font-medium tracking-wider uppercase">
                                        Cash on Delivery
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay when your order arrives</p>
                                </div>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </label>
                        </li>
                        
                    </ul>
                    <div class="text-red-500 text-xs mt-2">
                        @error('payment_method')
                            {{ $message }}
                        @enderror
                    </div>
                    
                    <div class="mt-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input wire:model="terms" type="checkbox" class="w-4 h-4 text-[#1a3c34] bg-gray-100 border-gray-300 rounded focus:ring-[#1a3c34]">
                            <span class="ms-2 text-sm text-gray-700">
                                I agree to the terms and conditions
                            </span>
                        </label>
                        <div class="text-red-500 text-xs mt-1">
                            @error('terms')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            
            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-5 col-span-12">
                <div class="bg-[#f5f0e8] p-6 sm:p-8 sticky top-6">
                    <div class="font-serif text-2xl text-[#1a3c34] mb-6">
                        ORDER SUMMARY
                    </div>
                    <div class="flex justify-between mb-3 text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-900">{{ Number::currency($sub_total, 'PKR') }}</span>
                    </div>
                    <div class="flex justify-between mb-3 text-sm text-gray-600">
                        <span>Taxes</span>
                        <span class="font-medium text-gray-900">{{ Number::currency(0, 'PKR') }}</span>
                    </div>
                    <div class="flex justify-between mb-3 text-sm text-gray-600">
                        <span>Shipping Cost</span>
                        <span class="font-medium text-[#1a3c34]">
                            @if($shipping_amount == 0)
                                Free
                            @else
                                {{ Number::currency($shipping_amount, 'PKR') }}
                            @endif
                        </span>
                    </div>
                    <div class="border-t border-gray-300 my-4"></div>
                    <div class="flex justify-between mb-2">
                        <span class="font-serif text-lg text-[#1a3c34]">Grand Total</span>
                        <span class="font-serif text-2xl text-[#1a3c34]">{{ Number::currency($grand_total, 'PKR') }}</span>
                    </div>
                </div>
                
                <button type="submit" class="bg-[#1a3c34] mt-4 w-full py-4 text-sm tracking-widest uppercase text-white hover:bg-opacity-90 transition">
                    <span wire:loading.remove wire:target="placeOrder">Place Order</span>
                    <span wire:loading wire:target="placeOrder">Processing...</span>
                </button>
                
                <!-- BASKET SUMMARY with Dynamic Images -->
                <div class="bg-[#f5f0e8] mt-4 p-6 sm:p-8">
                    <div class="font-serif text-2xl text-[#1a3c34] mb-6">
                        BASKET SUMMARY
                    </div>
                    <ul class="divide-y divide-gray-300" role="list">
                        @foreach($cart_items as $item)
                        <li class="py-4" wire:key="{{ $item['product_id'] ?? $item['id'] ?? $loop->index }}">
                            <div class="flex items-center">
                                <!-- Dynamic Product Image -->
                                <div class="flex-shrink-0">
                                    @php
                                        $product = \App\Models\Product::find($item['product_id'] ?? $item['id'] ?? null);
                                        $images = [];
                                        if ($product) {
                                            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                                            if (!is_array($images)) {
                                                $images = [$product->images ?? $product->image ?? null];
                                            }
                                        }
                                        $firstImage = $images[0] ?? $product->image ?? null;
                                    @endphp
                                    
                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage)  }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-14 h-16 object-cover"
                                             >
                                    @else
                                        <div class="w-14 h-16 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item['name'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Qty: {{ $item['quantity'] ?? $item['qty'] ?? 1 }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-sm font-medium text-gray-900">
                                    {{ Number::currency($item['total_amount'] ?? ($item['price'] * ($item['quantity'] ?? $item['qty'] ?? 1)), 'PKR') }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>