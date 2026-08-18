<?php

use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\CancelPage;
use App\Livewire\SuccessPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CategoriesPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\BlogPage;
use App\Livewire\BlogDetailPage;
use App\Livewire\Pages\ShowPage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
Mail::raw('This is a test email from NAAS Shopping.', function ($message) {
$message->to('info@naasshopping.com')
->subject('NAAS Shopping Email Test');
});

return 'Email sent successfully!';
});


use App\Http\Controllers\Auth\GoogleController;
// routes/web.php
Route::get('/html-sitemap', \App\Livewire\HtmlSitemap::class)->name('html-sitemap');
Route::get('/sitemap.xml', function () {
    $sitemapPath = public_path('sitemap.xml');

    abort_unless(file_exists($sitemapPath), 404);

    return response()->file($sitemapPath, [
        'Content-Type' => 'application/xml; charset=UTF-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('sitemap.xml');
Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/privacy-policy', ShowPage::class)->name('privacy-policy');
Route::get('/terms-conditions', ShowPage::class)->name('terms-conditions');
Route::get('/about-us', ShowPage::class)->name('about-us');
Route::get('/contact-us', ShowPage::class)->name('contact-us');
Route::get('/page/{slug}', ShowPage::class)->name('page.show');

// Public Routes (No Auth Required)
Route::get('/', HomePage::class)->name('home');
Route::get('/categories', CategoriesPage::class)->name('categories');
Route::get('/products', ProductsPage::class)->name('products');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/products/{slug}', ProductDetailPage::class)->name('product-detail');
Route::get('/blog', BlogPage::class)->name('blog.index');
Route::get('/blog/{slug}', BlogDetailPage::class)->name('blog.show');

// Checkout Routes (Guest + Auth Both)
// ✅ Checkout routes — Livewire components (Controller Nahi!)
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/success', SuccessPage::class)->name('checkout.success');   // ✅ Livewire
Route::get('/cancel', CancelPage::class)->name('checkout.cancel');       // ✅ Livewire
// Guest Only Routes (Auth nahi honi chahiye)
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class)->name('register');
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordPage::class)->name('password.reset');
});


// Auth Only Routes (Login required)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
    Route::get('/my-order-detail/{order_id}', MyOrderDetailPage::class)->name('my-order-detail');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});
