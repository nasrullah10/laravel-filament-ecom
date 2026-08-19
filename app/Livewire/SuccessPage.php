<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Models\Order;
#[Title('Order Success - NAAS Shopping')]



class SuccessPage extends Component
{
    #[Url]
    public $orderId;
    public ?Order $order = null;

    public function mount()
    {
        $this->orderId = $this->orderId
            ?: session('last_order_id')
            ?: session('last_guest_order');

        if (! $this->orderId) {
            session()->flash('error', 'Order confirmation is available immediately after placing an order.');

            return $this->redirectRoute('products');
        }

        $orderQuery = Order::with(['address', 'items.product'])
            ->whereKey($this->orderId);

        if (auth()->check()) {
            $orderQuery->where('user_id', auth()->id());
        } else {
            $guestOrderId = session('last_guest_order') ?: session('last_order_id');
            $orderQuery->whereKey($guestOrderId);
        }

        $this->order = $orderQuery->first();

        if (! $this->order) {
            session()->flash('error', 'This order confirmation is no longer available.');

            return $this->redirectRoute('products');
        }
    }

    public function render()
    {
        $order = $this->order;

        return view('livewire.success-page', compact('order'));
    }
}
