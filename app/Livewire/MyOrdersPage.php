<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
#[Title('My Orders - NAAS Shopping')]
class MyOrdersPage extends Component
{
    use WithPagination;
    public function render()
    {
        $latest_orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('livewire.my-orders-page', compact('latest_orders'));
    }
}
