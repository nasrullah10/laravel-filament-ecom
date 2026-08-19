<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuccessPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_direct_success_url_without_an_order_redirects_instead_of_returning_404(): void
    {
        $this->get('/success')
            ->assertRedirect(route('products'))
            ->assertSessionHas('error');
    }

    public function test_customer_can_view_their_own_order_confirmation(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'grand_total' => 3800,
            'shipping_amount' => 180,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'new',
            'currency' => 'PKR',
        ]);

        $this->actingAs($user)
            ->get('/success?orderId='.$order->id)
            ->assertOk()
            ->assertSee('Thank you. Your order has been received.')
            ->assertSee('#'.$order->id);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $owner = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
            'grand_total' => 3800,
            'shipping_amount' => 180,
            'payment_method' => 'cod',
            'status' => 'new',
        ]);

        $this->actingAs($otherCustomer)
            ->get('/success?orderId='.$order->id)
            ->assertRedirect(route('products'))
            ->assertSessionHas('error');
    }
}
