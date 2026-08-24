<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShopSeoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_category_page_has_unique_metadata_and_one_h1(): void
    {
        $category = Category::create([
            'name' => 'Premium Abayas',
            'slug' => 'premium-abayas',
            'is_active' => true,
        ]);

        $response = $this->get(route('products', ['category' => $category->slug]));

        $response->assertOk()
            ->assertSee('<title>Premium Abayas Online in Pakistan - NAAS Shopping</title>', false)
            ->assertSee('rel="canonical" href="'.route('products', ['category' => $category->slug]).'"', false)
            ->assertSee('Shop Premium Abayas');

        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_search_and_filters_are_not_indexed(): void
    {
        $this->get(route('products', ['q' => 'abaya']))
            ->assertOk()
            ->assertSee('name="robots" content="noindex,follow"', false);

        $this->get(route('products', ['sort' => 'price']))
            ->assertOk()
            ->assertSee('name="robots" content="noindex,follow"', false);
    }

    public function test_sitemap_is_publicly_available(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertHeader('content-type', 'application/xml; charset=UTF-8');
    }
}
