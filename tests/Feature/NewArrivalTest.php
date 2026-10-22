<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Livewire\HomePage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class NewArrivalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_create_and_unmark_a_new_arrival(): void
    {
        $admin = User::firstOrCreate(['email' => 'superadmin@gmail.com'], ['name' => 'Admin', 'password' => bcrypt('testing-password')]);
        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $category = Category::create(['name' => 'Arrival Test', 'slug' => 'arrival-test']);
        $brand = Brand::create(['name' => 'Arrival Test', 'slug' => 'arrival-test']);
        $slug = 'arrival-'.uniqid();

        Livewire::test(CreateProduct::class)->fillForm([
            'name' => 'Arrival Test Product', 'slug' => $slug,
            'description' => 'New arrival test', 'price' => 1000,
            'category_id' => $category->id, 'brand_id' => $brand->id,
            'is_active' => true, 'is_new_arrival' => true,
        ])->call('create')->assertHasNoFormErrors();

        $product = Product::where('slug', $slug)->firstOrFail();
        $this->assertTrue($product->is_new_arrival);
        Livewire::test(HomePage::class)->assertViewHas('featuredProducts', fn ($products) => $products->contains('id', $product->id));

        $product->update(['is_active' => false]);
        Livewire::test(HomePage::class)->assertViewHas('featuredProducts', fn ($products) => ! $products->contains('id', $product->id));
        $product->update(['is_active' => true]);

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm(['is_new_arrival' => false])->call('save')->assertHasNoFormErrors();
        $this->assertFalse($product->fresh()->is_new_arrival);
        Livewire::test(HomePage::class)->assertViewHas('featuredProducts', fn ($products) => ! $products->contains('id', $product->id));
    }
}
