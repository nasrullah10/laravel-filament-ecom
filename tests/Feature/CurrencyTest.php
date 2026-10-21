<?php

namespace Tests\Feature;

use App\Support\Price;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_frontend_switch_converts_amounts_and_keeps_english_layout(): void
    {
        Http::fake(['open.er-api.com/*' => Http::response([
            'result' => 'success', 'base_code' => 'PKR', 'rates' => ['SAR' => 0.0135],
        ])]);
        $this->withSession(['locale' => 'ar'])->post('/currency/SAR', ['return' => '/products?page=2'])
            ->assertRedirect('/products?page=2')->assertSessionHas('display_currency', 'SAR');
        $this->get('/products')->assertOk()->assertSee('lang="en" dir="ltr"', false)
            ->assertSee('SAR 6.75')->assertSee('Orders are charged in PKR.');
        $this->assertSame('SAR 13.50', Price::format(1000));
        $this->assertSame('SAR 0.00', Price::format(0));
        $this->assertSame('SAR 1,350.00', Price::format(100000));

        $this->post('/currency/PKR')->assertSessionHas('display_currency', 'PKR');
        $this->get('/products')->assertOk()->assertSee('PKR 500.00')->assertDontSee('SAR 6.75');
        $this->assertSame('PKR 1,000.00', Price::format(1000));
        $this->post('/currency/SAR');
        Http::assertSentCount(1);
    }

    public function test_unavailable_rate_does_not_switch_or_relabel_pkr_as_sar(): void
    {
        Http::fake(['*' => Http::response([], 503)]);
        $this->post('/currency/SAR')->assertSessionHas('error')->assertSessionMissing('display_currency');
        $this->assertSame('PKR 1,000.00', Price::format(1000));
    }

    public function test_riyal_uses_a_decimal_dot_and_two_decimal_places(): void
    {
        $this->withSession(['display_currency' => 'SAR', 'pkr_sar_rate' => 0.013553]);
        $this->assertSame('SAR 135.53', Price::format(10000));
    }

    public function test_invalid_rate_is_rejected(): void
    {
        Http::fake(['*' => Http::response(['result' => 'success', 'base_code' => 'PKR', 'rates' => ['SAR' => -1]])]);
        $this->post('/currency/SAR')->assertSessionHas('error')->assertSessionMissing('display_currency');
    }

    public function test_invalid_currency_and_external_redirects_are_rejected(): void
    {
        $this->post('/currency/USD')->assertNotFound();
        foreach (['https://example.com', '//example.com', '/\\example.com'] as $path) {
            $this->post('/currency/PKR', ['return' => $path])->assertRedirect('/');
        }
    }
}
