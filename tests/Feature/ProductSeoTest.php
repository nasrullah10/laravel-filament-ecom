<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductSeoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_uses_custom_meta_title_and_description(): void
    {
        $product = Product::query()->firstOrFail();
        $product->update([
            'meta_title' => 'Premium Test Product in Pakistan',
            'meta_description' => 'A custom product meta description written for Google search results in Pakistan.',
        ]);

        $this->get(route('product-detail', $product->slug))
            ->assertOk()
            ->assertSee('<title>Premium Test Product in Pakistan</title>', false)
            ->assertSee('name="description" content="A custom product meta description written for Google search results in Pakistan."', false)
            ->assertSee('property="og:title" content="Premium Test Product in Pakistan"', false);
    }

    public function test_product_seo_falls_back_when_custom_fields_are_blank(): void
    {
        $product = Product::query()->firstOrFail();
        $product->update(['meta_title' => null, 'meta_description' => null]);

        $this->get(route('product-detail', $product->slug))
            ->assertOk()
            ->assertSee('<title>'.$product->name.' - NAAS Shopping</title>', false)
            ->assertSee('name="description"', false);
    }
}
