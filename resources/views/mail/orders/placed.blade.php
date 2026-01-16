<x-mail::message>
# Order placed successfully!

Thanks for your order  number is. {{ $order->id }}

<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
