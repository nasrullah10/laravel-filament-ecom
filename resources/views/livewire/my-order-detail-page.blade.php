<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="/my-orders" class="hover:text-[#1a3c34] transition">My Orders</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">Order #{{ $order->id }}</li>
        </ol>
    </nav>

    <h1 class="font-serif text-4xl md:text-5xl text-[#1a3c34] mb-8">
        Order Details
    </h1>

    <!-- Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-5">
        <!-- Customer Card -->
        <div class="flex flex-col bg-white border-0">
            <div class="p-4 md:p-5 flex gap-x-4">
                <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-[#f5f0e8]">
                    <svg class="flex-shrink-0 size-5 text-[#1a3c34]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-widest uppercase text-gray-500">Customer</p>
                    </div>
                    <div class="mt-1 flex items-center gap-x-2">
                        <div class="text-sm font-medium text-[#1a3c34]">{{ $address->full_name }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Order Date Card -->
        <div class="flex flex-col bg-white border-0">
            <div class="p-4 md:p-5 flex gap-x-4">
                <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-[#f5f0e8]">
                    <svg class="flex-shrink-0 size-5 text-[#1a3c34]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 22h14" />
                        <path d="M5 2h14" />
                        <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
                        <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
                    </svg>
                </div>
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-widest uppercase text-gray-500">Order Date</p>
                    </div>
                    <div class="mt-1 flex items-center gap-x-2">
                        <h3 class="font-serif text-xl text-[#1a3c34]">{{ $order_items[0]->created_at->format('d-m-Y') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Order Status Card -->
        <div class="flex flex-col bg-white border-0">
            <div class="p-4 md:p-5 flex gap-x-4">
                <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-[#f5f0e8]">
                    <svg class="flex-shrink-0 size-5 text-[#1a3c34]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
                        <path d="m12 12 4 10 1.7-4.3L22 16Z" />
                    </svg>
                </div>
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-widest uppercase text-gray-500">Order Status</p>
                    </div>
                    <div class="mt-1 flex items-center gap-x-2">
                        @php
                            $status = '';
                            if ($order->status == 'new') {
                                $status = '<span class="bg-[#1a3c34] py-1.5 px-4 text-white text-xs tracking-wider uppercase">New</span>';
                            } 
                            if($order->status == 'processing') {
                                $status = '<span class="bg-[#c75b39] py-1.5 px-4 text-white text-xs tracking-wider uppercase">Processing</span>';
                            } 
                            if ($order->status == 'delivered') {
                                $status = '<span class="bg-[#1a3c34] bg-opacity-80 py-1.5 px-4 text-white text-xs tracking-wider uppercase">Delivered</span>';
                            } 
                            if ($order->status == 'cancelled') {
                                $status = '<span class="bg-gray-400 py-1.5 px-4 text-white text-xs tracking-wider uppercase">Cancelled</span>';
                            } 
                            if ($order->status == 'shipped') {
                                $status = '<span class="bg-[#8a8279] py-1.5 px-4 text-white text-xs tracking-wider uppercase">Shipped</span>';
                            } 
                        @endphp
                        {!! $status !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Payment Status Card -->
        <div class="flex flex-col bg-white border-0">
            <div class="p-4 md:p-5 flex gap-x-4">
                <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-[#f5f0e8]">
                    <svg class="flex-shrink-0 size-5 text-[#1a3c34]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z" />
                        <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                        <path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2" />
                        <path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2" />
                    </svg>
                </div>
                <div class="grow">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs tracking-widest uppercase text-gray-500">Payment Status</p>
                    </div>
                    <div class="mt-1 flex items-center gap-x-2">
                        @php 
                            $payment_status = '';
                            if ($order->payment_status == 'paid') {
                                $payment_status = '<span class="bg-[#1a3c34] py-1.5 px-4 text-white text-xs tracking-wider uppercase">Paid</span>';
                            }
                            if ($order->payment_status == 'pending') {
                                $payment_status = '<span class="bg-[#c75b39] py-1.5 px-4 text-white text-xs tracking-wider uppercase">Pending</span>';
                            }
                            if ($order->payment_status == 'failed') {
                                $payment_status = '<span class="bg-gray-400 py-1.5 px-4 text-white text-xs tracking-wider uppercase">Failed</span>';
                            }
                        @endphp
                        {!! $payment_status !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Grid -->

    <div class="flex flex-col md:flex-row gap-4 mt-8">
        <!-- Left Column -->
        <div class="md:w-3/4">
            <!-- Products Table -->
            <div class="bg-white p-6 mb-4">
                <h2 class="font-serif text-2xl text-[#1a3c34] mb-6">Order Items</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Product</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Price</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Quantity</th>
                            <th class="text-left text-xs tracking-widest uppercase text-gray-500 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order_items as $item)
                        <tr wire:key="{{ $item->id }}" class="border-b border-gray-100 hover:bg-[#f5f0e8] hover:bg-opacity-50 transition">
                            <td class="py-4">
                                <div class="flex items-center">
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
                                        <img class="h-16 w-16 mr-4 object-cover" 
                                             src="{{ url('storage', $firstImage) }}" 
                                             alt="{{ $item->product->name }}"
                                             onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">
                                    @else
                                        <div class="h-16 w-16 mr-4 bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-sm text-gray-600">{{ Number::currency($item->unit_amount, 'PKR') }}</td>
                            <td class="py-4 text-sm text-gray-600">
                                <span class="text-center w-8">{{ $item->quantity }}</span>
                            </td>
                            <td class="py-4 text-sm font-medium text-[#1a3c34]">{{ Number::currency($item->total_amount, 'PKR') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white p-6 mb-4">
                <h2 class="font-serif text-2xl text-[#1a3c34] mb-4">Shipping Address</h2>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-600">{{ $address->full_address }}, {{ $address->city }}, {{ $address->state??'' }}, {{ $address->zip_code??'' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs tracking-widest uppercase text-gray-500 mb-1">Phone</p>
                        <p class="text-sm font-medium text-[#1a3c34]">{{ $address->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="md:w-1/4">
            <div class="bg-[#f5f0e8] p-6">
                <h2 class="font-serif text-2xl text-[#1a3c34] mb-6">Summary</h2>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900">{{ Number::currency($order->grand_total, 'PKR') }}</span>
                </div>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Taxes</span>
                    <span class="font-medium text-gray-900">{{ Number::currency(0, 'PKR') }}</span>
                </div>
                <div class="flex justify-between mb-3 text-sm text-gray-600">
                    <span>Shipping</span>
                    <span class="font-medium text-[#1a3c34]">Free</span>
                </div>
                <div class="border-t border-gray-300 my-4"></div>
                <div class="flex justify-between">
                    <span class="font-serif text-lg text-[#1a3c34]">Grand Total</span>
                    <span class="font-serif text-xl text-[#1a3c34]">{{ Number::currency($order->grand_total, 'PKR') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>