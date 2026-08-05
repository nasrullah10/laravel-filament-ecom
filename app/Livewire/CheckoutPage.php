<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Title;
use App\Models\Order;
use App\Models\Address;
use Stripe\Stripe;
use App\Mail\OrderPlaced;
use App\Mail\CustomerOrderPlaced;
use App\Mail\AdminNewOrder;
use Illuminate\Support\Facades\Mail;

use Livewire\Component;
#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name; 
    public $phone;
    public $email;              // Guest ke liye
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method = 'cod';

    public function mount()
    {
        $this->payment_method = 'cod';
        $cart_items = CartManagement::getCartItemsFromCookie();
        if (count($cart_items) === 0) {
            return redirect()->route('products');
        }

        // Agar user logged in hai, auto-fill karein
        if (auth()->check()) {
            $user = auth()->user();
            $this->first_name = $user->name ?? '';
            $this->email = $user->email ?? '';
            
            // Last order se address fill
            $lastOrder = Order::where('user_id', $user->id)
                              ->with('address')
                              ->latest()
                              ->first();
            
            if ($lastOrder && $lastOrder->address) {
                $this->phone = $lastOrder->address->phone;
                $this->street_address = $lastOrder->address->street_address;
                $this->city = $lastOrder->address->city;
                $this->state = $lastOrder->address->state;
                $this->zip_code = $lastOrder->address->zip_code;
            }
        }
    }

    public function placeOrder()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',       
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'payment_method' => 'required',
        ];

        // Guest ke liye email required
        if (auth()->guest()) {
            $rules['email'] = 'required|email';
        }

        $this->validate($rules);
        $cart_items = CartManagement::getCartItemsFromCookie();
         $subTotal = CartManagement::calculateGrandTotal($cart_items);

        // Shipping Rule
        $shippingAmount = $subTotal >= 10000 ? 0 : 180;

        // Final Total
        $grandTotal = $subTotal + $shippingAmount;
        
        if (count($cart_items) === 0) {
            return redirect()->route('products');
        }
        
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
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_amount'      => $item['unit_amount'],
                'total_amount'      => $item['total_amount'],
            ];
        }

         $order  = new Order();
        $order->user_id = auth()->id();                     // NULL agar guest
        $order->guest_email = auth()->guest() ? $this->email : null;
        $order->guest_phone = auth()->guest() ? $this->phone : null;
        $order->first_name = $this->first_name;
        $order->last_name = $this->last_name;
        $order->grand_total = $grandTotal;
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'pkr';   
        $order->shipping_amount = $shippingAmount;
        $order->shipping_method = $shippingAmount == 0 ? 'Free Shipping' : 'Standard Shipping';
        $order->notes = auth()->check() ? 'Order placed by ' . auth()->user()->name : 'Guest order';
        
        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;
        
        $redirect_url ='';
        
        if($order->payment_method === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            
            // Guest ya user dono ka email handle
            $customerEmail = auth()->check() ? auth()->user()->email : $this->email;
            
            $sessionCheckout = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $stripe_line_items,
                'mode' => 'payment',
                'customer_email' => $customerEmail,
                'success_url' => route('checkout.success'). '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ]);
            $redirect_url = $sessionCheckout->url;

        } else {
           $redirect_url = route('checkout.success', [
    'orderId' => $order->id,
]);
            // $redirect_url = route('checkout.success');
        }
        
        $order->save();
        $address->order_id = $order->id;
        $address->save();
        
        $order->items()->createMany($db_line_items);
        $customerEmail = auth()->check()
            ? auth()->user()->email
            : $this->email;

        Mail::to($customerEmail)
            ->send(new CustomerOrderPlaced($order));
            Mail::to(config('mail.admin_email'))
        ->send(new AdminNewOrder($order));
        
        // Send email notification
        // if (auth()->check()) {
        //     Mail::to(auth()->user()->email)->send(new OrderPlaced($order));
        // } else {
        //     Mail::to($this->email)->send(new OrderPlaced($order));
        // }
        
        CartManagement::clearCartItems();
        
        // Guest ke liye session mein save karein
        if (auth()->guest()) {
            session()->put('last_guest_order', $order->id);
            session()->put('last_guest_email', $this->email);
             $redirect_url = route('checkout.success', [
    'orderId' => $order->id,
]);
        }
        
        return redirect($redirect_url);
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $sub_total = CartManagement::calculateGrandTotal($cart_items);

        $shipping_amount = $sub_total >= 10000 ? 0 : 180;

        $grand_total = $sub_total + $shipping_amount;
        // Dynamic images ke saath
        $cartItemsWithImages = collect($cart_items)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            $images = [];
            if ($product) {
                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                if (!is_array($images)) {
                    $images = [$product->images ?? $product->image ?? null];
                }
            }
            $item['image'] = $images[0] ?? $product->image ?? null;
            return $item;
        })->toArray();
        
        return view('livewire.checkout-page', [
            'cart_items' => $cartItemsWithImages,
            'sub_total' => $sub_total,
            'shipping_amount' => $shipping_amount,
            'grand_total' => $grand_total,
        ]);
    }
}