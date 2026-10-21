<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    public function test_language_selection_is_saved_and_returns_to_local_page(): void
    {
        $this->post('/language/ar', ['return' => '/products?page=2'])
            ->assertRedirect('/products?page=2')
            ->assertSessionHas('locale', 'ar');

        Route::middleware('web')->get('/locale-probe', fn () => [
            'locale' => app()->getLocale(),
            'label' => __('Cart'),
        ]);
        $this->get('/locale-probe')->assertJson(['locale' => 'ar', 'label' => 'السلة']);
        $this->post('/language/en')->assertSessionHas('locale', 'en');
        $this->get('/locale-probe')->assertJson(['locale' => 'en', 'label' => 'Cart']);
    }

    public function test_unsupported_language_is_rejected(): void
    {
        $this->post('/language/fr')->assertNotFound();
    }

    public function test_admin_login_uses_arabic_and_rtl(): void
    {
        $this->withSession(['locale' => 'ar'])->get('/admin/login')
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('العربية');
    }

    public function test_external_redirects_are_rejected(): void
    {
        foreach (['https://example.com', '//example.com', '/\\example.com'] as $path) {
            $this->post('/language/ar', ['return' => $path])->assertRedirect('/');
        }
    }

    public function test_invalid_session_locale_falls_back_to_english(): void
    {
        Route::middleware('web')->get('/locale-probe', fn () => app()->getLocale());
        $this->withSession(['locale' => 'invalid'])->get('/locale-probe')->assertSee('en');
    }
}
