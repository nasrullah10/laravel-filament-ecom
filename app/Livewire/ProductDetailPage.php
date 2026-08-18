<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ProductDetailPage extends Component
{
    public Product $product;
    public $slug;
    public $quantity = 1;
    public $relatedProducts = [];
    public function mount($slug)
    {
        $this->slug = $slug;
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
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);
        
        // 👇 Sirf dispatch, ->to() nahi chahiye
        $this->dispatch('update-to-cart', total_count: $total_count);
        
        LivewireAlert::title('Added to cart!')
            ->success()
            ->show();
    }

    public function buyNow($product_id)
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()
            ->whereKey($product_id)
            ->where('is_active', true)
            ->firstOrFail();

        if (! $product->in_stock) {
            LivewireAlert::title('This product is currently out of stock.')
                ->warning()
                ->show();

            return;
        }

        CartManagement::addItemToCartWithQuantity($product->id, $this->quantity);

        return redirect()->route('checkout');
    }

    public function render()
    {
        $product = Product::with('brand')->where('slug', $this->slug)->firstOrFail();
        return view('livewire.product-detail-page', [
            'product' => $product,
        ])->title($product->name.' - NAAS Shopping');
    }
}
