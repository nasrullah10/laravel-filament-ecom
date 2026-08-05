<h2>Thank You {{ $order->first_name }}</h2>

<p>Your order has been placed successfully.</p>

<p>
Order ID:
<b>#{{ $order->id }}</b>
</p>

<p>
Total:
<b>Rs {{ number_format($order->grand_total) }}</b>
</p>

<p>
Payment:
{{ ucfirst($order->payment_method) }}
</p>

<p>Thank you for shopping with us.</p>