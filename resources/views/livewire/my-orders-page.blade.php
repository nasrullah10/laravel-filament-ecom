<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-xs tracking-widest text-gray-500 uppercase">
            <li><a href="/" class="hover:text-[#1a3c34] transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('products') }}" class="hover:text-[#1a3c34] transition">Shop</a></li>
            <li>/</li>
            <li class="text-[#1a3c34]">My Orders</li>
        </ol>
    </nav>

    <h1 class="font-serif text-4xl md:text-5xl text-[#1a3c34] mb-8">
        My Orders
    </h1>

    <div class="flex flex-col bg-white p-5 mt-4">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-[#f5f0e8]">
                                <th scope="col" class="px-6 py-4 text-start text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Order</th>
                                <th scope="col" class="px-6 py-4 text-start text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Date</th>
                                <th scope="col" class="px-6 py-4 text-start text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Order Status</th>
                                <th scope="col" class="px-6 py-4 text-start text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Payment Status</th>
                                <th scope="col" class="px-6 py-4 text-start text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Order Amount</th>
                                <th scope="col" class="px-6 py-4 text-end text-xs tracking-widest uppercase font-medium text-[#1a3c34]">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($latest_orders as $order)
                            @php
                                $status = '';
                                $payment_status = '';
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

                            <tr class="odd:bg-white even:bg-[#f5f0e8] even:bg-opacity-50 hover:bg-[#f5f0e8] transition" wire:key="{{ $order->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1a3c34]">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{!! $status !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{!! $payment_status !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1a3c34]">{{ Number::currency($order->grand_total, 'PKR') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <a href="/my-order-detail/{{ $order->id }}" class="inline-block bg-[#1a3c34] text-white py-2.5 px-6 text-xs tracking-widest uppercase hover:bg-opacity-90 transition">View Details</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">
                {{ $latest_orders->links() }}
            </div>
        </div>
    </div>
</div>