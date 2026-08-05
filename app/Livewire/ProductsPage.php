<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Products - NAAS Shopping')]
class ProductsPage extends Component
{
    use WithPagination;
    
    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $featured = [];
    #[Url]
    public $on_sale = [];
    #[Url]
    public $price_range = 50000;
    #[Url]
    public $sort = 'latest';

    #[Url(as: 'q')]
    public string $search = '';

    // Add this for URL category parameter
    #[Url]
    public $category = null;

    public function mount()
    {
        // If category slug is passed in URL, find its ID and add to selected_categories
        // if ($this->category) {
        //     $category = Category::where('slug', $this->category)->first();
        //     if ($category) {
        //         $this->selected_categories[] = $category->id;
        //     }
        // }
        if ($this->category) {

            $category = Category::where('slug', $this->category)->first();
        
            if ($category) {
        
                $this->selected_categories = [$category->id];
        
                $childIds = Category::where('parent_id', $category->id)
                    ->pluck('id')
                    ->toArray();
        
                $this->selected_categories = array_merge(
                    $this->selected_categories,
                    $childIds
                );
            }
        }
    }

    // add to cart item method
    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-to-cart', total_count: $total_count)->to(Navbar::class);
        LivewireAlert::title('Added to cart!')->success()->show();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()->where('is_active', 1);

        if (trim($this->search) !== '') {
            $term = trim($this->search);
            $products->where(function ($query) use ($term) {
                $query->where('name', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%')
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%'.$term.'%'))
                    ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', '%'.$term.'%'));
            });
        }
        
        // if(!empty($this->selected_categories)){
        //     $products->whereIn('category_id', $this->selected_categories);
        // } 
        if (!empty($this->selected_categories)) {

            $categoryIds = collect($this->selected_categories);
        
            // Parent categories ki children bhi include karo
            $childIds = Category::whereIn('parent_id', $this->selected_categories)
                ->pluck('id');
        
            $categoryIds = $categoryIds
                ->merge($childIds)
                ->unique()
                ->values();
        
            $products->whereIn('category_id', $categoryIds);
        }
        
        if(!empty($this->selected_brands)){
            $products->whereIn('brand_id', $this->selected_brands);
        }    
        
        if(!empty($this->featured)){
            $products->where('is_featured', 1);
        }    
        
        if(!empty($this->on_sale)){
            $products->where('on_sale', 1);
        }  
        
        if($this->price_range){
            $products->whereBetween('price', [0, $this->price_range]);
        }  
        
        if($this->sort == 'latest'){
            $products->latest();
        }
        
        if($this->sort == 'price'){
            $products->orderBy('price', 'asc');
        }
        
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        
        return view('livewire.products-page', [
            'products' => $products->paginate(18),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
