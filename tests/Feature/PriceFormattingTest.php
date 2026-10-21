<?php

namespace Tests\Feature;

use App\Support\Price;
use Tests\TestCase;

class PriceFormattingTest extends TestCase
{
    public function test_frontend_prices_change_after_using_the_language_switcher(): void
    {
        $this->post('/language/ar', ['return' => '/products'])->assertRedirect('/products');
        $this->get('/products')->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('٥٠٠٫٠٠ روبية باكستانية');

        $this->post('/language/en', ['return' => '/products'])->assertRedirect('/products');
        $this->get('/products')->assertOk()
            ->assertSee('dir="ltr"', false)
            ->assertSee('PKR 500.00')
            ->assertDontSee('روبية باكستانية');
    }

    public function test_prices_follow_the_selected_language_without_converting_amounts(): void
    {
        app()->setLocale('en');
        $this->assertSame('PKR 1,234.50', Price::format('1234.50'));
        app()->setLocale('ar');
        $this->assertSame('١٬٢٣٤٫٥٠ روبية باكستانية', Price::format('1234.50'));
        $this->assertSame('٠٫٠٠ روبية باكستانية', Price::format(0));
        app()->setLocale('en');
        $this->assertSame('PKR 1,234.50', Price::format('1234.50'));
    }
}
