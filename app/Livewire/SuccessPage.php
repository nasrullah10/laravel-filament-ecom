<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Models\Order;
use Stripe\Stripe;
#[Title('Success NaasShopping')]



class SuccessPage extends Component
{
   #[Url]
public $orderId;
public function render()
{
    $order = Order::with(['address','items.product'])
        ->findOrFail($this->orderId);

    // dd($order);

    return view('livewire.success-page', compact('order'));
}
}
