<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('products') }}" class="hover:text-[#1a3c34] transition">Shop</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">Order Confirmation</li>
        </ol>
    </nav>

    <section class="flex items-center font-poppins">
        <div class="justify-center flex-1 max-w-6xl px-4 py-4 mx-auto bg-white border-0 md:py-10 md:px-10">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl text-[#1a3c34] mb-8">
                    Thank you. Your order has been received.
                </h1>

                <!-- Shipping Address -->
                <div class="flex border-b border-gray-200 items-stretch justify-start w-full h-full px-4 mb-8 md:flex-row xl:flex-col md:space-x-6 lg:space-x-8 xl:space-x-0">
                    <div class="flex items-start justify-start flex-shrink-0">
                        <div class="flex items-center justify-center w-full pb-6 space-x-4 md:justify-start">
                            <div class="flex flex-col items-start justify-start space-y-2">
                                <p class="text-lg font-medium leading-4 text-left text-gray-800">
                                    {{ $order->address?->first_name }}
                                </p>
                                <p class="text-sm leading-4 text-gray-500">{{ $order->address->street_address }}</p>
                                <p class="text-sm leading-4 text-gray-500">{{ $order->address->city }}, {{ $order->address->state }}, {{ $order->address->zip_code }}</p>
                                <p class="text-sm leading-4 text-gray-500">Phone: {{ $order->address->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Info Grid -->
                <div class="flex flex-wrap items-center pb-4 mb-10 border-b border-gray-200">
                    <div class="w-full px-4 mb-4 md:w-1/4">
                        <p class="mb-2 text-xs tracking-widest uppercase text-gray-500">
                            Order Number:
                        </p>
                        <p class="text-base font-medium leading-4 text-[#1a3c34]">
                            #{{ $order->id }}
                        </p>
                    </div>
                    <div class="w-full px-4 mb-4 md:w-1/4">
                        <p class="mb-2 text-xs tracking-widest uppercase text-gray-500">
                            Date:
                        </p>
                        <p class="text-base font-medium leading-4 text-[#1a3c34]">
                            {{ $order->created_at->format('d-m-Y') }}
                        </p>
                    </div>
                    <div class="w-full px-4 mb-4 md:w-1/4">
                        <p class="mb-2 text-xs tracking-widest uppercase text-gray-500">
                            Total:
                        </p>
                        <p class="text-base font-medium leading-4 text-[#1a3c34]">
                            {{ Number::currency($order->grand_total, 'PKR') }}
                        </p>
                    </div>
                    <div class="w-full px-4 mb-4 md:w-1/4">
                        <p class="mb-2 text-xs tracking-widest uppercase text-gray-500">
                            Payment Method:
                        </p>
                        <p class="text-base font-medium leading-4 text-[#1a3c34]">
                            {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Card' }}
                        </p>
                    </div>
                </div>

                <!-- Order Details & Shipping -->
                <div class="px-4 mb-10">
                    <div class="flex flex-col items-stretch justify-center w-full space-y-4 md:flex-row md:space-y-0 md:space-x-8">
                        
                        <!-- Order Details -->
                        <div class="flex flex-col w-full space-y-6">
                            <h2 class="font-serif text-2xl text-[#1a3c34] mb-2">Order details</h2>
                            <div class="flex flex-col items-center justify-center w-full pb-4 space-y-4 border-b border-gray-200">
                                <div class="flex justify-between w-full">
                                    <p class="text-base leading-4 text-gray-600">Subtotal</p>
                                    <p class="text-base leading-4 text-gray-900 font-medium">{{ Number::currency($order->grand_total - $order->shipping_amount, 'PKR') }}</p>
                                </div>
                                <div class="flex items-center justify-between w-full">
                                    <p class="text-base leading-4 text-gray-600">Discount</p>
                                    <p class="text-base leading-4 text-gray-900 font-medium">{{ Number::currency(0, 'PKR') }}</p>
                                </div>
                                <div class="flex items-center justify-between w-full">
                                    <p class="text-base leading-4 text-gray-600">Shipping</p>
                                    <p class="text-base leading-4 text-gray-900 font-medium">
                                        @if($order->shipping_amount == 0)
                                            Free
                                        @else
                                            {{ Number::currency($order->shipping_amount, 'PKR') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between w-full">
                                <p class="font-serif text-lg text-[#1a3c34]">Total</p>
                                <p class="font-serif text-xl text-[#1a3c34]">{{ Number::currency($order->grand_total, 'PKR') }}</p>
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div class="flex flex-col w-full px-2 space-y-4 md:px-8">
                            <h2 class="font-serif text-2xl text-[#1a3c34] mb-2">Shipping</h2>
                            <div class="flex items-start justify-between w-full">
                                <div class="flex items-center justify-center space-x-3">
                                    <div class="w-8 h-8 text-[#1a3c34]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6 bi bi-truck" viewBox="0 0 16 16">
                                            <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col items-center justify-start">
                                        <p class="text-base font-medium leading-6 text-gray-800">
                                            Delivery<br><span class="text-sm font-normal text-gray-500">Delivery with 24 Hours</span>
                                        </p>
                                    </div>
                                </div>
                                <p class="text-base font-medium leading-6 text-[#1a3c34]">{{ Number::currency(0, 'PKR') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items (if available) -->
                @if(isset($order->items) && $order->items->count() > 0)
                <div class="px-4 mb-10">
                    <h2 class="font-serif text-2xl text-[#1a3c34] mb-6">Items Ordered</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center py-4 border-b border-gray-100" wire:key="{{ $item->id }}">
                            <div class="flex-shrink-0">
                                @php
                                    $product = $item->product;
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
                                         alt="{{ $item->product_name }}" 
                                         class="w-16 h-20 object-cover"
                                         >
                                @else
                                    <div class="w-16 h-20 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">Qty: {{ $item->quantity }}</p>
                                @if($item->unit_amount != $item->total_amount)
                                    <p class="text-xs text-gray-500">{{ Number::currency($item->unit_amount, 'PKR') }} each</p>
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ Number::currency($item->total_amount, 'PKR') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Buttons -->
                <div class="flex items-center justify-start gap-4 px-4 mt-6">
                    <a href="{{ route('products') }}" class="w-full text-center px-6 py-3 text-[#1a3c34] border border-[#1a3c34] text-sm tracking-widest uppercase md:w-auto hover:text-white hover:bg-[#1a3c34] transition">
                        Go back shopping
                    </a>
                    <a href="{{ route('my-orders') }}" class="w-full text-center px-6 py-3 bg-[#1a3c34] text-white text-sm tracking-widest uppercase md:w-auto hover:bg-opacity-90 transition">
                        View My Orders
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>