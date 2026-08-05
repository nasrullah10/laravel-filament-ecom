<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;


#[Title('Order Details - NAAS Shopping')]
class MyOrderDetailPage extends Component
{
    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }

    public function render()
    {
        $order_items = OrderItem::with('product')->where('order_id', $this->order_id)->get();
        $address = Address::where('order_id', $this->order_id)->first();
        $order = Order::findOrFail($this->order_id);
        return view('livewire.my-order-detail-page', compact('order_items', 'address', 'order'));
    }
}
