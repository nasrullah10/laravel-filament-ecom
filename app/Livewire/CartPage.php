<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;

#[Title('Cart nasrullah')]


class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;
    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }
    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeItemFromCart($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }
    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagement::incrementItemQuantity($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }
    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-to-cart',total_count: count($this->cart_items))->to(Navbar::class);
    }
    public function render()
    {
        return view('livewire.cart-page');
    }
}
