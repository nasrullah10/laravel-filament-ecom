<h2>New Order Received</h2>

<p>A new order has been placed.</p>

<p><strong>Order ID:</strong> #{{ $order->id }}</p>

<p><strong>Customer:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>

<p><strong>Email:</strong>
{{ $order->user?->email ?? $order->guest_email }}
</p>

<p><strong>Phone:</strong>
{{ $order->guest_phone ?? $order->address->phone }}
</p>

<p><strong>Total:</strong>
Rs {{ number_format($order->grand_total) }}
</p>

<p><strong>Payment Method:</strong>
{{ ucfirst($order->payment_method) }}
</p>

<p><strong>Status:</strong>
{{ ucfirst($order->status) }}
</p>