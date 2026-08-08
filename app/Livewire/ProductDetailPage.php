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
    public $relatedProducts = [];
    public function mount($slug)
    {
        $this->product = Product::with('brand')->where('slug', $slug)->firstOrFail();
        
        // Fetch related products (same category, exclude current)
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', 1)
            ->limit(4)
            ->get();
    }

    public function increaseQuantity()
    {
        $this->quantity++;
        $this->dispatch('refresh');
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->dispatch('refresh');
        }
    }

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);
        
        // 👇 Sirf dispatch, ->to() nahi chahiye
        $this->dispatch('update-to-cart', total_count: $total_count);
        
        LivewireAlert::title('Added to cart!')
            ->success()
            ->show();
    }

    public function render()
    {
        $product = Product::with('brand')->where('slug', $this->slug)->firstOrFail();
        return view('livewire.product-detail-page', [
            'product' => $product,
        ]);
    }
}
