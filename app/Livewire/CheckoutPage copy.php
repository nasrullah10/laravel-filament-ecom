<?php

namespace App\Livewire;
use App\Helpers\CartManagement;
use App\Livewire\Title;
use App\Models\Order;
use App\Models\Address;
use Stripe\Stripe;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;

use Livewire\Component;
#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name; 
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        if (count($cart_items) === 0) {
            return redirect()->route('products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',       
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        $cart_items = CartManagement::getCartItemsFromCookie();
        
       $stripe_line_items = [];
        $db_line_items = [];

        foreach ($cart_items as $item) {
          
            // Stripe format
            $stripe_line_items[] = [
                'price_data' => [
                    'currency' => 'pkr',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['unit_amount'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];

            // Database format
            $db_line_items[] = [
                'product_id' => $item['product_id'], // make sure CartManagement returns product_id
                'quantity'   => $item['quantity'],
                'unit_amount'      => $item['unit_amount'], // or whatever your column is
                'total_amount'      => $item['total_amount'], // or whatever your column is
            ];
        }

        $order  = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'pkr';   
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = ' Order place by ' . auth()->user()->name;
        
        $address = new Address();
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;
        
        $redirect_url ='';
        if($order->payment_method === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $stripe_line_items,
                'mode' => 'payment',
                'customer_email' => auth()->user()->email,
                'success_url' => route('checkout.success'). '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),

            ]);
            $redirect_url = $sessionCheckout->url;

        }else{
            $redirect_url = route('checkout.success');
        }
        
        $order->save();
        $address->order_id = $order->id;
        $address->save();
        
        $order->items()->createMany($db_line_items);
        // Send email notification
        // Mail::to(auth()->user()->email)->send(new OrderPlaced($order));
        CartManagement::clearCartItems();
        
        return redirect($redirect_url);
        
       
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', compact('cart_items', 'grand_total'));
    }
}
