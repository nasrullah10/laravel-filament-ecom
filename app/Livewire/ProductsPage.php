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
#[Title('Products Page - Laravel Filament Ecommerce')]
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
    public $price_range = 500000;
    #[Url]
    public $sort = 'latest';

    // add to cart item method
    public function addToCart($product_id)
    {
        // dd($product_id);
        $total_count = CartManagement::addItemToCart($product_id);
        $dispatch = $this->dispatch('update-to-cart', total_count: $total_count)->to(Navbar::class);
        LivewireAlert::title('Changes saved!')
        ->success()
        ->show();
    }
    public function render()
    {
        
        $products = Product::query()
            ->where('is_active', 1);
        if(!empty($this->selected_categories)){
            $products
            ->whereIn('category_id', $this->selected_categories);
        } 
        if(!empty($this->selected_brands)){
            $products
            ->whereIn('brand_id', $this->selected_brands);
        }    
        if(!empty($this->featured)){
            $products
            ->where('is_featured', 1);
        }    
        if(!empty($this->on_sale)){
            $products
            ->where('on_sale', 1);
        }  
        if($this->price_range){
            $products
            ->whereBetween('price', [0, $this->price_range]);
        }  
        if($this->sort == 'latest'){
            $products
            ->latest();
        }
        if($this->sort == 'price'){
            $products
            ->orderBy('price', 'asc');
        }
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.products-page', [
            'products' => $products->paginate(9),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
