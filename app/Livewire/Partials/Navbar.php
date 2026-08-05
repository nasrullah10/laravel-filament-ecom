<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Helpers\CartManagement;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $total_count = 0;
    public $menuCategories = [];
    public string $search = '';
    public bool $showSearch = false;

    public function mount()
    {
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
        
        // ðŸ‘‡ Categories fetch karein
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

    public function openSearch(): void
    {
        $this->showSearch = true;
    }

    public function closeSearch(): void
    {
        $this->showSearch = false;
        $this->search = '';
    }

    public function submitSearch()
    {
        $term = trim($this->search);

        if ($term === '') {
            return;
        }

        return redirect()->route('products', ['q' => $term]);
    }

    public function render()
    {
        $searchResults = collect();

        if ($this->showSearch && mb_strlen(trim($this->search)) >= 2) {
            $term = trim($this->search);
            $searchResults = Product::query()
                ->where('is_active', true)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%'.$term.'%')
                        ->orWhere('description', 'like', '%'.$term.'%')
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%'.$term.'%'))
                        ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', '%'.$term.'%'));
                })
                ->latest()
                ->limit(6)
                ->get();
        }

        return view('livewire.partials.navbar', compact('searchResults'));
    }
}
