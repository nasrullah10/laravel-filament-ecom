<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;

#[Title('Cart - NAAS Shopping')]

class CartPage extends Component
{
    public $cart_items = [];
    public $sub_total;
    public $shipping_amount;
    public $grand_total;
    public $relatedProducts = []; // Add this

    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();

        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);
    
        $this->shipping_amount = $this->sub_total >= 10000 ? 0 : 180;
    
        $this->grand_total = $this->sub_total + $this->shipping_amount;
        $this->loadRelatedProducts(); // Add this
    }

    public function loadRelatedProducts()
    {
        // Get cart product IDs
        $cartProductIds = array_column($this->cart_items, 'product_id');
        
        // Fetch related products excluding cart items
        $this->relatedProducts = Product::where('is_active', 1)
            ->where('is_featured', 1)
            ->whereNotIn('id', $cartProductIds) // Exclude cart items
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeItemFromCart($product_id);
        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->shipping_amount = $this->sub_total >= 10000 ? 0 : 180;
        $this->grand_total = $this->sub_total + $this->shipping_amount;
        $this->loadRelatedProducts(); // Reload related products
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagement::incrementItemQuantity($product_id);
        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->shipping_amount = $this->sub_total >= 10000 ? 0 : 180;
        $this->grand_total = $this->sub_total + $this->shipping_amount;
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->shipping_amount = $this->sub_total >= 10000 ? 0 : 180;
        $this->grand_total = $this->sub_total + $this->shipping_amount;
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
