<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ProductDetailPage extends Component
{
    public $slug;
    public $quantity = 1;
    public function mount($slug)
    {
        $this->slug = $slug;
    }
    public function increaseQuantity()
    {
        $this->quantity++;
    }
    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    // add to cart item method
    public function addToCart($product_id)
    {
        // dd($product_id);
        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);
        $dispatch = $this->dispatch('update-to-cart', total_count: $total_count)->to(Navbar::class);
        LivewireAlert::title('Changes saved!')
        ->success()
        ->show();
    }
    public function render()
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();
        return view('livewire.product-detail-page', [
            'product' => $product,
        ]);
    }
}
