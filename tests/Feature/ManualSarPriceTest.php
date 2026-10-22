<?php

namespace Tests\Feature;

use App\Helpers\CartManagement;
use App\Livewire\CartPage;
use App\Models\Product;
use App\Support\Price;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ManualSarPriceTest extends TestCase
{
    use DatabaseTransactions;

    private function product(): Product
    {
        $category = \App\Models\Category::create(['name' => 'SAR Test', 'slug' => 'sar-test', 'is_active' => true]);
        $brand = \App\Models\Brand::create(['name' => 'SAR Test', 'slug' => 'sar-test', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Manual SAR Product', 'slug' => 'manual-sar-product-'.uniqid(), 'price' => 10000,
            'description' => 'Test description', 'images' => [],
            'category_id' => $category->id, 'brand_id' => $brand->id,
        ]);
        $product->update(['price' => 10000, 'price_sar' => 175.25, 'compare_price_sar' => 200, 'is_active' => true]);
        $this->withSession(['display_currency' => 'SAR', 'pkr_sar_rate' => 0.013553]);
        return $product->fresh();
    }

    public function test_saved_admin_price_is_used_in_product_page_and_pkr_is_preserved(): void
    {
        $product = $this->product();
        $this->get(route('product-detail', $product->slug))->assertOk()->assertSee('SAR 175.25')->assertSee('SAR 200.00');
        $this->assertSame('SAR 175.25', Price::product($product));
        $this->withSession(['display_currency' => 'PKR']);
        $this->assertSame('PKR 10,000.00', Price::product($product));
    }

    public function test_blank_manual_price_falls_back_and_zero_is_not_treated_as_blank(): void
    {
        $product = $this->product();
        $product->update(['price_sar' => null, 'compare_price_sar' => null]);
        $this->assertSame('SAR 135.53', Price::product($product->fresh()));
        $product->update(['price_sar' => 0]);
        $this->assertSame('SAR 0.00', Price::product($product->fresh()));
        $this->assertFalse(Price::hasCompare($product));
    }

    public function test_old_cart_and_checkout_use_current_manual_price_and_quantity(): void
    {
        $product = $this->product();
        $items = [['product_id' => $product->id, 'name' => $product->name, 'unit_amount' => 10000, 'total_amount' => 20000, 'quantity' => 2, 'unit_amount_sar' => 1]];
        $priced = CartManagement::withSarPrices($items);
        $this->assertEquals(175.25, $priced[0]['unit_amount_sar']);
        $this->assertEquals(350.50, Price::cartTotal($priced));
        $this->withCookie('cart_items', json_encode($items))->get('/checkout')
            ->assertOk()->assertSee('SAR 175.25')->assertSee('SAR 350.50');
        Livewire::withCookie('cart_items', json_encode($items))->test(CartPage::class)
            ->assertSee('SAR 350.50')->call('increaseQty', $product->id)->assertSee('SAR 525.75');
    }

    public function test_admin_can_save_manual_prices_and_reject_invalid_compare_price(): void
    {
        $product = $this->product();
        $admin = \App\Models\User::firstOrCreate(['email' => 'superadmin@gmail.com'], ['name' => 'Admin', 'password' => bcrypt('testing-password')]);
        $this->actingAs($admin);
        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        Livewire::test(\App\Filament\Resources\ProductResource\Pages\EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm(['price_sar' => 199.99, 'compare_price_sar' => 250])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame('199.99', $product->fresh()->price_sar);
        $this->assertSame('250.00', $product->fresh()->compare_price_sar);
        Livewire::test(\App\Filament\Resources\ProductResource\Pages\EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm(['price_sar' => 200, 'compare_price_sar' => 150])
            ->call('save')->assertHasFormErrors(['compare_price_sar']);
    }

    public function test_checkout_saves_the_displayed_sar_price_for_order_history(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $product = $this->product();
        $items = [['product_id' => $product->id, 'name' => $product->name, 'unit_amount' => 10000, 'total_amount' => 20000, 'quantity' => 2]];
        Livewire::withCookie('cart_items', json_encode($items))->test(\App\Livewire\CheckoutPage::class)
            ->set('first_name', 'Test')->set('last_name', 'Customer')
            ->set('email', 'sar-test@example.com')->set('phone', '03001234567')
            ->set('street_address', 'Test address')->set('city', 'Karachi')
            ->set('state', 'Sindh')->set('zip_code', '74000')->set('payment_method', 'cod')
            ->call('placeOrder')->assertHasNoErrors();
        $order = \App\Models\Order::where('guest_email', 'sar-test@example.com')->latest('id')->firstOrFail();
        $this->assertEquals(20000, $order->grand_total);
        $this->assertEquals(350.50, $order->grand_total_sar);
        $this->assertEquals(175.25, $order->items->first()->unit_amount_sar);
        $product->update(['price_sar' => 500]);
        $this->get('/success?orderId='.$order->id)->assertOk()->assertSee('SAR 350.50');
    }
}
