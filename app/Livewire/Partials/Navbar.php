<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Helpers\CartManagement;
use App\Models\Category;  // 👈 Import karein
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $total_count = 0;
    public $menuCategories = [];  // 👈 Yeh add karein

    public function mount()
    {
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
        
        // 👇 Categories fetch karein
        $this->menuCategories = Category::with('children')
            ->whereNull('parent_id')  // Sirf parent categories
            ->orderBy('name')
            ->get();
    }

    #[On('update-to-cart')]
    public function updateCartItem($total_count)
    {
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}