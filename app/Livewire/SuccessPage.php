<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Models\Order;
use Stripe\Stripe;
#[Title('Success Page')]

class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $order = Order::with('address')->where('user_id', auth()->id())->latest()->first();
        if($this->session_id){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session_info = \Stripe\Checkout\Session::retrieve($this->session_id);
            // dd($session_info);
            if($session_info->payment_status != 'paid'){
                // Handle payment not successful
                $order->payment_status = 'failed';
                $order->save();
            }else{
                $order->payment_status = 'paid';
                $order->save();
            }
        }
        return view('livewire.success-page', compact('order'));
    }
}
