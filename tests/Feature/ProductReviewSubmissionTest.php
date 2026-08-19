<?php

namespace Tests\Feature;

use App\Livewire\ProductDetailPage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductReviewSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_delivered_order_customer_can_submit_a_pending_review_and_sees_feedback(): void
    {
        Storage::fake('public');
        $product = Product::query()->firstOrFail();
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'grand_total' => $product->price,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
            'currency' => 'PKR',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_amount' => $product->price,
            'total_amount' => $product->price,
        ]);

        $video = UploadedFile::fake()->create('customer-review.mp4', 1024, 'video/mp4');

        Livewire::actingAs($user)
            ->test(ProductDetailPage::class, ['slug' => $product->slug])
            ->set('reviewRating', 5)
            ->set('reviewTitle', 'Excellent product')
            ->set('reviewComment', 'The quality and finish are excellent.')
            ->call('selectReviewMedia', 'video')
            ->set('reviewVideo', $video)
            ->call('submitReview')
            ->assertHasNoErrors()
            ->assertSet('reviewFeedbackType', 'success')
            ->assertSee('Review submitted')
            ->assertSee('pending admin approval');

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'status' => 'pending',
            'is_verified_purchase' => 1,
        ]);

        $review = ProductReview::where('product_id', $product->id)->where('user_id', $user->id)->firstOrFail();
        $this->assertCount(1, $review->videos);
        Storage::disk('public')->assertExists($review->videos[0]);

        $review->update(['status' => 'approved']);

        Livewire::actingAs($user)
            ->test(ProductDetailPage::class, ['slug' => $product->slug])
            ->assertSee(basename($review->videos[0]));
    }

    public function test_review_validation_errors_are_visible(): void
    {
        $product = Product::query()->firstOrFail();
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'grand_total' => $product->price,
            'status' => 'delivered',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_amount' => $product->price,
            'total_amount' => $product->price,
        ]);

        Livewire::actingAs($user)
            ->test(ProductDetailPage::class, ['slug' => $product->slug])
            ->set('reviewComment', 'short')
            ->call('submitReview')
            ->assertHasErrors(['reviewComment' => 'min'])
            ->assertSee('Please correct the highlighted fields.');

        $this->assertDatabaseMissing('product_reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_reviews_are_paginated_five_at_a_time_and_can_be_filtered_by_rating(): void
    {
        $product = Product::query()->firstOrFail();
        ProductReview::where('product_id', $product->id)->delete();

        foreach ([5, 5, 4, 3, 2, 1] as $rating) {
            $user = User::factory()->create();
            ProductReview::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'rating' => $rating,
                'comment' => 'A valid customer review for pagination testing.',
                'status' => 'approved',
                'is_verified_purchase' => true,
            ]);
        }

        Livewire::test(ProductDetailPage::class, ['slug' => $product->slug])
            ->assertViewHas('reviews', fn ($reviews) => $reviews->count() === 5 && $reviews->total() === 6)
            ->call('filterReviewsByRating', '5')
            ->assertSet('reviewRatingFilter', '5')
            ->assertViewHas('reviews', fn ($reviews) => $reviews->count() === 2 && $reviews->total() === 2)
            ->assertSee('2 shown');
    }
}
